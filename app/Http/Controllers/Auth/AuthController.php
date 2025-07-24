<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

/**
 * Controller untuk menangani autentikasi pengguna
 * 
 * Mengelola login, logout, register, dan redirect
 * berdasarkan role pengguna
 */
class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     * 
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin();
        }
        
        return view('auth.login');
    }

    /**
     * Proses login pengguna
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Cek apakah user ada dan aktif
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()
                ->withErrors(['email' => 'Email tidak terdaftar'])
                ->withInput($request->only('email'));
        }

        if (!$user->is_active) {
            return back()
                ->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.'])
                ->withInput($request->only('email'));
        }

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Update last login
            Auth::user()->updateLastLogin();
            
            return $this->redirectAfterLogin();
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->withInput($request->only('email'));
    }

    /**
     * Tampilkan halaman register untuk peserta
     * 
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin();
        }
        
        return view('auth.register');
    }

    /**
     * Proses registrasi peserta baru
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).{8,}$/'
            ],
            'phone' => 'required|string|max:20',
            'participant_status' => 'required|in:Mahasiswa Unas,Mahasiswa Eksternal,Siswa SMA/SMK',
            'institution' => 'required|string|max:255',
            'student_id' => 'required|string|max:50',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus (@$!%*?&)',
            'phone.required' => 'Nomor telepon harus diisi',
            'participant_status.required' => 'Status peserta harus dipilih',
            'participant_status.in' => 'Status peserta tidak valid',
            'institution.required' => 'Asal instansi harus diisi',
            'student_id.required' => 'Student ID/NIM harus diisi',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            // Buat user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'participant_status' => $request->participant_status,
                'institution' => $request->institution,
                'student_id' => $request->student_id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Assign role peserta (ensure role exists)
            if (!\Spatie\Permission\Models\Role::where('name', 'peserta')->exists()) {
                \Spatie\Permission\Models\Role::create(['name' => 'peserta']);
            }
            $user->assignRole('peserta');

            // Login otomatis setelah register
            Auth::login($user);

            return redirect()->route('peserta.dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang di UNAS Fest 2025.');

        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());

            return back()
                ->withErrors(['email' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Logout pengguna
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Redirect pengguna setelah login berdasarkan role
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAfterLogin()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.admin.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.admin.dashboard');
        } elseif ($user->isJuri()) {
            return redirect()->route('juri.juri.dashboard');
        } elseif ($user->isPeserta()) {
            return redirect()->route('peserta.dashboard');
        }

        return redirect()->route('login')
            ->with('error', 'Role tidak dikenali. Silakan hubungi administrator.');
    }

    /**
     * Tampilkan halaman forgot password
     * 
     * @return \Illuminate\View\View
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // TODO: Implementasi pengiriman email reset password
        // Untuk sekarang hanya tampilkan pesan sukses

        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    /**
     * Tampilkan halaman reset password
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Proses reset password
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).{8,}$/'
            ],
        ], [
            'token.required' => 'Token reset password diperlukan',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // TODO: Implementasi reset password dengan token
        // Untuk sekarang hanya redirect ke login dengan pesan sukses

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    /**
     * Tampilkan profil pengguna
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();

        return view('auth.profile', compact('user'));
    }

    /**
     * Update profil pengguna
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'institution' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'phone.required' => 'Nomor telepon harus diisi',
            'institution.max' => 'Nama institusi maksimal 255 karakter',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus JPEG, PNG, atau JPG',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'institution' => $request->institution,
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('public/avatars', $filename);
            $data['avatar'] = $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password pengguna
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok',
            'new_password.regex' => 'Password baru harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus (@$!%*?&)',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'updatePassword');
        }

        $user = Auth::user();

        // Cek password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini salah'], 'updatePassword');
        }

        // Update password
        $user->update(['password' => $request->new_password]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
