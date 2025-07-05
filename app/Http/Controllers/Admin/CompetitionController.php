<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

/**
 * Controller untuk mengelola kompetisi
 * 
 * Menangani CRUD kompetisi termasuk kategori, harga,
 * dan pengaturan periode pendaftaran
 */
class CompetitionController extends Controller
{
    /**
     * Tampilkan daftar kompetisi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $competitions = Competition::with('registrations')->select('competitions.*');

            return DataTables::of($competitions)
                ->addIndexColumn()
                ->addColumn('participants_count', function($competition) {
                    return $competition->getRegisteredParticipantsCount();
                })
                ->addColumn('revenue', function($competition) {
                    return 'Rp ' . number_format($competition->getTotalRevenue(), 0, ',', '.');
                })
                ->addColumn('status', function($competition) {
                    $badgeClass = $competition->is_active ? 'success' : 'secondary';
                    $statusText = $competition->is_active ? 'Aktif' : 'Tidak Aktif';
                    return "<span class='badge bg-{$badgeClass}'>{$statusText}</span>";
                })
                ->addColumn('registration_status', function($competition) {
                    $status = $competition->registration_status;
                    $class = [
                        'upcoming' => 'info',
                        'open' => 'success',
                        'closed' => 'danger'
                    ][$status] ?? 'secondary';

                    $text = [
                        'upcoming' => 'Belum Dibuka',
                        'open' => 'Terbuka',
                        'closed' => 'Ditutup'
                    ][$status] ?? 'Tidak Diketahui';

                    return "<span class='badge bg-{$class}'>{$text}</span>";
                })
                ->addColumn('action', function($competition) {
                    $viewBtn = '<a href="' . route('admin.competitions.show', $competition->id) .
                              '" class="btn btn-sm btn-info me-1"><i class="bi bi-eye-fill"></i></a>';
                    $editBtn = '<a href="' . route('admin.competitions.edit', $competition->id) .
                              '" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil-square"></i></a>';
                    $deleteBtn = '<button type="button" class="btn btn-sm btn-danger"
                                 onclick="deleteCompetition(' . $competition->id . ')">
                                 <i class="bi bi-trash-fill"></i></button>';

                    return $viewBtn . $editBtn . $deleteBtn;
                })
                ->rawColumns(['status', 'registration_status', 'action'])
                ->make(true);
        }

        // Get competitions for regular view
        $competitions = Competition::withCount('registrations')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.competitions.index', compact('competitions'));
    }

    /**
     * Tampilkan form tambah kompetisi
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Competition::CATEGORIES;
        return view('admin.competitions.create', compact('categories'));
    }

    /**
     * Simpan kompetisi baru
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:' . implode(',', array_keys(Competition::CATEGORIES)),
            'theme' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'early_bird_price' => 'nullable|numeric|min:0|lt:price',
            'early_bird_deadline' => 'nullable|date|after:now',
            'registration_start' => 'required|date|after_or_equal:now',
            'registration_end' => 'required|date|after:registration_start',
            'competition_start' => 'required|date|after_or_equal:registration_end',
            'competition_end' => 'required|date|after:competition_start',
            'submission_deadline' => 'nullable|date|after:competition_start|before_or_equal:competition_end',
            'result_announcement' => 'nullable|date|after:competition_end',
            'max_participants' => 'nullable|integer|min:1',
            'min_team_members' => 'nullable|integer|min:1',
            'max_team_members' => 'nullable|integer|min:1|gte:min_team_members',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_team_competition' => 'boolean',
            'allow_individual' => 'boolean',
        ], [
            'name.required' => 'Nama kompetisi harus diisi',
            'description.required' => 'Deskripsi harus diisi',
            'category.required' => 'Kategori harus dipilih',
            'price.required' => 'Harga pendaftaran harus diisi',
            'early_bird_price.lt' => 'Harga early bird harus lebih kecil dari harga normal',
            'registration_start.required' => 'Tanggal mulai pendaftaran harus diisi',
            'registration_end.required' => 'Tanggal akhir pendaftaran harus diisi',
            'registration_end.after' => 'Tanggal akhir pendaftaran harus setelah tanggal mulai',
            'competition_start.required' => 'Tanggal mulai kompetisi harus diisi',
            'competition_end.required' => 'Tanggal akhir kompetisi harus diisi',
            'max_team_members.gte' => 'Maksimal anggota tim harus lebih besar atau sama dengan minimal anggota tim',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['image', 'requirements', 'prizes', 'rules']);
        
        // Generate slug
        $data['slug'] = Str::slug($request->name);
        
        // Ensure unique slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Competition::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/competitions', $filename);
            $data['image'] = $filename;
        }

        // Handle array fields
        $data['requirements'] = $request->requirements ? explode("\n", $request->requirements) : [];
        $data['prizes'] = $request->prizes ? explode("\n", $request->prizes) : [];
        $data['rules'] = $request->rules ? explode("\n", $request->rules) : [];
        
        $data['is_active'] = $request->boolean('is_active', true);

        Competition::create($data);

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Kompetisi berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return \Illuminate\View\View
     */
    public function show(Competition $competition)
    {
        $competition->load(['registrations.user', 'registrations.payment']);
        
        $stats = [
            'total_registrations' => $competition->registrations->count(),
            'confirmed_registrations' => $competition->registrations->where('status', 'confirmed')->count(),
            'pending_registrations' => $competition->registrations->where('status', 'pending')->count(),
            'total_revenue' => $competition->getTotalRevenue(),
            'average_revenue_per_participant' => $competition->getRegisteredParticipantsCount() > 0 
                ? $competition->getTotalRevenue() / $competition->getRegisteredParticipantsCount() 
                : 0,
        ];

        return view('admin.competitions.show', compact('competition', 'stats'));
    }

