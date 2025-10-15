<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Services\RegistrationValidationService;
use App\Services\PricingService;
use App\Services\DynamicFormService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Controller untuk Manajemen Kompetisi dari sisi Peserta
 *
 * Menangani pendaftaran kompetisi oleh peserta
 */
class CompetitionController extends Controller
{
    protected $registrationValidationService;
    protected $pricingService;
    protected $dynamicFormService;
    protected $notificationService;

    public function __construct(
        RegistrationValidationService $registrationValidationService,
        PricingService $pricingService,
        DynamicFormService $dynamicFormService,
        NotificationService $notificationService
    ) {
        $this->registrationValidationService = $registrationValidationService;
        $this->pricingService = $pricingService;
        $this->dynamicFormService = $dynamicFormService;
        $this->notificationService = $notificationService;
    }

    /**
     * Tampilkan daftar kompetisi yang tersedia
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Competition::active()->openRegistration();
        
        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter berdasarkan harga
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'under_200k':
                    $query->where('price', '<', 200000);
                    break;
                case '200k_400k':
                    $query->whereBetween('price', [200000, 400000]);
                    break;
                case 'above_400k':
                    $query->where('price', '>', 400000);
                    break;
            }
        }
        
        // Filter berdasarkan tipe kompetisi
        if ($request->filled('type')) {
            if ($request->type === 'individual') {
                $query->where('allow_individual', true);
            } elseif ($request->type === 'team') {
                $query->where('is_team_competition', true);
            }
        }
        
        $competitions = $query->orderBy('registration_end', 'asc')->paginate(12);
        
        // Cek kompetisi yang sudah didaftari user
        $user = Auth::user();
        $registeredCompetitions = [];

        if ($user) {
            $registeredCompetitions = $user->registrations()
                ->pluck('competition_id')
                ->toArray();
        }

        return view('peserta.competitions.index', compact('competitions', 'registeredCompetitions'));
    }

    /**
     * Tampilkan detail kompetisi
     *
     * @param \App\Models\Competition $competition
     * @return \Illuminate\View\View
     */
    public function show(Competition $competition)
    {
        // Check if competition is active
        if ($competition->status !== 'active') {
            abort(403, 'Competition is not active');
        }

        $user = Auth::user();

        // Cek apakah user sudah mendaftar
        $existingRegistration = $competition->registrations()
            ->where('user_id', $user->id)
            ->first();

        // Cek apakah user sudah terdaftar di kompetisi lain (auto lock)
        $userRegistrations = $this->registrationValidationService->getUserRegistrations($user);
        $canRegister = $this->registrationValidationService->canUserRegisterForAnyCompetition($user);

        // Get pricing information
        $pricingSummary = $this->pricingService->getPricingSummary();
        $participantCategories = $this->pricingService->getParticipantCategories();
        
        // Get dynamic form requirements
        $dynamicRequirements = $this->dynamicFormService->getCompetitionRequirements($competition);
        $dynamicFormHTML = $this->dynamicFormService->generateFormHTML($competition);

        // Statistik kompetisi (removed participant counts)
        $stats = [
            'days_left' => now()->diffInDays($competition->registration_end, false),
            'is_early_bird' => $this->pricingService->isEarlyBirdPeriod(),
        ];

        return view('peserta.competitions.show', compact(
            'competition', 
            'existingRegistration', 
            'stats', 
            'userRegistrations', 
            'canRegister', 
            'pricingSummary', 
            'participantCategories',
            'dynamicRequirements',
            'dynamicFormHTML'
        ));
    }

