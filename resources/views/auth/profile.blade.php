@php
    $user = auth()->user();
    $layout = 'layouts.app';

    if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
        $layout = 'layouts.admin';
    } elseif ($user->hasRole('juri')) {
        $layout = 'layouts.juri';
    } elseif ($user->hasRole('peserta')) {
        $layout = 'layouts.peserta';
    }
@endphp

@extends($layout)

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            @php $user = auth()->user(); @endphp

            <!-- Profile Header -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-circle me-2"></i>Profil Pengguna
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->avatar_url }}"
                                     class="rounded-circle border border-3 shadow"
                                     width="150" height="150"
                                     alt="Avatar" id="avatar-preview"
                                     style="object-fit: cover;">
                                <label for="avatar" class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle shadow">
                                    <i class="bi bi-camera"></i>
                                </label>
                            </div>
                            <div class="mt-3">
                                <h6 class="mb-1">{{ $user->name }}</h6>
                                <span class="badge bg-primary">{{ ucfirst($user->getRoleNames()->first()) }}</span>
                                <div class="mt-2">
                                    <small class="text-muted">Klik ikon kamera untuk mengubah foto</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-person me-2"></i>Informasi Pribadi
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label fw-semibold">Email</label>
                                                    <input type="email" class="form-control bg-light" id="email"
                                                           value="{{ $user->email }}" readonly>
                                                    <small class="text-muted">Email tidak dapat diubah</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="institution" class="form-label fw-semibold">Institusi</label>
                                                    <input type="text" class="form-control @error('institution') is-invalid @enderror"
                                                           id="institution" name="institution" value="{{ old('institution', $user->institution) }}">
                                                    @error('institution')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="avatar" class="form-label fw-semibold">Foto Profil</label>
                                                    <input type="file" id="avatar" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                                    @error('avatar')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                                                </div>

                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="bi bi-shield-lock me-2"></i>Keamanan Akun
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('profile.password') }}" method="POST" id="password-form">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                                               id="current_password" name="current_password" required>
                                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                                            <i class="bi bi-eye" id="current_password_icon"></i>
                                                        </button>
                                                    </div>
                                                    @error('current_password', 'updatePassword')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="new_password" class="form-label fw-semibold">Password Baru</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control @error('new_password', 'updatePassword') is-invalid @enderror"
                                                               id="new_password" name="new_password" required>
                                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                                            <i class="bi bi-eye" id="new_password_icon"></i>
                                                        </button>
                                                    </div>
                                                    @error('new_password', 'updatePassword')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Minimal 8 karakter</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control"
                                                               id="new_password_confirmation" name="new_password_confirmation" required>
                                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                                            <i class="bi bi-eye" id="new_password_confirmation_icon"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="bi bi-shield-check me-2"></i>Ubah Password
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Pilih file gambar yang valid.');
                    return;
                }

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form validation
    const profileForm = document.getElementById('profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const phone = document.getElementById('phone').value.trim();

            if (name.length < 2) {
                e.preventDefault();
                alert('Nama harus minimal 2 karakter.');
                return;
            }

            if (phone.length < 10) {
                e.preventDefault();
                alert('Nomor telepon harus minimal 10 digit.');
                return;
            }
        });
    }

    const passwordForm = document.getElementById('password-form');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Konfirmasi password tidak cocok.');
                return;
            }

            if (newPassword.length < 8) {
                e.preventDefault();
                alert('Password baru harus minimal 8 karakter.');
                return;
            }
        });
    }
});

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');

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
</script>
@endpush
