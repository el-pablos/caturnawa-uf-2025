@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna Baru')

@section('sidebar-menu')
    <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a class="nav-link" href="{{ route('admin.competitions.index') }}">
        <i class="bi bi-trophy me-2"></i>Kompetisi
    </a>
    <a class="nav-link" href="{{ route('admin.registrations.index') }}">
        <i class="bi bi-person-check me-2"></i>Registrasi
    </a>
    <a class="nav-link" href="{{ route('admin.payments.index') }}">
        <i class="bi bi-credit-card me-2"></i>Pembayaran
    </a>
    <a class="nav-link active" href="{{ route('admin.users.index') }}">
        <i class="bi bi-people me-2"></i>Pengguna
    </a>
    <a class="nav-link" href="{{ route('admin.reports.index') }}">
        <i class="bi bi-graph-up me-2"></i>Laporan
    </a>
    <a class="nav-link" href="{{ route('admin.settings.index') }}">
        <i class="bi bi-gear me-2"></i>Pengaturan
    </a>
@endsection

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-plus me-2"></i>Tambah Pengguna Baru
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="bi bi-eye" id="password-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktifkan pengguna
                                </label>
                            </div>
                            <small class="text-muted">Pengguna yang tidak aktif tidak dapat login ke sistem</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Role Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Role
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Super Admin</h6>
                        <ul class="small text-muted">
                            <li>Akses penuh ke semua fitur</li>
                            <li>Dapat mengelola pengguna</li>
                            <li>Dapat mengubah pengaturan sistem</li>
                        </ul>
                        
                        <h6 class="text-info">Admin</h6>
                        <ul class="small text-muted">
                            <li>Mengelola kompetisi</li>
                            <li>Mengelola registrasi dan pembayaran</li>
                            <li>Melihat laporan</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Juri</h6>
                        <ul class="small text-muted">
                            <li>Menilai kompetisi yang ditugaskan</li>
                            <li>Melihat submission peserta</li>
                            <li>Memberikan skor dan komentar</li>
                        </ul>
                        
                        <h6 class="text-warning">Peserta</h6>
                        <ul class="small text-muted">
                            <li>Mendaftar kompetisi</li>
                            <li>Upload submission</li>
                            <li>Melihat hasil penilaian</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    function validatePasswords() {
        if (password.value !== passwordConfirmation.value) {
            passwordConfirmation.setCustomValidity('Password tidak cocok');
        } else {
            passwordConfirmation.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePasswords);
    passwordConfirmation.addEventListener('input', validatePasswords);
    
    form.addEventListener('submit', function(e) {
        validatePasswords();
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endpush