    /**
     * Proses pendaftaran kompetisi
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Competition $competition
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request, Competition $competition)
    {
        $user = Auth::user();

        // Check if competition is active
        if ($competition->status !== 'active') {
            abort(403, 'Competition is not active');
        }

        // Validasi apakah kompetisi masih buka pendaftaran
        if (!$competition->isRegistrationOpen()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Pendaftaran untuk kompetisi ini sudah ditutup.']);
            }
            return back()->with('error', 'Pendaftaran untuk kompetisi ini sudah ditutup.');
        }

        // Validasi apakah sudah mendaftar
        $existingRegistration = Registration::where('user_id', $user->id)
            ->where('competition_id', $competition->id)
            ->first();

        if ($existingRegistration) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda sudah terdaftar dalam kompetisi ini.']);
            }
            return back()->with('error', 'Anda sudah terdaftar dalam kompetisi ini.');
        }

        // Validasi apakah masih ada slot
        if ($competition->isFullyBooked()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Kompetisi ini sudah penuh.']);
            }
            return back()->with('error', 'Kompetisi ini sudah penuh.');
        }
        
        // For AJAX requests, use default values from user profile
        if ($request->expectsJson()) {
            $request->merge([
                'phone' => $request->phone ?: $user->phone,
                'institution' => $request->institution ?: $user->institution,
                'gender' => $request->gender ?: null,
                'education_level' => $request->education_level ?: null,
                'emergency_contact' => $request->emergency_contact ?: $user->emergency_contact_name,
                'emergency_phone' => $request->emergency_phone ?: $user->emergency_contact_phone,
                'special_needs' => $request->special_needs ?: null,
            ]);
        }

        // Check for registration conflicts (auto lock)
        $teamMembers = $competition->is_team_competition ? ($request->team_members ?? []) : [];
        $conflicts = $this->registrationValidationService->checkRegistrationConflicts($user, $competition, $teamMembers);

        if (!empty($conflicts)) {
            $conflictMessages = $this->registrationValidationService->getConflictMessages($conflicts);

            if (!empty($conflictMessages['errors'])) {
                return back()->withErrors([
                    'registration_conflict' => $conflictMessages['errors']
                ])->withInput();
            }
        }

        // Get dynamic validation rules
        $dynamicValidation = $this->dynamicFormService->buildValidationRules($competition, $request->all());
        
        // Base validation rules
        $rules = [
            'phone' => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'special_needs' => 'nullable|string|max:500',
        ];
        
        // For competitions with dynamic requirements, use only dynamic validation
        if ($dynamicValidation['rules'] && count($dynamicValidation['rules']) > 0) {
            // Use dynamic validation for competitions that have CompetitionRequirement entries
            $rules = array_merge($rules, $dynamicValidation['rules']);
        } else {
            // Fallback to hardcoded validation for competitions without dynamic requirements
            if ($competition->isSpcCompetition()) {
                // Use SPC-specific validation rules (individual competition)
                $spcRules = Registration::getSpcValidationRules();
                $rules = array_merge($rules, $spcRules);
            }
        }
        
        // Validasi logo instansi
        $rules['logo_instansi'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';

        // Validasi untuk kompetisi tim
        if ($competition->is_team_competition) {
            if ($competition->isEdcCompetition() && (!isset($dynamicValidation['rules']) || count($dynamicValidation['rules']) == 0)) {
                // Use EDC-specific validation rules only if no dynamic rules exist
                $edcRules = Registration::getEdcValidationRules();
                $rules = array_merge($rules, $edcRules);
            } elseif ($competition->isKdbiCompetition() && (!isset($dynamicValidation['rules']) || count($dynamicValidation['rules']) == 0)) {
                // Use KDBI-specific validation rules only if no dynamic rules exist
                $kdbiRules = Registration::getKdbiValidationRules();
                $rules = array_merge($rules, $kdbiRules);
            } elseif ($competition->isDccCompetition()) {
                // Use DCC-specific validation rules
                $rules['team_name'] = 'required|string|max:100';
                $rules['team_members'] = 'required|array|size:3'; // DCC requires exactly 3 members
                $rules['team_members.*.name'] = 'required|string|max:255';
                $rules['team_members.*.school'] = 'required|string|max:255';
                $rules['team_members.*.phone'] = 'required|string|max:20';
                $rules['team_members.*.photo_3x4'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
                $rules['team_members.*.student_status_letter'] = 'required|file|mimes:pdf,doc,docx|max:5120'; // 5MB
                $rules['team_members.*.student_id_card'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
                $rules['social_media_follow_proof'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:5120'; // 5MB
            } else {
                // Standard team competition rules
                $rules['team_name'] = 'required|string|max:255';
                $rules['team_members'] = 'required|array|min:1|max:5';
                $rules['team_members.*.name'] = 'required|string|max:255';
                $rules['team_members.*.email'] = 'required|email|max:255';
                $rules['team_members.*.phone'] = 'required|string|max:20';
                $rules['team_members.*.foto'] = 'required|image|mimes:jpeg,png,jpg|max:2048';

                // Validasi jumlah anggota tim
                if ($competition->min_team_members) {
                    $rules['team_members'] = 'required|array|min:' . $competition->min_team_members . '|max:5';
                }
                if ($competition->max_team_members) {
                    $maxMembers = min($competition->max_team_members, 5);
                    $rules['team_members'] = 'required|array|min:1|max:' . $maxMembers;
                }
            }
        }
        
        // Merge validation messages
        $baseMessages = [
            'phone.required' => 'Nomor telepon harus diisi',
            'institution.required' => 'Institusi harus diisi',
            'gender.required' => 'Jenis kelamin harus dipilih',
            'gender.in' => 'Jenis kelamin tidak valid',
            'participant_category.required' => 'Kategori peserta harus dipilih',
            'participant_category.in' => 'Kategori peserta tidak valid',
        ];
        
        if ($competition->isEdcCompetition()) {
            // Use EDC-specific messages
            $edcMessages = Registration::getEdcValidationMessages();
            $messages = array_merge($baseMessages, $edcMessages, $dynamicValidation['messages']);
        } elseif ($competition->isDccCompetition()) {
            // Use DCC-specific messages
            $dccMessages = [
                'team_name.required' => 'Nama tim harus diisi',
                'team_members.required' => 'Anggota tim harus diisi',
                'team_members.size' => 'Tim harus terdiri dari 3 anggota',
                'team_members.*.name.required' => 'Nama anggota tim harus diisi',
                'team_members.*.school.required' => 'Asal sekolah harus diisi',
                'team_members.*.phone.required' => 'Nomor telepon anggota tim harus diisi',
                'team_members.*.photo_3x4.required' => 'Pas foto 3x4 harus diupload',
                'team_members.*.student_status_letter.required' => 'Surat keterangan siswa aktif harus diupload',
                'team_members.*.student_id_card.required' => 'Kartu pelajar harus diupload',
                'social_media_follow_proof.required' => 'Bukti follow social media harus diupload',
            ];
            $messages = array_merge($baseMessages, $dccMessages, $dynamicValidation['messages']);
        } else {
            // Use standard messages
            $standardMessages = [
                'team_name.required' => 'Nama tim harus diisi',
                'team_members.required' => 'Anggota tim harus diisi',
                'team_members.min' => 'Minimal ' . ($competition->min_team_members ?? 1) . ' anggota tim',
                'team_members.max' => 'Maksimal ' . ($competition->max_team_members ?? 10) . ' anggota tim',
                'team_members.*.name.required' => 'Nama anggota tim harus diisi',
            ];
            $messages = array_merge($baseMessages, $standardMessages, $dynamicValidation['messages']);
        }
        
        $validator = Validator::make($request->all(), $rules, $messages);

        // Validasi khusus untuk siswa SMA/SMK - harus menyertakan institusi
        $validator->after(function ($validator) use ($request, $competition) {
            if ($request->participant_category === 'high_school_student' && empty($request->institution)) {
                $validator->errors()->add('institution', 'Institusi wajib diisi untuk siswa SMA/SMK');
            }
            
            // EDC-specific validations
            if ($competition->isEdcCompetition() && $request->team_members) {
                // Validate team name for SARA compliance
                if ($request->team_name) {
                    $teamNameErrors = Registration::validateEdcTeamName($request->team_name);
                    foreach ($teamNameErrors as $error) {
                        $validator->errors()->add('team_name', $error);
                    }
                }
                
                // Validate same university requirement
                $universityErrors = Registration::validateSameUniversity($request->team_members);
                foreach ($universityErrors as $error) {
                    $validator->errors()->add('team_members', $error);
                }
                
                // Validate speaker positions
                $speakerErrors = Registration::validateSpeakerPositions($request->team_members);
                foreach ($speakerErrors as $error) {
                    $validator->errors()->add('team_members', $error);
                }
            }
        });

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid: ' . $validator->errors()->first()
                ]);
            }
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle logo instansi upload
            $logoPath = null;
            if ($request->hasFile('logo_instansi')) {
                $logoPath = $request->file('logo_instansi')->store('logos', 'public');
            }

            // Process dynamic form data
            $dynamicFormData = $this->dynamicFormService->processFormData($competition, $request);
            
            // Map participant status for all competitions
            $participantCategory = $this->mapParticipantStatus($user->participant_status);
            
            // Calculate price based on competition type and user's participant status
            if ($competition->isEdcCompetition()) {
                // Use EDC-specific pricing
                $priceData = Registration::getCurrentEdcPricing();
            } elseif ($competition->isSpcCompetition()) {
                // Use SPC-specific pricing
                $priceData = Registration::getCurrentSpcPricing();
            } elseif ($competition->isDccCompetition()) {
                // Use DCC-specific pricing (from PDF: Early Bird 50k, Phase 1 65k, Phase 2 75k)
                $priceData = $this->getDccPricing();
            } else {
                // Use standard pricing service
                $priceData = $this->pricingService->getPriceForCategory($participantCategory);
            }

            // Buat registrasi baru
            $registrationData = [
                'user_id' => $user->id,
                'competition_id' => $competition->id,
                'phone' => $request->phone ?: $user->phone,
                'institution' => $request->institution ?: $user->institution,
                'logo_instansi' => $logoPath,
                'gender' => $request->gender,
                'participant_category' => $participantCategory,
                'pricing_phase' => $priceData['phase'],
                'special_needs' => $request->special_needs,
                'amount' => $priceData['amount'],
                'original_price' => $priceData['amount'],
                'status' => 'pending',
                'registered_at' => now(),
                'dynamic_data' => $dynamicFormData,
            ];

            if ($competition->is_team_competition) {
                $registrationData['team_name'] = $request->team_name;

                // Process team members with file uploads
                $teamMembers = [];
                
                if ($competition->isDccCompetition()) {
                    // DCC-specific team member processing
                    foreach ($request->team_members as $index => $member) {
                        $photo3x4Path = null;
                        $studentStatusLetterPath = null;
                        $studentIdCardPath = null;
                        
                        if (isset($member['photo_3x4']) && $member['photo_3x4'] instanceof \Illuminate\Http\UploadedFile) {
                            $photo3x4Path = $member['photo_3x4']->store('dcc_photos', 'public');
                        }
                        
                        if (isset($member['student_status_letter']) && $member['student_status_letter'] instanceof \Illuminate\Http\UploadedFile) {
                            $studentStatusLetterPath = $member['student_status_letter']->store('dcc_documents', 'public');
                        }
                        
                        if (isset($member['student_id_card']) && $member['student_id_card'] instanceof \Illuminate\Http\UploadedFile) {
                            $studentIdCardPath = $member['student_id_card']->store('dcc_documents', 'public');
                        }

                        $teamMembers[] = [
                            'name' => $member['name'],
                            'school' => $member['school'],
                            'phone' => $member['phone'],
                            'photo_3x4' => $photo3x4Path,
                            'student_status_letter' => $studentStatusLetterPath,
                            'student_id_card' => $studentIdCardPath,
                        ];
                    }
                    
                    // Handle social media follow proof
                    if ($request->hasFile('social_media_follow_proof')) {
                        $socialMediaProofPath = $request->file('social_media_follow_proof')->store('dcc_documents', 'public');
                        $registrationData['social_media_follow_proof'] = $socialMediaProofPath;
                    }
                } else {
                    // Standard team member processing
                    foreach ($request->team_members as $index => $member) {
                        $fotoPath = null;
                        if (isset($member['foto']) && $member['foto'] instanceof \Illuminate\Http\UploadedFile) {
                            $fotoPath = $member['foto']->store('team_photos', 'public');
                        }

                        $teamMembers[] = [
                            'name' => $member['name'],
                            'email' => $member['email'] ?? null,
                            'phone' => $member['phone'],
                            'foto' => $fotoPath,
                        ];
                    }
                }

                $registrationData['team_members'] = $teamMembers;
            }

            $registration = Registration::create($registrationData);

            // Update competition registration count
            $competition->increment('registration_count');

            // Send registration confirmation email
            try {
                $this->notificationService->sendRegistrationConfirmation($registration);
            } catch (\Exception $emailError) {
                \Log::warning('Failed to send registration confirmation email: ' . $emailError->getMessage());
                // Don't fail the registration if email fails
            }
        } catch (\Exception $e) {
            \Log::error('Registration creation failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat membuat pendaftaran: ' . $e->getMessage()
                ]);
            }

            return back()->with('error', 'Terjadi kesalahan saat membuat pendaftaran: ' . $e->getMessage())->withInput();
        }

        // Check if this is an AJAX request
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil! Silakan lakukan pembayaran untuk mengkonfirmasi pendaftaran Anda.',
                'redirect_url' => route('peserta.registrations.show', $registration)
            ]);
        }

        // Redirect ke halaman detail registrasi
        return redirect()->route('peserta.registrations.show', $registration)
            ->with('success', 'Pendaftaran berhasil! Silakan lakukan pembayaran untuk mengkonfirmasi pendaftaran Anda.');
    }

    /**
     * Map participant status from user account to pricing category
     */
    private function mapParticipantStatus($participantStatus)
    {
        $mapping = [
            'Mahasiswa Unas' => 'unas_student',
            'Mahasiswa Eksternal' => 'external_student',
            'Siswa SMA/SMK' => 'high_school_student',
        ];

        return $mapping[$participantStatus] ?? 'external_student';
    }

