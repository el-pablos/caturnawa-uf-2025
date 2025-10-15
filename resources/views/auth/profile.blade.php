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

                            <!-- Profile Completion -->
                            <div class="mt-3">
                                <small class="text-muted d-block mb-1">Profile Completion</small>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $user->profile_completion >= 100 ? 'success' : ($user->profile_completion >= 50 ? 'info' : 'warning') }}"
                                         role="progressbar"
                                         style="width: {{ $user->profile_completion }}%"
                                         aria-valuenow="{{ $user->profile_completion }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        {{ $user->profile_completion }}%
                                    </div>
                                </div>
                            </div>

                            <!-- Achievement Badges -->
                            @if($user->badges && count($user->badges) > 0)
                                <div class="mt-3">
                                    <small class="text-muted d-block mb-2">Achievement Badges</small>
                                    <div class="d-flex flex-wrap justify-content-center gap-2">
                                        @foreach($user->badges as $badgeKey)
                                            @php
                                                $badge = \App\Models\User::getAvailableBadges()[$badgeKey] ?? null;
                                            @endphp
                                            @if($badge)
                                                <span class="badge bg-{{ $badge['color'] }}"
                                                      title="{{ $badge['description'] }}"
                                                      data-bs-toggle="tooltip">
                                                    <i class="bi {{ $badge['icon'] }} me-1"></i>{{ $badge['name'] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Social Media Links -->
                            @if($user->hasSocialMediaLinks())
                                <div class="mt-3">
                                    <small class="text-muted d-block mb-2">Social Media</small>
                                    <div class="d-flex justify-content-center gap-2">
                                        @if($user->linkedin_url)
                                            <a href="{{ $user->linkedin_url }}" target="_blank" class="btn btn-sm btn-outline-primary" title="LinkedIn">
                                                <i class="bi bi-linkedin"></i>
                                            </a>
                                        @endif
                                        @if($user->twitter_url)
                                            <a href="{{ $user->twitter_url }}" target="_blank" class="btn btn-sm btn-outline-info" title="Twitter">
                                                <i class="bi bi-twitter"></i>
                                            </a>
                                        @endif
                                        @if($user->instagram_url)
                                            <a href="{{ $user->instagram_url }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Instagram">
                                                <i class="bi bi-instagram"></i>
                                            </a>
                                        @endif
                                        @if($user->facebook_url)
                                            <a href="{{ $user->facebook_url }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Facebook">
                                                <i class="bi bi-facebook"></i>
                                            </a>
                                        @endif
                                        @if($user->github_url)
                                            <a href="{{ $user->github_url }}" target="_blank" class="btn btn-sm btn-outline-dark" title="GitHub">
                                                <i class="bi bi-github"></i>
                                            </a>
                                        @endif
                                        @if($user->website_url)
                                            <a href="{{ $user->website_url }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Website">
                                                <i class="bi bi-globe"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
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
                                                           id="name" name="name" value="{{ old('name', $user->name) }}"
                                                           autocomplete="name" required>
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
                                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                                           autocomplete="tel" required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="institution" class="form-label fw-semibold">Institusi</label>
                                                    <input type="text" class="form-control @error('institution') is-invalid @enderror"
                                                           id="institution" name="institution" value="{{ old('institution', $user->institution) }}"
                                                           autocomplete="organization">
                                                    @error('institution')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="bio" class="form-label fw-semibold">Bio</label>
                                                    <textarea class="form-control @error('bio') is-invalid @enderror"
                                                              id="bio" name="bio" rows="3" maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                                                    @error('bio')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Maksimal 500 karakter</small>
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
                                                <i class="bi bi-share me-2"></i>Social Media Links
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('profile.update') }}" method="POST" id="social-media-form">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label for="linkedin_url" class="form-label fw-semibold">
                                                        <i class="bi bi-linkedin me-1"></i>LinkedIn
                                                    </label>
                                                    <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror"
                                                           id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}"
                                                           placeholder="https://linkedin.com/in/username">
                                                    @error('linkedin_url')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="twitter_url" class="form-label fw-semibold">
                                                        <i class="bi bi-twitter me-1"></i>Twitter
                                                    </label>
                                                    <input type="url" class="form-control @error('twitter_url') is-invalid @enderror"
                                                           id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $user->twitter_url) }}"
                                                           placeholder="https://twitter.com/username">
                                                    @error('twitter_url')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="instagram_url" class="form-label fw-semibold">
                                                        <i class="bi bi-instagram me-1"></i>Instagram
                                                    </label>
                                                    <input type="url" class="form-control @error('instagram_url') is-invalid @enderror"
                                                           id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $user->instagram_url) }}"
                                                           placeholder="https://instagram.com/username">
                                                    @error('instagram_url')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="facebook_url" class="form-label fw-semibold">
                                                        <i class="bi bi-facebook me-1"></i>Facebook
                                                    </label>
                                                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror"
                                                           id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $user->facebook_url) }}"
                                                           placeholder="https://facebook.com/username">
                                                    @error('facebook_url')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="github_url" class="form-label fw-semibold">
                                                        <i class="bi bi-github me-1"></i>GitHub
                                                    </label>
                                                    <input type="url" class="form-control @error('github_url') is-invalid @enderror"
                                                           id="github_url" name="github_url" value="{{ old('github_url', $user->github_url) }}"
                                                           placeholder="https://github.com/username">
                                                    @error('github_url')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="website_url" class="form-label fw-semibold">
                                                        <i class="bi bi-globe me-1"></i>Website
                                                    </label>
                                                    <input type="url" class="form-control @error('website_url') is-invalid @enderror"
                                                           id="website_url" name="website_url" value="{{ old('website_url', $user->website_url) }}"
                                                           placeholder="https://yourwebsite.com">
                                                    @error('website_url')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bi bi-check-lg me-2"></i>Simpan Social Media
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

                <!-- Achievement Badges Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-award me-2"></i>Achievement Badges
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->badges && count($user->badges) > 0)
                            <div class="row g-3">
                                @foreach($user->badges as $badgeKey)
                                    @php
                                        $badge = \App\Models\User::getAvailableBadges()[$badgeKey] ?? null;
                                    @endphp
                                    @if($badge)
                                        <div class="col-md-4">
                                            <div class="card border-{{ $badge['color'] }}">
                                                <div class="card-body text-center">
                                                    <i class="bi {{ $badge['icon'] }} text-{{ $badge['color'] }}" style="font-size: 3rem;"></i>
                                                    <h6 class="mt-2 mb-1">{{ $badge['name'] }}</h6>
                                                    <small class="text-muted">{{ $badge['description'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-award" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">No badges earned yet. Complete your profile and participate in competitions to earn badges!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Password Change Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shield-lock me-2"></i>Ubah Password
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                            <form action="{{ route('profile.password') }}" method="POST" id="password-form">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                                               id="current_password" name="current_password" autocomplete="current-password" required>
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
                                                               id="new_password" name="new_password" autocomplete="new-password" required>
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
                                                               id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password" required>
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

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
@endpush
