<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
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
        
        // Statistik kompetisi
        $stats = [
            'participants_count' => $competition->getRegisteredParticipantsCount(),
            'slots_remaining' => $competition->max_participants 
                ? $competition->max_participants - $competition->getRegisteredParticipantsCount()
                : null,
            'days_left' => now()->diffInDays($competition->registration_end, false),
            'is_early_bird' => $competition->isEarlyBird(),
        ];
        
        return view('peserta.competitions.show', compact('competition', 'existingRegistration', 'stats'));
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
                'emergency_contact' => $request->emergency_contact ?: $user->emergency_contact_name,
                'emergency_phone' => $request->emergency_phone ?: $user->emergency_contact_phone,
                'special_needs' => $request->special_needs ?: null,
            ]);
        }

        // Validasi form
        $rules = [
            'phone' => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'special_needs' => 'nullable|string|max:500',
        ];
        
        // Validasi untuk kompetisi tim
        if ($competition->is_team_competition) {
            $rules['team_name'] = 'required|string|max:255';
            $rules['team_members'] = 'required|array';
            $rules['team_members.*.name'] = 'required|string|max:255';
            $rules['team_members.*.student_id'] = 'nullable|string|max:50';
            $rules['team_members.*.role'] = 'nullable|string|max:100';
            
            // Validasi jumlah anggota tim
            if ($competition->min_team_members) {
                $rules['team_members'] = 'required|array|min:' . $competition->min_team_members;
            }
            if ($competition->max_team_members) {
                $rules['team_members'] = 'required|array|max:' . $competition->max_team_members;
            }
        }
        
        $validator = Validator::make($request->all(), $rules, [
            'phone.required' => 'Nomor telepon harus diisi',
            'institution.required' => 'Institusi harus diisi',
            'team_name.required' => 'Nama tim harus diisi',
            'team_members.required' => 'Anggota tim harus diisi',
            'team_members.min' => 'Minimal ' . ($competition->min_team_members ?? 1) . ' anggota tim',
            'team_members.max' => 'Maksimal ' . ($competition->max_team_members ?? 10) . ' anggota tim',
            'team_members.*.name.required' => 'Nama anggota tim harus diisi',
        ]);

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
            // Buat registrasi baru
            $registrationData = [
                'user_id' => $user->id,
                'competition_id' => $competition->id,
                'phone' => $request->phone ?: $user->phone,
                'institution' => $request->institution ?: $user->institution,
                'emergency_contact' => $request->emergency_contact,
                'emergency_phone' => $request->emergency_phone,
                'special_needs' => $request->special_needs,
                'amount' => $competition->getCurrentPriceAttribute(),
                'status' => 'pending',
                'registered_at' => now(),
            ];

            if ($competition->is_team_competition) {
                $registrationData['team_name'] = $request->team_name;
                $registrationData['team_members'] = $request->team_members;
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
