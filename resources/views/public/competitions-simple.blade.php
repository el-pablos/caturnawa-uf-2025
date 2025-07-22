@extends('layouts.simple')

@section('title', 'Kompetisi - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5"
                 data-aos="fade-up"
                 data-aos-duration="1000"
                 data-aos-easing="ease-out-cubic">
                <h1 class="display-4"
                    data-aos="fade-up"
                    data-aos-delay="200"
                    data-aos-duration="800">Kompetisi UNAS Fest 2025</h1>
                <p class="lead"
                   data-aos="fade-up"
                   data-aos-delay="400"
                   data-aos-duration="800">Bergabunglah dengan kompetisi nasional terbesar di Indonesia</p>
                <hr class="my-4"
                    data-aos="fade-left"
                    data-aos-delay="600"
                    data-aos-duration="600">
                <p data-aos="fade-up"
                   data-aos-delay="800"
                   data-aos-duration="800">Tiga kategori kompetisi: Teknologi, Kesehatan, dan Biodiversitas. Bergabunglah dalam festival kompetisi nasional terbesar!</p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card text-center"
                 data-aos="zoom-in"
                 data-aos-duration="800"
                 data-aos-delay="100"
                 data-aos-easing="ease-out-back">
                <div class="card-body">
                    <i class="bi bi-people-fill text-primary"
                       style="font-size: 2rem;"
                       data-aos="flip-up"
                       data-aos-delay="300"
                       data-aos-duration="600"></i>
                    <h3 class="mt-2"
                        data-aos="fade-up"
                        data-aos-delay="500"
                        data-aos-duration="600">{{ $stats['participants'] ?? 0 }}</h3>
                    <p class="text-muted"
                       data-aos="fade-up"
                       data-aos-delay="700"
                       data-aos-duration="600">Peserta Terdaftar</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card text-center"
                 data-aos="zoom-in"
                 data-aos-duration="800"
                 data-aos-delay="300"
                 data-aos-easing="ease-out-back">
                <div class="card-body">
                    <i class="bi bi-trophy-fill text-warning"
                       style="font-size: 2rem;"
                       data-aos="flip-up"
                       data-aos-delay="500"
                       data-aos-duration="600"></i>
                    <h3 class="mt-2"
                        data-aos="fade-up"
                        data-aos-delay="700"
                        data-aos-duration="600">{{ $stats['competitions'] ?? 0 }}</h3>
                    <p class="text-muted"
                       data-aos="fade-up"
                       data-aos-delay="900"
                       data-aos-duration="600">Kompetisi Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Competitions by Category -->
    @if($competitions && $competitions->count() > 0)
        @foreach($competitions as $category => $categoryCompetitions)
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-4"
                    data-aos="fade-right"
                    data-aos-duration="800"
                    data-aos-easing="ease-out-cubic">
                    <i class="bi bi-trophy text-warning"
                       data-aos="bounce"
                       data-aos-delay="200"
                       data-aos-duration="600"></i>
                    {{ \App\Models\Competition::CATEGORIES[$category] ?? ucfirst($category) }}
                </h2>
            </div>
            @foreach($categoryCompetitions as $index => $competition)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100"
                     data-aos="fade-up"
                     data-aos-duration="800"
                     data-aos-delay="{{ ($index * 150) + 100 }}"
                     data-aos-easing="ease-out-back">
                    <div class="card-body">
                        <h5 class="card-title"
                            data-aos="fade-up"
                            data-aos-delay="{{ ($index * 150) + 300 }}"
                            data-aos-duration="600">{{ $competition->name }}</h5>
                        <p class="card-text"
                           data-aos="fade-up"
                           data-aos-delay="{{ ($index * 150) + 400 }}"
                           data-aos-duration="600">{{ Str::limit($competition->description, 100) }}</p>
                        <div class="mb-2"
                             data-aos="fade-left"
                             data-aos-delay="{{ ($index * 150) + 500 }}"
                             data-aos-duration="500">
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i>
                                Pendaftaran: {{ $competition->registration_start->format('d M') }} - {{ $competition->registration_end->format('d M Y') }}
                            </small>
                        </div>
                        <div class="mb-2"
                             data-aos="fade-left"
                             data-aos-delay="{{ ($index * 150) + 600 }}"
                             data-aos-duration="500">
                            <small class="text-success">
                                <i class="bi bi-currency-dollar"></i>
                                Hadiah: Rp {{ number_format($competition->prize_pool ?? 0, 0, ',', '.') }}
                            </small>
                        </div>
                        <div class="mb-2"
                             data-aos="fade-left"
                             data-aos-delay="{{ ($index * 150) + 700 }}"
                             data-aos-duration="500">
                            <small class="text-info">
                                <i class="bi bi-people"></i>
                                {{ $competition->registrations->count() }} peserta terdaftar
                            </small>
                        </div>
                    </div>
                    <div class="card-footer"
                         data-aos="fade-up"
                         data-aos-delay="{{ ($index * 150) + 800 }}"
                         data-aos-duration="600">
                        <a href="{{ route('public.competition.detail', $competition->slug) }}"
                           class="btn btn-primary"
                           data-aos="zoom-in"
                           data-aos-delay="{{ ($index * 150) + 900 }}"
                           data-aos-duration="400">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                        @if($competition->registration_start <= now() && $competition->registration_end >= now())
                            <a href="{{ route('register') }}"
                               class="btn btn-success ms-2"
                               data-aos="zoom-in"
                               data-aos-delay="{{ ($index * 150) + 1000 }}"
                               data-aos-duration="400">
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
