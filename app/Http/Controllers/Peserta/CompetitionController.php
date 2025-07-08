<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Services\RegistrationValidationService;
use App\Services\PricingService;
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

    public function __construct(RegistrationValidationService $registrationValidationService, PricingService $pricingService)
    {
        $this->registrationValidationService = $registrationValidationService;
        $this->pricingService = $pricingService;
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

        // Statistik kompetisi
        $stats = [
            'participants_count' => $competition->getRegisteredParticipantsCount(),
            'slots_remaining' => $competition->max_participants
                ? $competition->max_participants - $competition->getRegisteredParticipantsCount()
                : null,
            'days_left' => now()->diffInDays($competition->registration_end, false),
            'is_early_bird' => $this->pricingService->isEarlyBirdPeriod(),
        ];

        return view('peserta.competitions.show', compact('competition', 'existingRegistration', 'stats', 'userRegistrations', 'canRegister', 'pricingSummary', 'participantCategories'));
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

        // Validasi form
        $rules = [
            'phone' => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'education_level' => 'required|string|max:50',
            'participant_category' => 'required|in:unas_student,external_student,high_school_student',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'special_needs' => 'nullable|string|max:500',
        ];
        
        // Validasi logo instansi
        $rules['logo_instansi'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';

        // Validasi untuk kompetisi tim
        if ($competition->is_team_competition) {
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
        
        $validator = Validator::make($request->all(), $rules, [
            'phone.required' => 'Nomor telepon harus diisi',
            'institution.required' => 'Institusi harus diisi',
            'gender.required' => 'Jenis kelamin harus dipilih',
            'gender.in' => 'Jenis kelamin tidak valid',
            'education_level.required' => 'Tingkat pendidikan harus dipilih',
            'team_name.required' => 'Nama tim harus diisi',
            'team_members.required' => 'Anggota tim harus diisi',
            'team_members.min' => 'Minimal ' . ($competition->min_team_members ?? 1) . ' anggota tim',
            'team_members.max' => 'Maksimal ' . ($competition->max_team_members ?? 10) . ' anggota tim',
            'team_members.*.name.required' => 'Nama anggota tim harus diisi',
        ]);

        // Validasi khusus untuk SMA/SMK - harus menyertakan institusi
        $validator->after(function ($validator) use ($request) {
            if (in_array($request->education_level, ['SMA', 'SMK']) && empty($request->institution)) {
                $validator->errors()->add('institution', 'Institusi wajib diisi untuk peserta SMA/SMK');
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

            // Calculate price based on participant category
            $participantCategory = $request->participant_category;
            $priceData = $this->pricingService->getPriceForCategory($participantCategory);

            // Buat registrasi baru
            $registrationData = [
                'user_id' => $user->id,
                'competition_id' => $competition->id,
                'phone' => $request->phone ?: $user->phone,
                'institution' => $request->institution ?: $user->institution,
                'logo_instansi' => $logoPath,
                'gender' => $request->gender,
                'education_level' => $request->education_level,
                'participant_category' => $participantCategory,
                'pricing_phase' => $priceData['phase'],
                'emergency_contact' => $request->emergency_contact,
                'emergency_phone' => $request->emergency_phone,
                'special_needs' => $request->special_needs,
                'amount' => $priceData['amount'],
                'original_price' => $priceData['amount'],
                'status' => 'pending',
                'registered_at' => now(),
            ];

            if ($competition->is_team_competition) {
                $registrationData['team_name'] = $request->team_name;

                // Process team members with file uploads
                $teamMembers = [];
                foreach ($request->team_members as $index => $member) {
                    $fotoPath = null;
                    if (isset($member['foto']) && $member['foto'] instanceof \Illuminate\Http\UploadedFile) {
                        $fotoPath = $member['foto']->store('team_photos', 'public');
                    }

                    $teamMembers[] = [
                        'name' => $member['name'],
                        'email' => $member['email'],
                        'phone' => $member['phone'],
                        'foto' => $fotoPath,
                    ];
                }

                $registrationData['team_members'] = $teamMembers;
            }

            $registration = Registration::create($registrationData);
        } catch (\Exception $e) {
            \Log::error('Registration creation failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat membuat pendaftaran: ' . $e->getMessage()
                ]);
            }

            return back()->with('error', 'Terjadi kesalahan saat membuat pendaftaran.');
        }
        
        // Check if this is an AJAX request
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil! Silakan lakukan pembayaran untuk mengkonfirmasi pendaftaran Anda.',
                'redirect_url' => route('payment.checkout', $registration)
            ]);
        }

        // Redirect ke halaman pembayaran
        return redirect()->route('payment.checkout', $registration)
            ->with('success', 'Pendaftaran berhasil! Silakan lakukan pembayaran untuk mengkonfirmasi pendaftaran Anda.');
    }
}
