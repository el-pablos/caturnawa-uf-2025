@extends('layouts.app')

@section('title', 'Kompetisi Saya')
@section('page-title', 'Kompetisi yang Ditugaskan')

@section('sidebar-menu')
    <a class="nav-link" href="{{ route('juri.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a class="nav-link active" href="{{ route('juri.competitions.index') }}">
        <i class="bi bi-trophy me-2"></i>Kompetisi Saya
    </a>
    <a class="nav-link" href="{{ route('juri.scoring.index') }}">
        <i class="bi bi-star me-2"></i>Penilaian
    </a>
    <a class="nav-link" href="{{ route('juri.submissions.index') }}">
        <i class="bi bi-file-earmark-text me-2"></i>Review Submission
    </a>
@endsection

@section('content')
<div class="row">
    @forelse($competitions as $competition)
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $competition->name }}</h5>
                    @if($competition->status === 'active')
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($competition->status) }}</span>
                    @endif
                </div>
                
                @if($competition->image)
                    <img src="{{ $competition->image_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $competition->name }}">
                @endif
                
                <div class="card-body">
                    <p class="card-text">{{ Str::limit($competition->description, 150) }}</p>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <small class="text-muted">Kategori</small>
                            <div class="fw-semibold">{{ $competition->category }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Total Peserta</small>
                            <div class="fw-semibold">{{ $competition->confirmed_registrations_count }} peserta</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Tanggal Mulai</small>
                            <div class="fw-semibold">{{ $competition->start_date->format('d M Y') }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Deadline Submission</small>
                            <div class="fw-semibold">{{ $competition->submission_deadline ? $competition->submission_deadline->format('d M Y') : 'Belum ditentukan' }}</div>
                        </div>
                    </div>

                    <!-- Progress Penilaian -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Progress Penilaian</small>
                            <small class="fw-semibold">{{ $competition->scoring_progress }}%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $competition->scoring_progress }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="{{ route('juri.competitions.show', $competition) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </a>
                        <a href="{{ route('juri.competitions.participants', $competition) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-people me-1"></i>Peserta
                        </a>
                        @if($competition->confirmed_registrations_count > 0)
                            <a href="{{ route('juri.scoring.competition', $competition) }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-star me-1"></i>Nilai
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-trophy fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Belum Ada Kompetisi</h5>
                    <p class="text-muted">Anda belum ditugaskan untuk menilai kompetisi apapun.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($competitions->count() > 0)
    <!-- Summary Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-trophy fs-2 text-primary mb-2"></i>
                    <div class="h4 mb-0">{{ $competitions->count() }}</div>
                    <small class="text-muted">Total Kompetisi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-2 text-success mb-2"></i>
                    <div class="h4 mb-0">{{ $competitions->sum('confirmed_registrations_count') }}</div>
                    <small class="text-muted">Total Peserta</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-clock fs-2 text-warning mb-2"></i>
                    <div class="h4 mb-0">{{ $competitions->where('scoring_progress', '<', 100)->count() }}</div>
                    <small class="text-muted">Belum Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle fs-2 text-info mb-2"></i>
                    <div class="h4 mb-0">{{ $competitions->where('scoring_progress', 100)->count() }}</div>
                    <small class="text-muted">Selesai Dinilai</small>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('styles')
<style>
.progress {
    background-color: #e9ecef;
}

.progress-bar {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endpush