    /**
     * Tampilkan form edit kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return \Illuminate\View\View
     */
    public function edit(Competition $competition)
    {
        $categories = Competition::CATEGORIES;
        
        // Convert array fields to string for form
        $competition->requirements = is_array($competition->requirements) 
            ? implode("\n", $competition->requirements) : '';
        $competition->prizes = is_array($competition->prizes) 
            ? implode("\n", $competition->prizes) : '';
        $competition->rules = is_array($competition->rules) 
            ? implode("\n", $competition->rules) : '';

        return view('admin.competitions.edit', compact('competition', 'categories'));
    }

    /**
     * Update kompetisi
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Competition $competition
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Competition $competition)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:' . implode(',', array_keys(Competition::CATEGORIES)),
            'theme' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'early_bird_price' => 'nullable|numeric|min:0|lt:price',
            'early_bird_deadline' => 'nullable|date',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'competition_start' => 'required|date|after_or_equal:registration_end',
            'competition_end' => 'required|date|after:competition_start',
            'submission_deadline' => 'nullable|date|after:competition_start|before_or_equal:competition_end',
            'result_announcement' => 'nullable|date|after:competition_end',
            'max_participants' => 'nullable|integer|min:1',
            'min_team_members' => 'nullable|integer|min:1',
            'max_team_members' => 'nullable|integer|min:1|gte:min_team_members',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_team_competition' => 'boolean',
            'allow_individual' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['image', 'requirements', 'prizes', 'rules']);
        
        // Update slug jika nama berubah
        if ($request->name !== $competition->name) {
            $data['slug'] = Str::slug($request->name);
            
            // Ensure unique slug
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Competition::where('slug', $data['slug'])->where('id', '!=', $competition->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/competitions', $filename);
            $data['image'] = $filename;
        }

        // Handle array fields
        $data['requirements'] = $request->requirements ? explode("\n", $request->requirements) : [];
        $data['prizes'] = $request->prizes ? explode("\n", $request->prizes) : [];
        $data['rules'] = $request->rules ? explode("\n", $request->rules) : [];
        
        $data['is_active'] = $request->boolean('is_active');

        $competition->update($data);

        return redirect()->route('admin.competitions.index')
            ->with('success', 'Kompetisi berhasil diperbarui.');
    }

    /**
     * Hapus kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Competition $competition)
    {
        try {
            // Cek apakah ada pendaftaran
            if ($competition->registrations()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kompetisi tidak dapat dihapus karena sudah ada peserta yang mendaftar.'
                ]);
            }

            $competition->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kompetisi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus kompetisi.'
            ]);
        }
    }

    /**
     * Toggle status aktif kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Competition $competition)
    {
        try {
            $competition->update(['is_active' => !$competition->is_active]);
            
            $status = $competition->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return response()->json([
                'success' => true,
                'message' => "Kompetisi berhasil {$status}.",
                'status' => $competition->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status kompetisi.'
            ]);
        }
    }

    /**
     * Export daftar peserta kompetisi
     * 
     * @param \App\Models\Competition $competition
     * @return \Illuminate\Http\Response
     */
    public function exportParticipants(Competition $competition)
    {
        $registrations = $competition->registrations()
            ->with(['user', 'payment'])
            ->where('status', 'confirmed')
            ->get();

        $csvData = [];
        $csvData[] = [
            'No. Pendaftaran',
            'Nama Peserta',
            'Email',
            'Telepon',
            'Institusi',
            'Tim',
            'Status Pembayaran',
            'Tanggal Daftar'
        ];

        foreach ($registrations as $registration) {
            $csvData[] = [
                $registration->registration_number,
                $registration->user->name,
                $registration->user->email,
                $registration->phone,
                $registration->institution,
                $registration->team_name ?: '-',
                $registration->isPaid() ? 'Lunas' : 'Belum Lunas',
                $registration->created_at->format('d/m/Y H:i')
            ];
        }

        $filename = 'peserta_' . Str::slug($competition->name) . '_' . date('Ymd_His') . '.csv';
        
        $handle = fopen('php://output', 'w');
        
        ob_start();
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
        $csvContent = ob_get_clean();

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