    /**
     * Get DCC-specific pricing based on current date
     * Based on PDF requirements: Early Bird 50k, Phase 1 65k, Phase 2 75k
     */
    private function getDccPricing()
    {
        $now = now();
        
        // DCC pricing phases (same for both Infographics and Short Video)
        $earlyBirdStart = now()->setDate(2025, 8, 25);  // 25 August 2025
        $earlyBirdEnd = now()->setDate(2025, 8, 31);    // 31 August 2025
        $phase1Start = now()->setDate(2025, 9, 1);      // 1 September 2025
        $phase1End = now()->setDate(2025, 9, 13);       // 13 September 2025
        $phase2Start = now()->setDate(2025, 9, 14);     // 14 September 2025
        $phase2End = now()->setDate(2025, 9, 26);       // 26 September 2025
        
        if ($now->between($earlyBirdStart, $earlyBirdEnd)) {
            return [
                'amount' => 50000,
                'phase' => 'early_bird',
                'phase_name' => 'Early Bird'
            ];
        } elseif ($now->between($phase1Start, $phase1End)) {
            return [
                'amount' => 65000,
                'phase' => 'phase_1',
                'phase_name' => 'Phase 1'
            ];
        } elseif ($now->between($phase2Start, $phase2End)) {
            return [
                'amount' => 75000,
                'phase' => 'phase_2',
                'phase_name' => 'Phase 2'
            ];
        } else {
            // Default to phase 2 pricing if outside all phases
            return [
                'amount' => 75000,
                'phase' => 'phase_2',
                'phase_name' => 'Phase 2'
            ];
        }
    }
}
