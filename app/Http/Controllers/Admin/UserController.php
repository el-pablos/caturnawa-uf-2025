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
            'super_admin' => User::role('Super Admin')->count(),
            'admin' => User::role('Admin')->count(),
            'juri' => User::role('Juri')->count(),
            'peserta' => User::role('Peserta')->count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'stats'));
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
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load(['roles', 'registrations.competition', 'registrations.payment']);
        
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Prevent deleting current user
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Prevent deleting super admin if only one exists
        if ($user->hasRole('Super Admin') && User::role('Super Admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus Super Admin terakhir.');
        }

        try {
            $user->delete();
            return back()->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status aktif pengguna
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating current user
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        try {
            $user->update(['is_active' => !$user->is_active]);
            
            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('success', "Pengguna berhasil {$status}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status pengguna: ' . $e->getMessage());
        }
    }
}
