@extends('layouts.app')

@section('title', 'Detail ' . ($registration->team_name ?? $registration->user->name))

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('competitions.index') }}">Kompetisi</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('competitions.rounds.index', $competition) }}">{{ $competition->name }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('competitions.rounds.show', [$competition, $round]) }}">{{ $round->name }}</a></li>
                            <li class="breadcrumb-item active">{{ $registration->team_name ?? $registration->user->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-1 fw-bold text-primary">{{ $registration->team_name ?? $registration->user->name }}</h1>
                    <p class="text-muted mb-0">Detail peserta {{ $round->name }} - {{ $competition->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Team Information -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>
                        Informasi Tim
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-people-fill text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-center mb-3">{{ $registration->team_name ?? $registration->user->name }}</h6>
                    
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Ketua Tim:</strong><br>
                            {{ $registration->user->name }}
                        </li>
                        <li class="mb-2">
                            <strong>Email:</strong><br>
                            {{ $registration->user->email }}
                        </li>
                        <li class="mb-2">
                            <strong>Institusi:</strong><br>
                            {{ $registration->user->university ?? 'N/A' }}
                        </li>
                        <li class="mb-2">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $registration->status == 'confirmed' ? 'success' : 'warning' }}">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </li>
                        <li class="mb-2">
                            <strong>Tanggal Daftar:</strong><br>
                            {{ $registration->created_at->format('d M Y H:i') }}
                        </li>
                    </ul>

                    @if($registration->teamMembers && $registration->teamMembers->count() > 0)
                    <hr>
                    <h6 class="fw-bold">Anggota Tim</h6>
                    @foreach($registration->teamMembers as $member)
                    <div class="mb-2">
                        <strong>{{ $member->name }}</strong><br>
                        <small class="text-muted">{{ $member->email }}</small>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>
                        Statistik
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $participantScores->count() }}</h4>
                            <small class="text-muted">Penilaian</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $participantScores->avg('score') ? number_format($participantScores->avg('score'), 2) : '0.00' }}</h4>
                            <small class="text-muted">Rata-rata</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scores and Details -->
        <div class="col-md-8">
            <!-- Scores Table -->
            @if($participantScores->isNotEmpty())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard-data me-2"></i>
                        Detail Penilaian {{ $round->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Juri</th>
                                    <th>Kriteria</th>
                                    <th>Skor</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($participantScores as $score)
                                <tr>
                                    <td>
                                        <strong>{{ $score->judge->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $score->criteria->name ?? $score->criteria_type ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $score->score }}</span>
                                    </td>
                                    <td>{{ $score->comments ?? '-' }}</td>
                                    <td>{{ $score->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2">Total / Rata-rata</th>
                                    <th>
                                        <span class="badge bg-success fs-6">
                                            {{ number_format($participantScores->avg('score'), 2) }}
                                        </span>
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Submission Details -->
            @if($submission)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-earmark me-2"></i>
                        Karya yang Dikumpulkan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Karya</h6>
                            <ul class="list-unstyled">
                                <li><strong>Judul:</strong> {{ $submission->title ?? 'N/A' }}</li>
                                <li><strong>Deskripsi:</strong> {{ $submission->description ?? 'N/A' }}</li>
                                <li><strong>Tanggal Submit:</strong> {{ $submission->created_at->format('d M Y H:i') }}</li>
                                <li><strong>Status:</strong> 
                                    <span class="badge bg-{{ $submission->status == 'approved' ? 'success' : 'warning' }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            @if($submission->files && $submission->files->count() > 0)
                            <h6 class="fw-bold">File yang Dikumpulkan</h6>
                            @foreach($submission->files as $file)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-download me-1"></i>
                                    {{ $file->original_name }}
                                </a>
                                <small class="text-muted d-block">{{ $file->file_type }} - {{ $file->file_size }}</small>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>

                    @if($submission->comments && $submission->comments->count() > 0)
                    <hr>
                    <h6 class="fw-bold">Komentar Juri</h6>
                    @foreach($submission->comments as $comment)
                    <div class="border rounded p-3 mb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ $comment->judge->name ?? 'Juri' }}</strong>
                                <small class="text-muted">{{ $comment->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                        <p class="mb-0 mt-2">{{ $comment->comment }}</p>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            @endif

            <!-- No Data Message -->
            @if($participantScores->isEmpty() && !$submission)
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Belum Ada Data</h5>
                    <p class="text-muted">Penilaian dan karya akan ditampilkan setelah tersedia.</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('competitions.rounds.show', [$competition, $round]) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali ke {{ $round->name }}
                </a>
                
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>
                    Cetak Detail
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .btn, .breadcrumb, nav {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
@endsection
