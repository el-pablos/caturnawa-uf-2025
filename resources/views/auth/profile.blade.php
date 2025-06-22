@php
    $user = auth()->user();
    $layout = 'layouts.app';

    if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
        $layout = 'layouts.admin';
    } elseif ($user->hasRole('Juri')) {
        $layout = 'layouts.juri';
    } elseif ($user->hasRole('Peserta')) {
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
            <!-- Profile Header -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-circle me-2"></i>Profil Pengguna
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->avatar_url }}"
                                     class="rounded-circle border border-3 border-primary shadow"
                                     width="150" height="150"
                                     alt="Avatar" id="avatar-preview"
                                     style="object-fit: cover;">
                                <label for="avatar" class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle shadow">
                                    <i class="bi bi-camera"></i>
                                </label>

                            </div>
                            <div class="mt-3">
                                <h6 class="text-primary mb-1">{{ $user->name }}</h6>
                                <span class="badge bg-primary">{{ $user->getRoleNames()->first() }}</span>
                                <div class="mt-2">
                                    <small class="text-muted">Klik ikon kamera untuk mengubah foto</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-light">
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
                                        <div class="card-header bg-warning text-dark">
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

            <!-- Account Information -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Informasi Akun
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <small class="text-muted">Bergabung Sejak</small>
                                    <div class="fw-semibold">{{ $user->created_at->format('d F Y') }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Terakhir Login</small>
                                    <div class="fw-semibold">{{ $user->updated_at->format('d M Y H:i') }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Status Akun</small>
                                    <div>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Terverifikasi
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Belum Terverifikasi
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Role</small>
                                    <div>
                                        <span class="badge bg-primary">{{ $user->getRoleNames()->first() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-activity me-2"></i>Aktivitas Terbaru
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $activities = [];

                                // Add role-specific activities
                                if ($user->hasRole('Peserta')) {
                                    $registrations = $user->registrations()->latest()->take(3)->get();
                                    foreach ($registrations as $reg) {
                                        $activities[] = [
                                            'icon' => 'bi-person-check',
                                            'text' => 'Mendaftar ' . $reg->competition->name,
                                            'time' => $reg->created_at->diffForHumans(),
                                            'color' => 'text-primary'
                                        ];
                                    }
                                } elseif ($user->hasRole('Juri')) {
                                    $activities[] = [
                                        'icon' => 'bi-star',
                                        'text' => 'Menilai submission',
                                        'time' => '2 hari yang lalu',
                                        'color' => 'text-warning'
                                    ];
                                } else {
                                    $activities[] = [
                                        'icon' => 'bi-gear',
                                        'text' => 'Mengelola sistem',
                                        'time' => '1 hari yang lalu',
                                        'color' => 'text-info'
                                    ];
                                }

                                // Add login activity
                                $activities[] = [
                                    'icon' => 'bi-box-arrow-in-right',
                                    'text' => 'Login terakhir',
                                    'time' => $user->updated_at->diffForHumans(),
                                    'color' => 'text-success'
                                ];
                            @endphp

                            @if(count($activities) > 0)
                                <div class="timeline">
                                    @foreach($activities as $activity)
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle bg-light p-2">
                                                    <i class="bi {{ $activity['icon'] }} {{ $activity['color'] }}"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="fw-semibold">{{ $activity['text'] }}</div>
                                                <small class="text-muted">{{ $activity['time'] }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-clock-history fs-1 mb-2"></i>
                                    <p>Belum ada aktivitas</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-lightning me-2"></i>Aksi Cepat
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @if($user->hasRole('Peserta'))
                                    <div class="col-md-3">
                                        <a href="{{ route('peserta.competitions.index') }}" class="btn btn-outline-primary w-100">
                                            <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('peserta.registrations.index') }}" class="btn btn-outline-success w-100">
                                            <i class="bi bi-list-check me-2"></i>Registrasi Saya
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('peserta.submissions.index') }}" class="btn btn-outline-warning w-100">
                                            <i class="bi bi-file-earmark me-2"></i>Karya Saya
                                        </a>
                                    </div>
                                @elseif($user->hasRole('Juri'))
                                    <div class="col-md-3">
                                        <a href="{{ route('juri.competitions.index') }}" class="btn btn-outline-primary w-100">
                                            <i class="bi bi-trophy me-2"></i>Kompetisi
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('juri.scoring.index') }}" class="btn btn-outline-warning w-100">
                                            <i class="bi bi-star me-2"></i>Penilaian
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('juri.submissions.index') }}" class="btn btn-outline-info w-100">
                                            <i class="bi bi-file-earmark me-2"></i>Review Karya
                                        </a>
                                    </div>
                                @else
                                    <div class="col-md-3">
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary w-100">
                                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-success w-100">
                                            <i class="bi bi-people me-2"></i>Kelola User
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('admin.competitions.index') }}" class="btn btn-outline-warning w-100">
                                            <i class="bi bi-trophy me-2"></i>Kompetisi
                                        </a>
                                    </div>
                                @endif
                                <div class="col-md-3">
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger w-100">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.profile-avatar {
    transition: all 0.3s ease;
}

.profile-avatar:hover {
    transform: scale(1.05);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.input-group .btn {
    border-left: none;
}

.form-control:focus + .btn {
    border-color: #86b7fe;
}
</style>
@endpush

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
                    alert('Please select a valid image file.');
                    return;
                }

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                    avatarPreview.classList.add('profile-avatar');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Password strength indicator
    const newPasswordInput = document.getElementById('new_password');
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            updatePasswordStrengthIndicator(strength);
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

    // Auto-save draft (optional)
    let saveTimeout;
    const formInputs = document.querySelectorAll('#profile-form input[type="text"], #profile-form input[type="email"]');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                // Auto-save functionality can be implemented here
                console.log('Auto-saving profile data...');
            }, 2000);
        });
    });
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

// Calculate password strength
function calculatePasswordStrength(password) {
    let strength = 0;

    if (password.length >= 8) strength += 1;
    if (password.match(/[a-z]/)) strength += 1;
    if (password.match(/[A-Z]/)) strength += 1;
    if (password.match(/[0-9]/)) strength += 1;
    if (password.match(/[^a-zA-Z0-9]/)) strength += 1;

    return strength;
}

// Update password strength indicator
function updatePasswordStrengthIndicator(strength) {
    const indicator = document.getElementById('password-strength');
    if (!indicator) return;

    const colors = ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#198754'];
    const texts = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];

    indicator.style.backgroundColor = colors[strength - 1] || '#dc3545';
    indicator.textContent = texts[strength - 1] || 'Sangat Lemah';
}

// Smooth scroll for quick actions
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Loading states for forms
function showFormLoading(formId) {
    const form = document.getElementById(formId);
    const submitBtn = form.querySelector('button[type="submit"]');

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
}

function hideFormLoading(formId, originalText) {
    const form = document.getElementById(formId);
    const submitBtn = form.querySelector('button[type="submit"]');

    submitBtn.disabled = false;
    submitBtn.innerHTML = originalText;
}

// Add loading states to forms
document.getElementById('profile-form')?.addEventListener('submit', function() {
    showFormLoading('profile-form');
});

document.getElementById('password-form')?.addEventListener('submit', function() {
    showFormLoading('password-form');
});
</script>
@endpush
