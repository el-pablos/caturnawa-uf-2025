@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('page-title', 'Detail Pengguna')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- User Profile Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>Profil Pengguna
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="{{ $user->avatar_url }}" alt="Avatar" 
                         class="rounded-circle" width="120" height="120">
                </div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <div class="mb-3">
                    @foreach($user->getRoleNames() as $role)
                        <span class="badge bg-primary">{{ ucfirst($role) }}</span>
                    @endforeach
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border-end">
                            <h6 class="mb-0">{{ $user->registrations ? $user->registrations->count() : 0 }}</h6>
                            <small class="text-muted">Registrasi</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border-end">
                            <h6 class="mb-0">{{ $user->submissions ? $user->submissions->count() : 0 }}</h6>
                            <small class="text-muted">Karya</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <h6 class="mb-0">{{ $user->payments ? $user->payments->where('status', 'paid')->count() : 0 }}</h6>
                        <small class="text-muted">Pembayaran</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>Status Akun
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status Akun</label>
                    <div>
                        @if($user->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Verified</label>
                    <div>
                        @if($user->email_verified_at)
                            <span class="badge bg-success">Terverifikasi</span>
                            <small class="text-muted d-block">{{ $user->email_verified_at->format('d M Y H:i') }}</small>
                        @else
                            <span class="badge bg-warning">Belum Terverifikasi</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Bergabung</label>
                    <div>
                        <small class="text-muted">{{ $user->created_at->format('d F Y H:i') }}</small>
                    </div>
                </div>
                
                <div class="mb-0">
                    <label class="form-label fw-semibold">Terakhir Update</label>
                    <div>
                        <small class="text-muted">{{ $user->updated_at->format('d F Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- User Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Detail
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <p class="mb-0">{{ $user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <p class="mb-0">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Institusi</label>
                        <p class="mb-0">{{ $user->institution ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <p class="mb-0">{{ $user->address ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                        <p class="mb-0">{{ $user->birth_date ? $user->birth_date->format('d F Y') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Registrations -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-list-check me-2"></i>Riwayat Registrasi ({{ $user->registrations ? $user->registrations->count() : 0 }})
                </h6>
            </div>
            <div class="card-body">
                @if($user->registrations && $user->registrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kompetisi</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->registrations as $registration)
                                    <tr>
                                        <td>{{ $registration->competition->name }}</td>
                                        <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @if($registration->status == 'confirmed')
                                                <span class="badge bg-success">Terkonfirmasi</span>
                                            @elseif($registration->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Dibatalkan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($registration->payment)
                                                @if($registration->payment->status == 'paid')
                                                    <span class="badge bg-success">Lunas</span>
                                                @elseif($registration->payment->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Gagal</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Belum Bayar</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.registrations.show', $registration) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted">Belum ada registrasi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Submissions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>Karya yang Dikirim ({{ $user->submissions ? $user->submissions->count() : 0 }})
                </h6>
            </div>
            <div class="card-body">
                @if($user->submissions && $user->submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Kompetisi</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->submissions as $submission)
                                    <tr>
                                        <td>{{ $submission->title }}</td>
                                        <td>{{ $submission->competition->name }}</td>
                                        <td>{{ $submission->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @if($submission->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($submission->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.submissions.show', $submission) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted">Belum ada karya yang dikirim</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
