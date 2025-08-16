<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

/**
 * Controller untuk mengelola pengguna (Super Admin only)
 * 
 * Mengelola CRUD pengguna dan assignment role
 */
class UserController extends Controller
{
    /**
     * Tampilkan daftar pengguna
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::with('roles')->orderBy('created_at', 'desc');

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);
        $roles = Role::all();

        // Statistik
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'super_admin' => $this->safeRoleCount('superadmin'),
            'admin' => $this->safeRoleCount('admin'),
            'juri' => $this->safeRoleCount('juri'),
            'peserta' => $this->safeRoleCount('peserta'),
        ];

        return view('admin.users.index', compact('users', 'roles', 'stats'));
    }

    /**
     * Safely count users by role, return 0 if role doesn't exist
     *
     * @param string $roleName
     * @return int
     */
    private function safeRoleCount($roleName)
    {
        try {
            return User::role($roleName)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Tampilkan form create pengguna
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Simpan pengguna baru
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_active' => $request->boolean('is_active', true),
                'email_verified_at' => now(), // Auto verify for admin created users
            ]);

            $user->assignRole($request->role);

            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna berhasil dibuat.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal membuat pengguna: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan detail pengguna
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function show(User $user, Request $request)
    {
        $user->load([
            'roles',
            'registrations.competition',
            'registrations.payment',
            'submissions.competition',
            'payments'
        ]);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'is_active' => $user->is_active,
                    'avatar_url' => $user->avatar_url,
                    'created_at' => $user->created_at->toISOString(),
                    'updated_at' => $user->updated_at->toISOString(),
                    'roles' => $user->roles->map(function($role) {
                        return [
                            'id' => $role->id,
                            'name' => $role->name,
                        ];
                    }),
                    'registrations_count' => $user->registrations->count(),
                    'submissions_count' => $user->submissions->count(),
                    'payments_count' => $user->payments->count(),
                ]
            ]);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Tampilkan form edit pengguna
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update pengguna
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_active' => $request->boolean('is_active', true),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            $user->syncRoles([$request->role]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus pengguna
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Prevent deleting current user
        if ($user->id === auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus akun sendiri.'
                ]);
            }
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Prevent deleting super admin if only one exists
        if ($user->hasRole('superadmin') && User::role('superadmin')->count() <= 1) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus Super Admin terakhir.'
                ]);
            }
            return back()->with('error', 'Tidak dapat menghapus Super Admin terakhir.');
        }

        try {
            $user->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna berhasil dihapus.'
                ]);
            }

            return back()->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus pengguna: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status aktif pengguna
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(User $user)
    {
        // Log the request for debugging
        \Log::info('User toggle status request', [
            'user_id' => $user->id,
            'current_status' => $user->is_active,
            'auth_user' => auth()->id(),
            'expects_json' => request()->expectsJson(),
            'request_headers' => request()->headers->all()
        ]);

        // Prevent deactivating current user
        if ($user->id === auth()->id()) {
            \Log::warning('Attempt to deactivate own account', ['user_id' => $user->id]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menonaktifkan akun sendiri.'
                ], 400);
            }
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        // Prevent deactivating super admin if only one exists and they're active
        if ($user->hasRole('superadmin') && $user->is_active && User::role('superadmin')->where('is_active', true)->count() <= 1) {
            \Log::warning('Attempt to deactivate last active super admin', ['user_id' => $user->id]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menonaktifkan Super Admin terakhir yang aktif.'
                ], 400);
            }
            return back()->with('error', 'Tidak dapat menonaktifkan Super Admin terakhir yang aktif.');
        }

        try {
            $originalStatus = $user->is_active;
            $user->update(['is_active' => !$user->is_active]);
            $newStatus = $user->fresh()->is_active;

            $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

            \Log::info('User status toggled successfully', [
                'user_id' => $user->id,
                'original_status' => $originalStatus,
                'new_status' => $newStatus,
                'message' => "Pengguna berhasil {$status}"
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Pengguna berhasil {$status}.",
                    'user' => $user->fresh(),
                    'debug' => [
                        'original_status' => $originalStatus,
                        'new_status' => $newStatus,
                        'timestamp' => now()->toISOString()
                    ]
                ]);
            }

            return back()->with('success', "Pengguna berhasil {$status}.");
        } catch (\Exception $e) {
            \Log::error('Failed to toggle user status', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah status pengguna: ' . $e->getMessage(),
                    'debug' => [
                        'error_type' => get_class($e),
                        'error_file' => $e->getFile(),
                        'error_line' => $e->getLine()
                    ]
                ], 500);
            }
            return back()->with('error', 'Gagal mengubah status pengguna: ' . $e->getMessage());
        }
    }
}
