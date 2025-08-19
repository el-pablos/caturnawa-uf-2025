@extends('layouts.app')

@section('title', $competition->name . ' - ' . $round->name)

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-2">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('competitions.index') }}">Kompetisi</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('competitions.rounds.index', $competition) }}">{{ $competition->name }}</a></li>
                                    <li class="breadcrumb-item active">{{ $round->name }}</li>
                                </ol>
                            </nav>
                            <h1 class="h3 mb-1 fw-bold text-primary">{{ $round->name }}</h1>
                            <p class="text-muted mb-0">{{ $round->description }}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column align-items-md-end">
                                <span class="badge bg-{{ $round->status == 'completed' ? 'success' : ($round->status == 'ongoing' ? 'warning' : 'secondary') }} mb-2">
                                    {{ $round->status_name }}
                                </span>
                                @if($round->start_date)
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $round->start_date->format('d M Y H:i') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Round Information -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Informasi {{ $round->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Detail Babak</h6>
                            <ul class="list-unstyled">
                                <li><strong>Tipe:</strong> {{ $round->round_type_name }}</li>
                                <li><strong>Nomor Babak:</strong> {{ $round->round_number }}</li>
                                <li><strong>Status:</strong> 
                                    <span class="badge bg-{{ $round->status == 'completed' ? 'success' : ($round->status == 'ongoing' ? 'warning' : 'secondary') }}">
                                        {{ $round->status_name }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Jadwal</h6>
                            <ul class="list-unstyled">
                                @if($round->start_date)
                                <li><strong>Mulai:</strong> {{ $round->start_date->format('d M Y H:i') }}</li>
                                @endif
                                @if($round->end_date)
                                <li><strong>Selesai:</strong> {{ $round->end_date->format('d M Y H:i') }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
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
                            <h4 class="text-primary">{{ $participants->count() }}</h4>
                            <small class="text-muted">Peserta</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $scores->count() }}</h4>
                            <small class="text-muted">Penilaian</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    @if($rankings->isNotEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-trophy me-2"></i>
                                Hasil {{ $round->name }}
                            </h5>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i>
                                Cetak
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">Rank</th>
                                    <th>Nama Tim</th>
                                    <th>Ketua Tim</th>
                                    <th>Asal Institusi</th>
                                    <th width="120">Total Skor</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rankings as $ranking)
                                <tr class="{{ $ranking['rank'] <= 3 ? 'table-warning' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($ranking['rank'] == 1)
                                                <i class="bi bi-trophy-fill text-warning me-2"></i>
                                            @elseif($ranking['rank'] == 2)
                                                <i class="bi bi-award-fill text-secondary me-2"></i>
                                            @elseif($ranking['rank'] == 3)
                                                <i class="bi bi-award-fill text-warning me-2"></i>
                                            @endif
                                            <strong>{{ $ranking['rank'] }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $ranking['registration']->team_name ?? $ranking['registration']->user->name }}</strong>
                                    </td>
                                    <td>{{ $ranking['registration']->user->name }}</td>
                                    <td>{{ $ranking['registration']->user->university ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary fs-6">
                                            {{ number_format($ranking['total_score'], 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('competitions.rounds.participant-detail', [$competition, $round, $ranking['registration']]) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard-data text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Belum Ada Hasil</h5>
                    <p class="text-muted">Hasil akan ditampilkan setelah penilaian selesai.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Navigation -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('competitions.rounds.index', $competition) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali ke Daftar Babak
                </a>
                
                @if($round->round_type == 'final' && $round->status == 'completed')
                <a href="{{ route('competitions.rounds.final-results', $competition) }}" class="btn btn-success">
                    <i class="bi bi-trophy me-1"></i>
                    Lihat Hasil Final
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

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
