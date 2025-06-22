@if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin'))
    @extends('layouts.admin')
@elseif(auth()->user()->hasRole('Juri'))
    @extends('layouts.juri')
@elseif(auth()->user()->hasRole('Peserta'))
    @extends('layouts.peserta')
@else
    @extends('layouts.app')
@endif

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Profile Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-circle me-2"></i>Informasi Profil
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->avatar_url }}" 
                                     class="rounded-circle border" 
                                     width="150" height="150" 
                                     alt="Avatar" id="avatar-preview">
                                <label for="avatar" class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle">
                                    <i class="bi bi-camera"></i>
                                </label>
                                <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Klik ikon kamera untuk mengubah foto</small>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" 
                                           value="{{ $user->email }}" readonly>
                                    <small class="text-muted">Email tidak dapat diubah</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <input type="text" class="form-control" id="role" 
                                           value="{{ $user->getRoleNames()->first() }}" readonly>
                                    <small class="text-muted">Role tidak dapat diubah</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Change Password -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-shield-lock me-2"></i>Ubah Password
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control @error('new_password', 'updatePassword') is-invalid @enderror" 
                               id="new_password" name="new_password" required>
                        @error('new_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" 
                               id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                    
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-shield-check me-2"></i>Ubah Password
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Account Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Akun
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <small class="text-muted">Bergabung Sejak</small>
                        <div class="fw-semibold">{{ $user->created_at->format('d F Y') }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Terakhir Login</small>
                        <div class="fw-semibold">{{ $user->updated_at->format('d F Y H:i') }}</div>
                    </div>
                    <div class="col-12">
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
    
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
