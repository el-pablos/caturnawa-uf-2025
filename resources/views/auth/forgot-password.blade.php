@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">
                        <i class="bi bi-key me-2"></i>Lupa Password
                    </h3>
                </div>
                <div class="card-body">
                    <div class="small mb-3 text-muted">
                        Masukkan alamat email Anda dan kami akan mengirimkan link untuk reset password.
                    </div>
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        
                        <div class="form-floating mb-3">
                            <input class="form-control @error('email') is-invalid @enderror" 
                                   id="inputEmail" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="name@example.com" 
                                   required 
                                   autocomplete="email" 
                                   autofocus />
                            <label for="inputEmail">Alamat Email</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <a class="small" href="{{ route('login') }}">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
                            </a>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-send me-2"></i>Kirim Link Reset
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
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.alert-success {
    background: linear-gradient(45deg, #56ab2f 0%, #a8e6cf 100%);
    border: none;
    color: white;
}
</style>
@endpush
