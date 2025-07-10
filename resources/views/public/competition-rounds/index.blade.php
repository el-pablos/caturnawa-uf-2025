@extends('layouts.public')

@section('title', 'Mata Lomba - UNAS Fest 2025')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold text-primary mb-3">
                <i class="bi bi-trophy-fill me-3"></i>Mata Lomba UNAS Fest 2025
            </h1>
            <p class="lead text-muted">
                Pilih kompetisi dan lihat babak-babak yang tersedia
            </p>
        </div>
    </div>

    <!-- Competitions Grid -->
    <div class="row">
        @forelse($competitions as $competition)
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 competition-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-award me-2"></i>{{ $competition->name }}
                    </h5>
                    <small class="opacity-75">
                        {{ \App\Models\Competition::CATEGORIES[$competition->category] ?? $competition->category }}
                    </small>
                </div>
                
                <div class="card-body">
                    <p class="card-text text-muted mb-3">
                        {{ Str::limit($competition->description, 100) }}
                    </p>
                    
                    <!-- Competition Stats -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="text-center">
                                <div class="h5 text-success mb-0">{{ $competition->rounds->count() }}</div>
                                <small class="text-muted">Babak Kompetisi</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Available Rounds -->
                    @if($competition->rounds->count() > 0)
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Babak Tersedia:</h6>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($competition->rounds as $round)
                            <span class="badge bg-light text-dark border">
                                {{ ucfirst($round->round_type) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="card-footer bg-transparent border-0">
                    <div class="d-grid gap-2">
                        <a href="{{ route('matalomba.show', $competition->slug) }}" 
                           class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i>Lihat Detail Babak
                        </a>
                        <a href="{{ route('public.competition.detail', $competition->slug) }}" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-info-circle me-2"></i>Info Kompetisi
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-trophy text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">Belum Ada Kompetisi</h4>
                    <p class="text-muted">Kompetisi akan segera tersedia. Silakan cek kembali nanti.</p>
                    <a href="{{ route('public.home') }}" class="btn btn-primary">
                        <i class="bi bi-house me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Quick Navigation -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Navigasi Cepat</h5>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('public.competitions') }}" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul me-2"></i>Semua Kompetisi
                        </a>
                        <a href="{{ route('leaderboard.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-trophy me-2"></i>Leaderboard
                        </a>
                        <a href="{{ route('public.home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-2"></i>Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.competition-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.competition-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.btn {
    border-radius: 8px;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endsection
