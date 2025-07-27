@extends('layouts.simple')

@section('title', 'Kompetisi - UNAS Fest 2025')

@push('styles')
<style>
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

    .hero-section {
        position: relative;
        overflow: hidden;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5 hero-section" data-aos="fade-up">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                <div class="hero-content">
                    <h1 class="display-4" data-aos="zoom-in" data-aos-delay="200">Kompetisi UNAS Fest 2025</h1>
                    <p class="lead" data-aos="fade-up" data-aos-delay="400">Bergabunglah dengan kompetisi nasional terbesar di Indonesia</p>
                    <hr class="my-4" data-aos="fade-up" data-aos-delay="600">
                    <p data-aos="fade-up" data-aos-delay="800">Tiga kategori kompetisi: Teknologi, Kesehatan, dan Biodiversitas. Bergabunglah dalam festival kompetisi nasional terbesar!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4" data-aos="fade-up">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $stats['total_participants'] ?? 0 }}</h3>
                    <p class="text-muted">Peserta Terdaftar</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-trophy-fill text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $stats['total_competitions'] ?? 0 }}</h3>
                    <p class="text-muted">Kompetisi Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Competitions List -->
    @if($competitions && $competitions->count() > 0)
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-4" data-aos="fade-up">
                    <i class="bi bi-trophy text-warning"></i>
                    Kompetisi Tersedia
                </h2>
            </div>
        </div>
        <div class="row">
            @foreach($competitions as $competition)
            <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 6) * 100 }}">
                <div class="card h-100 shadow-sm">
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
                                Hadiah: Rp {{ number_format($competition->prize_amount ?? 0, 0, ',', '.') }}
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

        <!-- Pagination -->
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $competitions->links() }}
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center"
                     data-aos="fade-up"
                     data-aos-duration="1000"
                     data-aos-easing="ease-out-back">
                    <i class="bi bi-info-circle"
                       data-aos="bounce"
                       data-aos-delay="200"
                       data-aos-duration="800"></i>
                    <h4 data-aos="fade-up"
                        data-aos-delay="400"
                        data-aos-duration="600">Belum Ada Kompetisi Aktif</h4>
                    <p data-aos="fade-up"
                       data-aos-delay="600"
                       data-aos-duration="600">Kompetisi akan segera dibuka. Pantau terus website ini untuk informasi terbaru!</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
