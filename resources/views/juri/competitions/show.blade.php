@extends('layouts.juri')

@section('title', 'Detail Kompetisi')
@section('page-title', 'Detail Kompetisi: ' . $competition->name)

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
<!-- Competition Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trophy me-2"></i>{{ $competition->name }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('juri.competitions.participants', $competition) }}" class="btn btn-light btn-sm">
                            <i class="bi bi-people me-1"></i>Peserta
                        </a>
                        <a href="{{ route('juri.competitions.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
            @if($competition->image)
                <img src="{{ $competition->image_url }}" class="card-img-top" style="height: 300px; object-fit: cover;" alt="{{ $competition->name }}">
            @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="lead">{{ $competition->description }}</p>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <small class="text-muted">Kategori</small>
                                <div class="fw-semibold">{{ $competition->category }}</div>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">Status</small>
                                <div>
                                    @if($competition->status === 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($competition->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">Tanggal Kompetisi</small>
                                <div class="fw-semibold">
                                    {{ $competition->competition_start ? $competition->competition_start->format('d M Y') : 'Belum ditentukan' }}
                                    @if($competition->competition_end)
                                        - {{ $competition->competition_end->format('d M Y') }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">Deadline Submission</small>
                                <div class="fw-semibold">
                                    {{ $competition->submission_deadline ? $competition->submission_deadline->format('d M Y H:i') : 'Belum ditentukan' }}
                                </div>
                            </div>
                        </div>

                        <!-- Competition Details -->
                        @if($competition->rules && is_array($competition->rules))
                            <div class="mb-4">
                                <h6 class="fw-bold">Aturan Kompetisi</h6>
                                <ul class="list-unstyled">
                                    @foreach($competition->rules as $rule)
                                        <li class="mb-2">
                                            <i class="bi bi-check-circle text-success me-2"></i>{{ $rule }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($competition->requirements && is_array($competition->requirements))
                            <div class="mb-4">
                                <h6 class="fw-bold">Persyaratan</h6>
                                <ul class="list-unstyled">
                                    @foreach($competition->requirements as $requirement)
                                        <li class="mb-2">
                                            <i class="bi bi-arrow-right text-primary me-2"></i>{{ $requirement }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <!-- Statistics -->
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-graph-up me-2"></i>Statistik
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="text-center">
                                            <div class="h4 mb-0 text-primary">{{ $statistics['total_participants'] }}</div>
                                            <small class="text-muted">Total Peserta</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">
                                            <div class="h4 mb-0 text-success">{{ $statistics['confirmed_participants'] }}</div>
                                            <small class="text-muted">Terkonfirmasi</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">
                                            <div class="h4 mb-0 text-warning">{{ $statistics['total_submissions'] }}</div>
                                            <small class="text-muted">Submission</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">
                                            <div class="h4 mb-0 text-info">{{ $statistics['scored_submissions'] }}</div>
                                            <small class="text-muted">Sudah Dinilai</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Penilaian -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-clipboard-check me-2"></i>Progress Penilaian
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Kemajuan Anda</small>
                                    <small class="fw-semibold">{{ $statistics['scoring_progress'] }}%</small>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $statistics['scoring_progress'] }}%"></div>
                                </div>
                                <div class="d-grid">
                                    @if($statistics['total_submissions'] > 0)
                                        <a href="{{ route('juri.scoring.competition', $competition) }}" 
                                           class="btn btn-success btn-sm">
                                            <i class="bi bi-star me-1"></i>Mulai Penilaian
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="bi bi-clock me-1"></i>Belum Ada Submission
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Prizes Section -->
@if($competition->prizes && is_array($competition->prizes))
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-award me-2"></i>Hadiah Kompetisi
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($competition->prizes as $index => $prize)
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    @if($index == 0)
                                        <i class="bi bi-trophy-fill text-warning fs-1 mb-2"></i>
                                        <h6 class="text-warning">Juara 1</h6>
                                    @elseif($index == 1)
                                        <i class="bi bi-trophy text-secondary fs-1 mb-2"></i>
                                        <h6 class="text-secondary">Juara 2</h6>
                                    @elseif($index == 2)
                                        <i class="bi bi-trophy text-warning fs-1 mb-2"></i>
                                        <h6 class="text-warning">Juara 3</h6>
                                    @else
                                        <i class="bi bi-award text-info fs-1 mb-2"></i>
                                        <h6 class="text-info">Juara {{ $index + 1 }}</h6>
                                    @endif
                                    <p class="mb-0">{{ $prize }}</p>
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

<!-- Recent Submissions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Submission Terbaru
                    </h6>
                    <a href="{{ route('juri.scoring.competition', $competition) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentSubmissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th>Judul</th>
                                    <th>Tanggal Submit</th>
                                    <th>Status Penilaian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSubmissions as $submission)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    @if($submission->registration->user->avatar)
                                                        <img src="{{ $submission->registration->user->avatar_url }}" 
                                                             class="rounded-circle" width="32" height="32" alt="Avatar">
                                                    @else
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 32px; height: 32px;">
                                                            <span class="text-white fw-bold small">
                                                                {{ substr($submission->registration->user->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $submission->registration->display_name }}</div>
                                                    <small class="text-muted">{{ $submission->registration->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $submission->title ?: 'Untitled' }}</div>
                                            @if($submission->description)
                                                <small class="text-muted">{{ Str::limit($submission->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y') : 'N/A' }}</div>
                                            <small class="text-muted">{{ $submission->submitted_at ? $submission->submitted_at->format('H:i') : '' }}</small>
                                        </td>
                                        <td>
                                            @if($submission->jury_score)
                                                @if($submission->jury_score->is_final)
                                                    <span class="badge bg-success">Final</span>
                                                @else
                                                    <span class="badge bg-warning">Draft</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Belum Dinilai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('juri.submissions.show', $submission) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('juri.scoring.submission', $submission) }}" 
                                                   class="btn btn-outline-success">
                                                    <i class="bi bi-star"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-file-earmark-text fs-1 text-muted mb-3"></i>
                        <h6 class="text-muted">Belum Ada Submission</h6>
                        <p class="text-muted">Belum ada peserta yang mengumpulkan karya untuk kompetisi ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm img {
    object-fit: cover;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.progress {
    background-color: #e9ecef;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endpush
