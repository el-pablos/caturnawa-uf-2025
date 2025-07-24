@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">
                        <i class="bi bi-shield-lock me-2"></i>Reset Password
                    </h3>
                </div>
                <div class="card-body">
                    <div class="small mb-3 text-muted">
                        Masukkan email dan password baru Anda.
                    </div>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <div class="form-floating mb-3">
                            <input class="form-control @error('email') is-invalid @enderror" 
                                   id="inputEmail" 
                                   type="email" 
                                   name="email" 
                                   placeholder="name@example.com" 
                                   value="{{ old('email') }}" 
                                   required />
                            <label for="inputEmail">
                                <i class="bi bi-envelope me-2"></i>Alamat Email
                            </label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input class="form-control @error('password') is-invalid @enderror" 
                                   id="inputPassword" 
                                   type="password" 
                                   name="password" 
                                   placeholder="Password" 
                                   required />
                            <label for="inputPassword">
                                <i class="bi bi-lock me-2"></i>Password Baru
                            </label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="inputPasswordConfirm" 
                                   type="password" 
                                   name="password_confirmation" 
                                   placeholder="Konfirmasi Password" 
                                   required />
                            <label for="inputPasswordConfirm">
                                <i class="bi bi-lock-fill me-2"></i>Konfirmasi Password Baru
                            </label>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <a class="small" href="{{ route('login') }}">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
                            </a>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-check-circle me-2"></i>Reset Password
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small">
                        <a href="{{ route('register') }}">Belum punya akun? Daftar!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    opacity: .65;
    transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.alert {
    border: none;
    border-radius: 10px;
}

.invalid-feedback {
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const passwordInput = document.getElementById('inputPassword');
    const confirmInput = document.getElementById('inputPasswordConfirm');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);
        // You can add visual feedback here
    });
    
    confirmInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirm = this.value;
        
        if (password !== confirm && confirm.length > 0) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        return strength;
    }
});
</script>
@endpush
