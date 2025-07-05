@extends('layouts.juri')

@section('title', 'Dashboard Juri')

@section('page-title', 'Dashboard Juri')

@section('header-actions')
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-download me-1"></i>Export & Laporan
            </button>
            <ul class="dropdown-menu">
                <li><h6 class="dropdown-header">Export Data</h6></li>
                <li><a class="dropdown-item" href="{{ route('juri.export.scores') }}">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export Penilaian (CSV)
                </a></li>
                <li><a class="dropdown-item" href="{{ route('juri.export.detailed-report') }}">
                    <i class="bi bi-file-earmark-text me-2"></i>Laporan Detail (CSV)
                </a></li>
                <li><a class="dropdown-item" href="{{ route('juri.export.statistics') }}">
                    <i class="bi bi-graph-up me-2"></i>Statistik (CSV)
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><h6 class="dropdown-header">Filter Export</h6></li>
                <li><a class="dropdown-item" href="#" onclick="showExportModal()">
                    <i class="bi bi-funnel me-2"></i>Export dengan Filter
                </a></li>
            </ul>
        </div>
        <a href="{{ route('juri.scoring.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-star me-1"></i>Mulai Penilaian
        </a>
    </div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">{{ $stats['assigned_competitions'] }}</div>
                    <div class="fw-semibold">Kompetisi Ditugaskan</div>
                </div>
                <div class="fs-1 opacity-50">
                    <i class="bi bi-trophy-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $stats['completed_scores'] }}</div>
                        <div class="fw-semibold">Penilaian Selesai</div>
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
                        <div class="stats-number">{{ $stats['pending_scores'] }}</div>
                        <div class="fw-semibold">Menunggu Penilaian</div>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-hourglass-split"></i>
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
                        <div class="stats-number">{{ number_format($stats['average_score'], 1) }}</div>
                        <div class="fw-semibold">Rata-rata Nilai</div>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Section -->
<div class="row mb-4">
    <div class="col-lg-8 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bar-chart me-2"></i>Progress Penilaian
                </h5>
            </div>
            <div class="card-body">
                @forelse($scoringProgress as $progress)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-0">{{ $progress['competition']->name }}</h6>
                            <small class="text-muted">{{ ucfirst($progress['competition']->category) }}</small>
                        </div>
                        <div class="text-end">
                            <span class="fw-semibold">{{ $progress['scored'] }}/{{ $progress['total'] }}</span>
                            <small class="text-muted d-block">{{ number_format($progress['percentage'], 1) }}%</small>
                        </div>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar 
                            @if($progress['percentage'] >= 100) bg-success
                            @elseif($progress['percentage'] >= 50) bg-primary
                            @else bg-warning
                            @endif" 
                            role="progressbar" 
                            style="width: {{ $progress['percentage'] }}%">
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                    Belum ada kompetisi yang perlu dinilai
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-trophy-fill me-2"></i>Kompetisi Aktif
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($activeCompetitions as $competition)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $competition->name }}</div>
                                <small class="text-muted">{{ ucfirst($competition->category) }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-primary">{{ $competition->registrations_count }} peserta</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('juri.scoring.competition', $competition) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    Nilai
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-trophy fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada kompetisi aktif
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Submissions and Recent Activities -->
<div class="row">
    <div class="col-lg-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>Submission Belum Dinilai
                </h6>
                <a href="{{ route('juri.scoring.index') }}" class="btn btn-sm btn-warning">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($pendingSubmissions->take(5) as $submission)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $submission->title }}</div>
                                <small class="text-muted">
                                    {{ $submission->registration->display_name }} -
                                    {{ $submission->registration->competition->name }}
                                </small>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        Disubmit {{ $submission->submitted_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('juri.scoring.submission', $submission) }}" 
                                   class="btn btn-sm btn-primary">
                                    Nilai
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-check-circle fs-1 d-block mb-2 opacity-50"></i>
                        Semua submission sudah dinilai
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentActivities as $activity)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">
                                    Menilai {{ $activity->registration->display_name }}
                                </div>
                                <small class="text-muted">{{ $activity->registration->competition->name }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-{{ $activity->is_final ? 'success' : 'warning' }}">
                                        {{ $activity->is_final ? 'Final' : 'Draft' }}
                                    </span>
                                    <span class="badge bg-info ms-1">{{ $activity->total_score }}</span>
                                </div>
                            </div>
                            <small class="text-muted">{{ $activity->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-activity fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada aktivitas
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-download me-2"></i>Export dengan Filter
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="exportForm" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="export_type" class="form-label">Jenis Export</label>
                        <select class="form-select" id="export_type" name="export_type" required>
                            <option value="">Pilih jenis export...</option>
                            <option value="scores">Penilaian</option>
                            <option value="detailed-report">Laporan Detail</option>
                            <option value="statistics">Statistik</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="competition_filter" class="form-label">Kompetisi</label>
                        <select class="form-select" id="competition_filter" name="competition_id">
                            <option value="">Semua Kompetisi</option>
                            @foreach($activeCompetitions as $competition)
                                <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="status_filter_group">
                        <label for="status_filter" class="form-label">Status Penilaian</label>
                        <select class="form-select" id="status_filter" name="status">
                            <option value="">Semua Status</option>
                            <option value="final">Final</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>File akan didownload dalam format CSV yang dapat dibuka dengan Excel atau Google Sheets.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-download me-2"></i>Download
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showExportModal() {
    const modal = new bootstrap.Modal(document.getElementById('exportModal'));
    modal.show();
}

document.getElementById('export_type').addEventListener('change', function() {
    const exportType = this.value;
    const form = document.getElementById('exportForm');
    const statusGroup = document.getElementById('status_filter_group');

    // Update form action based on export type
    if (exportType) {
        form.action = `{{ route('juri.export.scores') }}`.replace('scores', exportType);
    }

    // Hide status filter for statistics export
    if (exportType === 'statistics') {
        statusGroup.style.display = 'none';
    } else {
        statusGroup.style.display = 'block';
    }
});

// Handle form submission
document.getElementById('exportForm').addEventListener('submit', function(e) {
    const exportType = document.getElementById('export_type').value;
    if (!exportType) {
        e.preventDefault();
        alert('Silakan pilih jenis export terlebih dahulu.');
        return;
    }

    // Close modal after submission
    setTimeout(() => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
        modal.hide();
    }, 100);
});
</script>
@endpush
