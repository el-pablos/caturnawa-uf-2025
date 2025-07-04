<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Registration;
use App\Models\Competition;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Display participants list
     */
    public function index(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'Peserta');
        })->with(['registrations.competition']);

        // Filter by competition
        if ($request->filled('competition_id')) {
            $query->whereHas('registrations', function($q) use ($request) {
                $q->where('competition_id', $request->competition_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Filter by institution
        if ($request->filled('institution')) {
            $query->where('institution', 'like', '%' . $request->institution . '%');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $participants = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get competitions for filter
        $competitions = Competition::where('is_active', true)->get();

        // Get institutions for filter
        $institutions = User::whereHas('roles', function($q) {
            $q->where('name', 'Peserta');
        })->whereNotNull('institution')
          ->distinct()
          ->pluck('institution')
          ->sort();

        // Statistics
        $stats = [
            'total' => User::whereHas('roles', function($q) { $q->where('name', 'Peserta'); })->count(),
            'active' => User::whereHas('roles', function($q) { $q->where('name', 'Peserta'); })->where('is_active', true)->count(),
            'verified' => User::whereHas('roles', function($q) { $q->where('name', 'Peserta'); })->whereNotNull('email_verified_at')->count(),
            'registered' => Registration::where('status', 'confirmed')->distinct('user_id')->count(),
        ];

        return view('admin.participants.index', compact('participants', 'competitions', 'institutions', 'stats'));
    }

    /**
     * Show participant details
     */
    public function show(User $participant)
    {
        $participant->load([
            'registrations.competition',
            'registrations.payment',
            'registrations.submissions.scores.jury'
        ]);

        return view('admin.participants.show', compact('participant'));
    }

    /**
     * Export participants data
     */
    public function export(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'Peserta');
        })->with(['registrations.competition']);

        // Apply same filters as index
        if ($request->filled('competition_id')) {
            $query->whereHas('registrations', function($q) use ($request) {
                $q->where('competition_id', $request->competition_id);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $participants = $query->get();

        $filename = 'participants_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($participants) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Nama',
                'Email',
                'Institusi',
                'NIM/NIS',
                'Tanggal Lahir',
                'Jenis Kelamin',
                'Alamat',
                'Kota',
                'Provinsi',
                'Kode Pos',
                'Kontak Darurat',
                'No. HP Darurat',
                'Status Akun',
                'Email Verified',
                'Tanggal Daftar',
                'Kompetisi Diikuti',
                'Status Registrasi'
            ]);

            foreach ($participants as $participant) {
                $competitions = $participant->registrations->pluck('competition.name')->implode(', ');
                $registrationStatus = $participant->registrations->pluck('status')->unique()->implode(', ');

                fputcsv($file, [
                    $participant->id,
                    $participant->name,
                    $participant->email,
                    $participant->institution,
                    $participant->student_id,
                    $participant->birth_date ? $participant->birth_date->format('Y-m-d') : '',
                    $participant->gender,
                    $participant->address,
                    $participant->city,
                    $participant->province,
                    $participant->postal_code,
                    $participant->emergency_contact_name,
                    $participant->emergency_contact_phone,
                    $participant->is_active ? 'Aktif' : 'Tidak Aktif',
                    $participant->email_verified_at ? 'Verified' : 'Unverified',
                    $participant->created_at->format('Y-m-d H:i:s'),
                    $competitions,
                    $registrationStatus
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get participant statistics for dashboard
     */
    public function getStatistics()
    {
        $stats = [
            'total_participants' => User::whereHas('roles', function($q) {
                $q->where('name', 'Peserta');
            })->count(),
            
            'active_participants' => User::whereHas('roles', function($q) {
                $q->where('name', 'Peserta');
            })->where('is_active', true)->count(),
            
            'verified_participants' => User::whereHas('roles', function($q) {
                $q->where('name', 'Peserta');
            })->whereNotNull('email_verified_at')->count(),
            
            'registered_participants' => Registration::where('status', 'confirmed')
                ->distinct('user_id')->count(),
            
            'participants_by_competition' => Registration::with('competition')
                ->where('status', 'confirmed')
                ->get()
                ->groupBy('competition.name')
                ->map(function($registrations) {
                    return $registrations->count();
                }),
            
            'participants_by_institution' => User::whereHas('roles', function($q) {
                $q->where('name', 'Peserta');
            })->whereNotNull('institution')
              ->get()
              ->groupBy('institution')
              ->map(function($users) {
                  return $users->count();
              })
              ->sortDesc()
              ->take(10),
            
            'recent_registrations' => User::whereHas('roles', function($q) {
                $q->where('name', 'Peserta');
            })->where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
