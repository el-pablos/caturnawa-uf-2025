@extends('layouts.simple')

@section('title', 'UNAS Fest 2025 - Festival Kompetisi Nasional')

@section('content')
<div class="container my-5" data-aos="fade-up" data-aos-duration="800">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5">
                <h1 class="display-4">UNAS Fest 2025</h1>
                <p class="lead">Festival Kompetisi Nasional Terbesar Indonesia</p>
                <hr class="my-4">
                <p>Bergabunglah dengan kompetisi Teknologi, Kesehatan, dan Biodiversitas menanti!</p>
                <a class="btn btn-warning btn-lg text-light" href="{{ route('public.competitions') }}" role="button">
                    <i class="bi bi-trophy"></i> Lihat Kompetisi
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mt-5" data-aos="fade-up" data-aos-duration="800">
        <div class="col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $stats['participants'] ?? 0 }}</h3>
                    <p class="text-muted">Peserta</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-trophy-fill text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ $stats['competitions'] ?? 0 }}</h3>
                    <p class="text-muted">Kompetisi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Competitions -->
    @if($competitions && $competitions->count() > 0)
    <div class="row mt-5" data-aos="fade-up" data-aos-duration="800">
        <div class="col-12">
            <h2 class="text-center mb-4">Kompetisi Unggulan</h2>
        </div>
        @foreach($competitions as $competition)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $competition->name }}</h5>
                    <p class="card-text">{{ Str::limit($competition->description, 100) }}</p>
                    <p class="text-muted">
                        <i class="bi bi-calendar"></i> 
                        {{ $competition->registration_end->format('d M Y') }}
                    </p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('public.competition.detail', $competition->slug) }}" class="btn btn-primary">
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
