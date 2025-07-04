@extends('layouts.admin')

@section('title', 'Data Peserta')

@section('breadcrumb')
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0">Data Peserta</h1>
        <nav class="ms-auto">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Peserta</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-people fs-1 mb-2 text-primary"></i>
                <div class="unas-stats-number">{{ number_format($stats['total']) }}</div>
                <div class="unas-stats-label">Total Peserta</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-person-check fs-1 mb-2 text-success"></i>
                <div class="unas-stats-number text-success">{{ number_format($stats['active']) }}</div>
                <div class="unas-stats-label">Akun Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-shield-check fs-1 mb-2 text-info"></i>
                <div class="unas-stats-number text-info">{{ number_format($stats['verified']) }}</div>
                <div class="unas-stats-label">Email Verified</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-trophy fs-1 mb-2 text-warning"></i>
                <div class="unas-stats-number text-warning">{{ number_format($stats['registered']) }}</div>
                <div class="unas-stats-label">Terdaftar Kompetisi</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filter Peserta
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-2">
                <label class="form-label fw-semibold">Kompetisi</label>
                <select name="competition_id" class="form-select">
                    <option value="">Semua Kompetisi</option>
                    @foreach($competitions as $competition)
                        <option value="{{ $competition->id }}" {{ request('competition_id') == $competition->id ? 'selected' : '' }}>
                            {{ $competition->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Email Verified</option>
                    <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Email Unverified</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Institusi</label>
                <select name="institution" class="form-select">
                    <option value="">Semua Institusi</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution }}" {{ request('institution') === $institution ? 'selected' : '' }}>
                            {{ $institution }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, email, institusi, atau NIM..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('admin.participants.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Participants Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-people me-2"></i>Daftar Peserta
        </h6>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.participants.export', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="bi bi-download me-1"></i>Export CSV
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($participants->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Peserta</th>
                            <th>Institusi</th>
                            <th>Kompetisi</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $participant)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $participant->avatar_url }}" class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                        <div>
                                            <strong>{{ $participant->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $participant->email }}</small>
                                            @if($participant->student_id)
                                                <br><small class="text-muted">NIM: {{ $participant->student_id }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $participant->institution ?? 'Tidak ada' }}</td>
                                <td>
                                    @if($participant->registrations->count() > 0)
                                        @foreach($participant->registrations as $registration)
                                            <span class="badge bg-primary me-1 mb-1">{{ $registration->competition->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Belum mendaftar</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        @if($participant->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                        
                                        @if($participant->email_verified_at)
                                            <span class="badge bg-info">Verified</span>
                                        @else
                                            <span class="badge bg-warning">Unverified</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $participant->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.participants.show', $participant) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $participants->firstItem() }} - {{ $participants->lastItem() }} dari {{ $participants->total() }} peserta
                </div>
                {{ $participants->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">Tidak ada peserta ditemukan</h5>
                <p class="text-muted">Belum ada peserta yang sesuai dengan filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>
@endsection
