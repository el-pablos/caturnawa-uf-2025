@extends('layouts.simple')

@php
    $seoPage = 'home';
@endphp

@section('title', 'UNAS Fest 2025 - Festival Kompetisi Nasional')

@section('content')
<style>
    .modern-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .modern-title {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(45deg, #fff, #f8f9fa, #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        letter-spacing: -1px;
    }
    
    .modern-subtitle {
        font-size: 1.3rem;
        font-weight: 300;
        color: rgba(255,255,255,0.9);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        line-height: 1.6;
    }
    
    .modern-btn {
        background: linear-gradient(45deg, #ff6b6b, #feca57);
        border: none;
        border-radius: 50px;
        padding: 15px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px rgba(255,107,107,0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }
    
    .modern-btn:hover::before {
        left: 100%;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(255,107,107,0.4);
    }
    
    
    .modern-container {
        position: relative;
        z-index: 1;
    }

    /* Bubbles animation */
    .bubbles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }

    .bubbles li {
        position: absolute;
        list-style: none;
        display: block;
        width: 20px;
        height: 20px;
        background: rgba(255, 255, 255, 0.2);
        animation: animate-bubbles 25s linear infinite;
        bottom: -150px;
        border-radius: 50%;
    }

    .bubbles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
    .bubbles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
    .bubbles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
    .bubbles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
    .bubbles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
    .bubbles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
    .bubbles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
    .bubbles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
    .bubbles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
    .bubbles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

    @keyframes animate-bubbles {
        0% { transform: translateY(0) rotate(0deg); opacity: 1; }
        100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
    }
    @media (max-width: 768px) {
        .modern-title {
            font-size: 2.5rem;
        }

        .modern-subtitle {
            font-size: 1rem;
        }

        .floating-trophy {
            font-size: 3rem;
        }
    }
</style>

<!-- Hero Section -->
<div class="dynamic-bg"></div>
<div class="container my-5 modern-container">
    <div class="floating-shape"></div>
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                <div class="hero-content text-center">
                    <h1 class="modern-title mb-4">
                        UNAS<span style="background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"> FEST 2025</span></h1>
                    <p class="modern-subtitle mb-5">Festival Kompetisi Nasional Terbesar Indonesia</p>
                <hr class="my-4">
                <p>Bergabunglah dengan kompetisi Teknologi, Kesehatan, dan Biodiversitas menanti!</p>
                <a class="btn modern-btn btn-auto w-auto"
                   href="{{ route('public.competitions') }}"
                   role="button">
                    <i class="bi bi-trophy"></i> Lihat Kompetisi
                </a>
            </div>
        </div>
    </div>

    <!-- Leaderboard Section by Competition -->
    @if(isset($competitionLeaderboards) && count($competitionLeaderboards) > 0)
    <div class="row mt-5">
        <div class="col-12 mb-4">
            <div class="text-center" data-aos="fade-up">
                <h2 class="fw-bold text-primary">
                    <i class="bi bi-trophy me-2"></i>Leaderboard UNAS FEST 2025
                </h2>
                <p class="text-muted">Peringkat Tim Terbaik Per Kompetisi</p>
            </div>
        </div>

        @foreach($competitions as $compIndex => $competition)
            @if(isset($competitionLeaderboards[$competition->id]) && count($competitionLeaderboards[$competition->id]) > 0)
            <div class="col-lg-6 col-md-12 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 200 }}">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-trophy me-2"></i>{{ $competition->name }}
                        </h5>
                        <small>Top 4 Peringkat</small>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="100">Rank</th>
                                        <th>Tim</th>
                                        <th>Institusi</th>
                                        <th class="text-center" width="100">Victory Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($competitionLeaderboards[$competition->id] as $index => $team)
                                    <tr class="{{ $index < 3 ? 'table-warning' : '' }}">
                                        <td class="text-center">
                                            @if($index == 0)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="bi bi-trophy-fill"></i> 1st
                                                </span>
                                            @elseif($index == 1)
                                                <span class="badge bg-secondary fs-6">
                                                    <i class="bi bi-award-fill"></i> 2nd
                                                </span>
                                            @elseif($index == 2)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="bi bi-award"></i> 3rd
                                                </span>
                                            @elseif($index == 3)
                                                <span class="badge bg-info text-white fs-6">
                                                    <i class="bi bi-star"></i> Jury Mention
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $team['team_name'] }}</strong>
                                                @if($team['participants'] && count($team['participants']) > 0)
                                                    <br><small class="text-muted">{{ $team['participants'][0]['name'] ?? '' }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ $team['institution'] ?? 'Tidak ada' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">
                                                <i class="bi bi-star-fill"></i> {{ $team['total_victory_points'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('leaderboard.index', ['competition' => $competition->id]) }}"
                       class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Lihat Detail
                    </a>
                    <a href="{{ route('matalomba.show', $competition->slug) }}"
                       class="btn btn-outline-success btn-sm ms-2">
                        <i class="bi bi-trophy me-1"></i>Lihat Babak
                    </a>
                </div>
            </div>
        </div>
        @endif
        @endforeach

        <div class="col-12 text-center mt-3">
            <a href="{{ route('leaderboard.index') }}"
               class="btn btn-primary btn-lg">
                <i class="bi bi-trophy me-2"></i>Lihat Semua Leaderboard
            </a>
        </div>
    </div>
    @else
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="bi bi-trophy text-muted"
                       style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">Leaderboard Belum Tersedia</h4>
                    <p class="text-muted">Leaderboard akan ditampilkan setelah ada submission yang dinilai.</p>
                    <a href="{{ route('public.competitions') }}"
                       class="btn btn-primary">
                        <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

        <!-- Featured Competitions -->
        @if($competitions && $competitions->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h2 class="text-center mb-4">Kompetisi UnasFest</h2>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($competitions as $index => $competition)
                <div class="col">
                    <div class="card h-100 bg-white rounded-3 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-bold text-primary mb-0">{{ $competition->name ?? 'Competition' }}</h5>
                                @if(($competition->is_active ?? false) && $competition->registration_start && $competition->registration_end && now()->between($competition->registration_start, $competition->registration_end))
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($competition->registration_start && now()->lt($competition->registration_start))
                                    <span class="badge bg-warning">Belum Dibuka</span>
                                @elseif($competition->registration_end && now()->gt($competition->registration_end))
                                    <span class="badge bg-secondary">Ditutup</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </div>
                            <p class="card-text text-muted mb-3">{{ Str::limit($competition->description ?? 'No description available', 100) }}</p>
                            @if($competition->registration_start && $competition->registration_end)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-calendar text-primary me-1"></i>
                                    Pendaftaran: {{ $competition->registration_start->format('d M') }} - {{ $competition->registration_end->format('d M Y') }}
                                </small>
                            </div>
                            @endif
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-tag text-primary me-1"></i>
                                    {{ $competition->category ?? 'General' }}
                                </small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            @if($competition->slug)
                            <a href="{{ route('public.competition.detail', $competition->slug) }}"
                               class="btn btn-primary rounded-3 px-4 py-2">
                                Lihat Detail
                            </a>
                            @else
                            <span class="btn btn-secondary rounded-3 px-4 py-2 disabled">
                                Detail Tidak Tersedia
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="row mt-5">
                <div class="col-12">
                    <h2 class="text-center mb-4">Kompetisi UnasFest</h2>
                    <div class="text-center">
                        <div class="alert alert-info d-inline-block">
                            <i class="bi bi-info-circle me-2"></i>
                            Belum ada kompetisi yang tersedia saat ini. Silakan cek kembali nanti!
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
