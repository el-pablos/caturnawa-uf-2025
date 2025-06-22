@extends('layouts.peserta')

@section('title', 'Dashboard Peserta')

@section('page-title', 'Dashboard Peserta')

@section('header-actions')
    <a href="{{ route('peserta.competitions.index') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Daftar Kompetisi
    </a>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
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
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $registration->competition->name }}</div>
                                <small class="text-muted">
                                    {{ $registration->registration_number }}
                                    @if($registration->team_name)
                                        - Tim: {{ $registration->team_name }}
                                    @endif
                                </small>
                                <div class="mt-1">
                                    <span class="badge bg-{{ $registration->status === 'confirmed' ? 'success' : ($registration->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                    @if($registration->isPaid())
                                        <span class="badge bg-success ms-1">Lunas</span>
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
                                    <a href="{{ route('peserta.competitions.show', $competition) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        Daftar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-trophy fs-1 d-block mb-2 opacity-50"></i>
                        Tidak ada kompetisi tersedia
                    </div>
                    @endforelse
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
