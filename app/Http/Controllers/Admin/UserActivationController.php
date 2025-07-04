<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActivationController extends Controller
{
    /**
     * Display pending user activations
     */
    public function index(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'Peserta');
        });

        // Filter by activation status
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_active', false);
            } elseif ($request->status === 'active') {
                $query->where('is_active', true);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => User::whereHas('roles', function($q) { $q->where('name', 'Peserta'); })->count(),
            'pending' => User::whereHas('roles', function($q) { $q->where('name', 'Peserta'); })->where('is_active', false)->count(),
            'active' => User::whereHas('roles', function($q) { $q->where('name', 'Peserta'); })->where('is_active', true)->count(),
        ];

        return view('admin.user-activation.index', compact('users', 'stats'));
    }

    /**
     * Activate user account
     */
    public function activate(User $user)
    {
        try {
            $user->update([
                'is_active' => true,
                'activated_at' => now(),
                'activated_by' => Auth::id(),
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun berhasil diaktivasi.'
                ]);
            }

            return back()->with('success', 'Akun ' . $user->name . ' berhasil diaktivasi.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengaktivasi akun: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Gagal mengaktivasi akun: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate user account
     */
    public function deactivate(User $user)
    {
        try {
            $user->update([
                'is_active' => false,
                'activated_at' => null,
                'activated_by' => null,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Akun berhasil dinonaktifkan.'
                ]);
            }

            return back()->with('success', 'Akun ' . $user->name . ' berhasil dinonaktifkan.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menonaktifkan akun: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Gagal menonaktifkan akun: ' . $e->getMessage());
        }
    }

    /**
     * Bulk activate users
     */
    public function bulkActivate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $users = User::whereIn('id', $request->user_ids)->get();
            
            foreach ($users as $user) {
                $user->update([
                    'is_active' => true,
                    'activated_at' => now(),
                    'activated_by' => Auth::id(),
                ]);
            }

            return back()->with('success', 'Berhasil mengaktivasi ' . $users->count() . ' akun.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan aktivasi massal: ' . $e->getMessage());
        }
    }
}
