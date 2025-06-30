@extends('layouts.simple')

@section('title', 'Kompetisi - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5">
                <h1 class="display-4">Kompetisi UNAS Fest 2025</h1>
                <p class="lead">Bergabunglah dengan kompetisi nasional terbesar di Indonesia</p>
                <hr class="my-4">
                <p>Tiga kategori kompetisi: Teknologi, Kesehatan, dan Biodiversitas dengan total hadiah 500 Juta Rupiah!</p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-5">
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $stats['participants'] ?? 0 }}</h3>
                    <p class="text-muted">Peserta Terdaftar</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-trophy-fill text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $stats['competitions'] ?? 0 }}</h3>
                    <p class="text-muted">Kompetisi Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-building text-success" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $stats['universities'] ?? 0 }}</h3>
                    <p class="text-muted">Universitas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-currency-dollar text-info" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">500M</h3>
                    <p class="text-muted">Total Hadiah</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Competitions by Category -->
    @if($competitions && $competitions->count() > 0)
        @foreach($competitions as $category => $categoryCompetitions)
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-4">
                    <i class="bi bi-trophy text-warning"></i> 
                    Kategori {{ ucfirst($category) }}
                </h2>
            </div>
            @foreach($categoryCompetitions as $competition)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $competition->name }}</h5>
                        <p class="card-text">{{ Str::limit($competition->description, 100) }}</p>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> 
                                Pendaftaran: {{ $competition->registration_start->format('d M') }} - {{ $competition->registration_end->format('d M Y') }}
                            </small>
                        </div>
                        <div class="mb-2">
                            <small class="text-success">
                                <i class="bi bi-currency-dollar"></i> 
                                Hadiah: Rp {{ number_format($competition->prize_pool ?? 0, 0, ',', '.') }}
                            </small>
                        </div>
                        <div class="mb-2">
                            <small class="text-info">
                                <i class="bi bi-people"></i> 
                                {{ $competition->registrations->count() }} peserta terdaftar
                            </small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('public.competition.detail', $competition->slug) }}" class="btn btn-primary">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                        @if($competition->registration_start <= now() && $competition->registration_end >= now())
                            <a href="{{ route('register') }}" class="btn btn-success ms-2">
                                <i class="bi bi-person-plus"></i> Daftar
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    @else
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i>
                    <h4>Belum Ada Kompetisi Aktif</h4>
                    <p>Kompetisi akan segera dibuka. Pantau terus website ini untuk informasi terbaru!</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
