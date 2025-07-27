@extends('layouts.simple')

@section('title', 'Kompetisi - UNAS Fest 2025')

@push('styles')
<style>
    .modern-hero {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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
            font-size: 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="container my-5 modern-container">
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                <div class="hero-content text-center">
                    <h1 class="modern-title mb-4">
                        KOMPETISI<span style="background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"> UNAS FEST 2025</span>
                    </h1>
                    <p class="modern-subtitle mb-5">Bergabunglah dengan kompetisi nasional terbesar di Indonesia</p>
                    <hr class="my-4">
                    <p>Tiga kategori kompetisi: Teknologi, Kesehatan, dan Biodiversitas menanti!</p>
                    <a class="btn modern-btn btn-auto w-auto"
                       href="#competitions-list"
                       role="button">
                        <i class="bi bi-trophy"></i> Lihat Kompetisi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mt-5 mb-5">
        <div class="col-12 mb-4">
            <div class="text-center" data-aos="fade-up">
                <h2 class="fw-bold text-primary">
                    <i class="bi bi-graph-up me-2"></i>Statistik UNAS FEST 2025
                </h2>
                <p class="text-muted">Data Terkini Festival Kompetisi Nasional</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card shadow h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                    <h3 class="mt-3 fw-bold text-primary">{{ $stats['total_participants'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Peserta Terdaftar</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card shadow h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-trophy-fill text-warning" style="font-size: 3rem;"></i>
                    <h3 class="mt-3 fw-bold text-warning">{{ $stats['total_competitions'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Kompetisi Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card shadow h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-currency-dollar text-success" style="font-size: 3rem;"></i>
                    <h3 class="mt-3 fw-bold text-success">Rp {{ number_format($stats['total_prizes'] ?? 0, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Total Hadiah</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Competitions List -->
    @if($competitions && $competitions->count() > 0)
        <div class="row mt-5" id="competitions-list">
            <div class="col-12 mb-4">
                <div class="text-center" data-aos="fade-up">
                    <h2 class="fw-bold text-primary">
                        <i class="bi bi-trophy me-2"></i>Kompetisi Tersedia
                    </h2>
                    <p class="text-muted">Pilih kompetisi yang sesuai dengan minat dan keahlian Anda</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($competitions as $competition)
            <div class="col-lg-6 col-md-12 mb-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 150 }}">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-trophy me-2"></i>{{ $competition->name }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ Str::limit($competition->description, 120) }}</p>

                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="border-end">
                                    <i class="bi bi-calendar text-primary d-block mb-1"></i>
                                    <small class="text-muted">Pendaftaran</small>
                                    <div class="fw-bold small">{{ $competition->registration_start->format('d M') }} - {{ $competition->registration_end->format('d M') }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <i class="bi bi-currency-dollar text-success d-block mb-1"></i>
                                    <small class="text-muted">Hadiah</small>
                                    <div class="fw-bold small text-success">Rp {{ number_format($competition->prize_amount ?? 0, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-people text-info d-block mb-1"></i>
                                <small class="text-muted">Peserta</small>
                                <div class="fw-bold small text-info">{{ $competition->registrations->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="{{ route('public.competition.detail', $competition->slug) }}" class="btn btn-outline-primary">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                            @if($competition->registration_start <= now() && $competition->registration_end >= now())
                                <a href="{{ route('register') }}" class="btn btn-success">
                                    <i class="bi bi-person-plus"></i> Daftar Sekarang
                                </a>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="bi bi-clock"></i>
                                    @if($competition->registration_start > now())
                                        Belum Dibuka
                                    @else
                                        Pendaftaran Ditutup
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $competitions->links() }}
            </div>
        </div>
    @else
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-trophy text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">Belum Ada Kompetisi</h4>
                        <p class="text-muted mb-4">Kompetisi akan segera dibuka. Pantau terus website ini untuk informasi terbaru!</p>
                        <a href="{{ route('public.home') }}" class="btn modern-btn">
                            <i class="bi bi-house"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
