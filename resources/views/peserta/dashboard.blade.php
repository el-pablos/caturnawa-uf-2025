@extends('layouts.peserta')

@section('title', 'Dashboard Peserta')

@section('page-title', 'Dashboard Peserta')

@section('header-actions')
    <a href="{{ route('peserta.competitions.index') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Daftar Kompetisi
    </a>
@endsection

@section('content')
<style>
.colorful-tabs .nav-link {
    border-radius: 12px;
    margin: 0 5px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.colorful-tabs .nav-link:not(.active) {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #495057;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.colorful-tabs .nav-link:not(.active):hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.colorful-tabs #overview-tab.active {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-color: #0056b3;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.colorful-tabs #guidance-tab.active {
    background: linear-gradient(135deg, #6f42c1 0%, #5a2d91 100%) !important;
    color: white !important;
    border-color: #5a2d91 !important;
    box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3) !important;
}

.colorful-tabs #upload-tab.active {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
    color: white !important;
    border-color: #138496 !important;
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3) !important;
}

.colorful-tabs #submissions-tab.active {
    background: linear-gradient(135deg, #fd7e14 0%, #e55a00 100%) !important;
    color: white !important;
    border-color: #e55a00 !important;
    box-shadow: 0 4px 12px rgba(253, 126, 20, 0.3) !important;
}

/* Improved contrast for better visibility */
.colorful-tabs .nav-link {
    font-size: 0.95rem;
    padding: 12px 20px;
    min-height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.colorful-tabs .nav-link:not(.active) {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    color: #495057;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    border: 1px solid #e9ecef;
}

.colorful-tabs .nav-link:not(.active):hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #212529;
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.18);
}

/* Enhanced active tab styling */
.colorful-tabs .nav-link.active {
    transform: translateY(-2px) !important;
    font-weight: 700 !important;
    letter-spacing: 0.025em !important;
}

/* Force override any conflicting styles */
#dashboardTabs #overview-tab.nav-link.active {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    color: white !important;
    border-color: #0056b3 !important;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3) !important;
}

#dashboardTabs #guidance-tab.nav-link.active {
    background: linear-gradient(135deg, #6f42c1 0%, #5a2d91 100%) !important;
    color: white !important;
    border-color: #5a2d91 !important;
    box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3) !important;
}

#dashboardTabs #upload-tab.nav-link.active {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
    color: white !important;
    border-color: #138496 !important;
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3) !important;
}

#dashboardTabs #submissions-tab.nav-link.active {
    background: linear-gradient(135deg, #fd7e14 0%, #e55a00 100%) !important;
    color: white !important;
    border-color: #e55a00 !important;
    box-shadow: 0 4px 12px rgba(253, 126, 20, 0.3) !important;
}

/* Statistics Cards with Better Contrast */
.card.bg-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
}

.card.bg-success {
    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25) !important;
}

.card.bg-warning {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25) !important;
    color: white !important;
}

.card.bg-info {
    background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%) !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(8, 145, 178, 0.25) !important;
}

/* Ensure text is always readable on colored cards */
.card.bg-primary .stats-number,
.card.bg-success .stats-number,
.card.bg-warning .stats-number,
.card.bg-info .stats-number {
    color: white !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.card.bg-primary .fw-semibold,
.card.bg-success .fw-semibold,
.card.bg-warning .fw-semibold,
.card.bg-info .fw-semibold {
    color: rgba(255,255,255,0.95) !important;
}
</style>

<!-- Navigation Tabs -->
<ul class="nav nav-pills mb-4 colorful-tabs" id="dashboardTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab">
            <i class="bi bi-speedometer2 me-2"></i>Overview
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="guidance-tab" data-bs-toggle="pill" data-bs-target="#guidance" type="button" role="tab">
            <i class="bi bi-compass me-2"></i>Panduan Penggunaan Dashboard Caturnawa
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="upload-tab" data-bs-toggle="pill" data-bs-target="#upload" type="button" role="tab">
            <i class="bi bi-cloud-upload me-2"></i>Upload Karya
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="submissions-tab" data-bs-toggle="pill" data-bs-target="#submissions" type="button" role="tab">
            <i class="bi bi-file-earmark-text me-2"></i>Submissions
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="dashboardTabsContent">
    <!-- Overview Tab -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        <!-- Overview Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                    <div class="card-body text-white">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-3 fw-bold" style="color: #ffffff; text-shadow: 2px 2px 6px rgba(0,0,0,0.8); font-weight: 800;">
                                    <i class="bi bi-speedometer2 me-2"></i>
                                    Dashboard Overview
                                </h4>
                                <p class="mb-3 lead" style="color: #ffffff; text-shadow: 1px 1px 4px rgba(0,0,0,0.7); font-weight: 500;">
                                    Ringkasan aktivitas dan status kompetisi Anda dalam satu tampilan.
                                </p>
                                <div class="d-flex gap-2">
                                    <span class="badge p-2" style="background-color: rgba(255,255,255,0.95); color: #1f2937; font-weight: 600; text-shadow: none;">
                                        <i class="bi bi-graph-up me-1"></i>Live Statistics
                                    </span>
                                    <span class="badge p-2" style="background-color: rgba(255,255,255,0.95); color: #1f2937; font-weight: 600; text-shadow: none;">
                                        <i class="bi bi-bell me-1"></i>Quick Actions
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="bi bi-pie-chart display-1 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $stats['total_registrations'] }}</div>
                        <div class="fw-semibold">Total Pendaftaran</div>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-card-list"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $stats['confirmed_registrations'] }}</div>
                        <div class="fw-semibold">Terkonfirmasi</div>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">Rp {{ number_format($stats['total_paid'], 0, ',', '.') }}</div>
                        <div class="fw-semibold">Total Dibayar</div>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-wallet-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $stats['final_submissions'] }}/{{ $stats['total_submissions'] }}</div>
                        <div class="fw-semibold">Karya Final</div>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-Lock Status Alert -->
@if($autoLockStatus['is_locked'])
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="card-title mb-0">
                    <i class="bi bi-lock-fill me-2"></i>Status Pendaftaran Terkunci
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-3">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill me-3 mt-1 text-warning"></i>
                        <div>
                            <strong>{{ $autoLockStatus['lock_reason'] }}</strong>
                            <p class="mb-0 mt-2">{{ $autoLockStatus['message'] }}</p>
                        </div>
                    </div>
                </div>

                @if($autoLockStatus['locked_competitions']->isNotEmpty())
                <h6 class="mb-3">Kompetisi yang Sudah Terdaftar:</h6>
                <div class="row">
                    @foreach($autoLockStatus['locked_competitions'] as $registration)
                    <div class="col-md-6 mb-3">
                        <div class="card border-success">
                            <div class="card-body">
                                <h6 class="card-title text-success">
                                    <i class="bi bi-trophy me-2"></i>{{ $registration->competition->name }}
                                </h6>
                                <p class="card-text small mb-2">
                                    <strong>Status:</strong>
                                    <span class="badge bg-success">{{ ucfirst($registration->status) }}</span>
                                </p>
                                <p class="card-text small mb-0">
                                    <strong>Terdaftar:</strong> {{ $registration->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0">
                    <i class="bi bi-unlock-fill me-2"></i>Status Pendaftaran Tersedia
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-success mb-0">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-3 text-success"></i>
                        <div>
                            <strong>{{ $autoLockStatus['message'] }}</strong>
                            <p class="mb-0 mt-1">Silakan pilih kompetisi yang ingin Anda ikuti dari daftar kompetisi yang tersedia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Upcoming Deadlines Alert -->
@if(count($upcomingDeadlines) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="card-title mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>Deadline Terdekat
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($upcomingDeadlines as $deadline)
                    <div class="col-md-6 col-lg-4 mb-2">
                        <div class="alert alert-{{ $deadline['status'] }} mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <strong>{{ $deadline['title'] }}</strong>
                                    <div class="small">
                                        {{ $deadline['deadline']->format('d M Y H:i') }}
                                        ({{ $deadline['deadline']->diffForHumans() }})
                                    </div>
                                </div>
                                <a href="{{ $deadline['action_url'] }}" class="btn btn-sm btn-{{ $deadline['status'] }}">
                                    {{ $deadline['type'] === 'payment' ? 'Bayar' : 'Submit' }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Main Content -->
<div class="row">
    <!-- My Registrations -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="bi bi-card-list me-2"></i>Pendaftaran Saya
                </h6>
                <a href="{{ route('peserta.registrations.index') }}" class="btn btn-sm btn-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($registrations->take(5) as $registration)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            @if($registration->competition->image)
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ asset('storage/competitions/' . $registration->competition->image) }}" 
                                     alt="{{ $registration->competition->name }}" 
                                     class="rounded" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $registration->competition->name }}</div>
                                <small class="text-muted">
                                    {{ $registration->registration_number }}
                                    @if($registration->team_name)
                                        - Tim: {{ $registration->team_name }}
                                    @endif
                                </small>
                                <div class="mt-1">
                                    @if($registration->status === 'confirmed')
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                    @elseif($registration->payment && $registration->payment->is_confirmed)
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                    @elseif($registration->payment && $registration->payment->isSuccess())
                                        <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                    @elseif($registration->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($registration->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold">Rp {{ number_format($registration->amount, 0, ',', '.') }}</div>
                                <small class="text-muted">{{ $registration->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada pendaftaran
                        <div class="mt-2">
                            <a href="{{ route('peserta.competitions.index') }}" class="btn btn-sm btn-primary">
                                Daftar Kompetisi
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Available Competitions -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="bi bi-trophy-fill me-2"></i>Kompetisi Tersedia
                </h6>
                <a href="{{ route('peserta.competitions.index') }}" class="btn btn-sm btn-success">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($availableCompetitions as $competition)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            @if($competition->image)
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ asset('storage/competitions/' . $competition->image) }}" 
                                     alt="{{ $competition->name }}" 
                                     class="rounded" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $competition->name }}</div>
                                <small class="text-muted">{{ ucfirst($competition->category) }}</small>
                                <div class="mt-1">
                                    @if($competition->isEarlyBird())
                                        <span class="badge bg-warning">Early Bird</span>
                                    @endif
                                    <span class="badge bg-info">
                                        {{ $competition->getRegisteredParticipantsCount() }} peserta
                                    </span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold">Rp {{ number_format($competition->current_price, 0, ',', '.') }}</div>
                                <small class="text-muted">
                                    {{ $competition->registration_end->diffForHumans() }}
                                </small>
                                <div class="mt-1">
                                    @if($autoLockStatus['is_locked'])
                                        <button class="btn btn-sm btn-secondary" disabled title="Pendaftaran terkunci">
                                            <i class="bi bi-lock-fill me-1"></i>Terkunci
                                        </button>
                                    @else
                                        <a href="{{ route('peserta.competitions.show', $competition) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Daftar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-trophy fs-1 d-block mb-2 opacity-50"></i>
                        @if($autoLockStatus['is_locked'])
                            Pendaftaran kompetisi terkunci
                            <div class="mt-2 small">
                                Anda sudah terdaftar di kompetisi lain
                            </div>
                        @else
                            Tidak ada kompetisi tersedia
                        @endif
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
    
    <!-- Guidance Tab -->
    <div class="tab-pane fade" id="guidance" role="tabpanel">
        @include('peserta.partials.guidance')
    </div>

    <!-- Upload Karya Tab -->
    <div class="tab-pane fade" id="upload" role="tabpanel">
        @include('peserta.partials.upload-karya')
    </div>

    <!-- Submissions Tab -->
    <div class="tab-pane fade" id="submissions" role="tabpanel">
        <!-- Submissions Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #fd7e14 0%, #e55a00 100%); box-shadow: 0 4px 12px rgba(253, 126, 20, 0.25);">
                    <div class="card-body text-white">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-3 fw-bold" style="color: #ffffff; text-shadow: 2px 2px 6px rgba(0,0,0,0.8); font-weight: 800;">
                                    <i class="bi bi-file-earmark-text-fill me-2"></i>
                                    Status Submissions
                                </h4>
                                <p class="mb-3 lead" style="color: #ffffff; text-shadow: 1px 1px 4px rgba(0,0,0,0.7); font-weight: 500;">
                                    Kelola dan pantau status karya yang telah Anda submit untuk setiap kompetisi.
                                </p>
                                <div class="d-flex gap-2">
                                    <span class="badge p-2" style="background-color: rgba(255,255,255,0.95); color: #1f2937; font-weight: 600; text-shadow: none;">
                                        <i class="bi bi-eye me-1"></i>Real-time Status
                                    </span>
                                    <span class="badge p-2" style="background-color: rgba(255,255,255,0.95); color: #1f2937; font-weight: 600; text-shadow: none;">
                                        <i class="bi bi-graph-up me-1"></i>Track Progress
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="bi bi-clipboard-data display-1 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submissions Status -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="bi bi-file-earmark-text-fill me-2"></i>Status Submission
                </h6>
                <a href="{{ route('peserta.submissions.index') }}" class="btn btn-sm btn-info">
                    Kelola Submission
                </a>
            </div>
            <div class="card-body">
                @if(count($submissions) > 0)
                <div class="row">
                    @foreach($submissions as $submission)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-{{ $submission->status_class }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">{{ $submission->title }}</h6>
                                    <span class="badge bg-{{ $submission->status_class }}">
                                        {{ $submission->status_label }}
                                    </span>
                                </div>
                                <p class="card-text text-muted small">
                                    {{ $submission->registration->competition->name }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        {{ $submission->file_count }} file(s)
                                        @if($submission->submitted_at)
                                            - {{ $submission->submitted_at->diffForHumans() }}
                                        @endif
                                    </small>
                                    <div>
                                        @if($submission->getAverageScore() > 0)
                                            <span class="badge bg-success">
                                                {{ number_format($submission->getAverageScore(), 1) }}
                                            </span>
                                        @endif
                                        <a href="{{ route('peserta.submissions.show', $submission) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-file-earmark fs-1 d-block mb-2 opacity-50"></i>
                    <p>Belum ada submission</p>
                    <small>Submission dapat dibuat setelah pendaftaran dikonfirmasi</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh untuk update status real-time
    setInterval(function() {
        // Cek update status pembayaran untuk pending registrations
        $('.badge:contains("pending")').closest('.list-group-item').each(function() {
            // AJAX call untuk cek status terbaru
            // Implementation dapat ditambahkan sesuai kebutuhan
        });
    }, 30000); // Check every 30 seconds
});
</script>
@endpush