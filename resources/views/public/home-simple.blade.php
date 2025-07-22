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
    
    .modern-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="50" cy="10" r="0.8" fill="rgba(255,255,255,0.12)"/><circle cx="10" cy="60" r="1.2" fill="rgba(255,255,255,0.06)"/><circle cx="90" cy="30" r="0.9" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
        animation: float 20s ease-in-out infinite;
    }
    
    .modern-hero::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 30s linear infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(255,107,107,0.4);
    }
    
    .dynamic-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background: linear-gradient(45deg, #f8f9fa 0%, #e9ecef 100%);
        overflow: hidden;
    }
    
    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
    }
    
    .modern-container {
        position: relative;
        z-index: 1;
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
</style>

<div class="dynamic-bg"></div>
<div class="container my-5 modern-container">
    <div class="floating-shape"></div>
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5">
                <div class="hero-content text-center">
                    <h1 class="modern-title mb-4">
                        UNAS<span style="background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"> FEST 2025</span></h1>
                    <p class="modern-subtitle mb-5">Festival Kompetisi Nasional Terbesar Indonesia</p>
                <hr class="my-4">
                <p>Bergabunglah dengan kompetisi Teknologi, Kesehatan, dan Biodiversitas menanti!</p>
                <a class="btn modern-btn btn-lg w-auto mt-3"
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
            <div class="text-center">
                <h2 class="fw-bold text-primary">
                    <i class="bi bi-trophy me-2"></i>Leaderboard UNAS Fest 2025
                </h2>
                <p class="text-muted">Peringkat Tim Terbaik Per Kompetisi</p>
            </div>
        </div>

        @foreach($competitions as $compIndex => $competition)
            @if(isset($competitionLeaderboards[$competition->id]) && count($competitionLeaderboards[$competition->id]) > 0)
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-trophy me-2"></i>{{ $competition->name }}
                        </h5>
                        <small data-aos="fade-up">Top 4 Peringkat</small>
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
                    <a href="{{ route('matalomba.show', $competition->slug) }}">
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
            <div class="card shadow"
                 data-aos="zoom-in">
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
                @foreach($competitions as $index => $competition)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 bg-white rounded-3 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold text-primary">{{ $competition->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($competition->description, 100) }}</p>
                            <p class="text-muted">
                                <i class="bi bi-calendar text-primary"></i>
                                {{ $competition->registration_end->format('d M Y') }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="{{ route('public.competition.detail', $competition->slug) }}"
                            class="btn btn-primary rounded-3 px-4 py-2">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection