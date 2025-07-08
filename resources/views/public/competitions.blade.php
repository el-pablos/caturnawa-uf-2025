@extends('layouts.simple')

@php
    $seoPage = 'competitions';
@endphp

@section('title', 'Kompetisi - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5">
                <div class="text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-trophy me-3"></i>Kompetisi UNAS Fest 2025
                    </h1>
                    <p class="lead mb-4">
                        Bergabunglah dengan kompetisi nasional terbesar di Indonesia. Tunjukkan inovasi terbaikmu 
                        dalam berbagai bidang yang akan membentuk masa depan Indonesia.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('login') }}" class="btn btn-warning btn-lg w-100">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.about') }}" class="btn btn-light btn-lg w-100">
                                <i class="bi bi-info-circle me-2"></i>Tentang Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">
                <i class="bi bi-graph-up text-info"></i> 
                UNAS Fest dalam Angka
            </h2>
        </div>
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white text-center">
                    <h3 class="card-title mb-0">Statistik Kompetisi</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center justify-content-center">
                        <div class="col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <i class="bi bi-trophy-fill text-success mb-2" style="font-size: 2rem;"></i>
                                    <h3 class="text-success">{{ $stats['active_competitions'] ?? '0' }}</h3>
                                    <p class="text-muted mb-0">Kompetisi Aktif</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Competitions List -->
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4">
                <i class="bi bi-list-task text-primary"></i> 
                Daftar Kompetisi
            </h2>
        </div>
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="card-title mb-0">Semua Kompetisi UNAS Fest 2025</h3>
                    <p class="mb-0">Pilih kompetisi yang sesuai dengan minat dan keahlianmu</p>
                </div>
                <div class="card-body">
                    @forelse($competitions as $competition)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-start border-primary border-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4 class="text-primary mb-2">{{ $competition->name }}</h4>
                                                <p class="text-muted mb-3">{{ Str::limit($competition->description ?? 'Kompetisi inovatif yang menantang kreativitas dan kemampuan peserta.', 150) }}</p>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar3 me-1"></i>
                                                            <strong>Pendaftaran:</strong> {{ $competition->registration_start ? \Carbon\Carbon::parse($competition->registration_start)->format('d M Y') : 'TBA' }} - {{ $competition->registration_end ? \Carbon\Carbon::parse($competition->registration_end)->format('d M Y') : 'TBA' }}
                                                        </small>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <small class="text-muted">
                                                            <i class="bi bi-people me-1"></i>
                                                            <strong>Tim:</strong> {{ $competition->max_team_members ?? 'Maksimal 3' }} orang
                                                        </small>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <small class="text-success">
                                                            <i class="bi bi-trophy me-1"></i>
                                                            <strong>Hadiah:</strong> {{ $competition->prize_amount ? 'Rp ' . number_format($competition->prize_amount, 0, ',', '.') : 'Sertifikat & Hadiah Menarik' }}
                                                        </small>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <small class="text-info">
                                                            <i class="bi bi-people-fill me-1"></i>
                                                            <strong>Peserta:</strong> {{ $competition->registrations->count() ?? 0 }} terdaftar
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <div class="mb-3">
                                                    @if($competition->registration_start > now())
                                                        <span class="badge bg-warning fs-6">Segera Dibuka</span>
                                                    @elseif($competition->registration_end < now())
                                                        <span class="badge bg-danger fs-6">Pendaftaran Ditutup</span>
                                                    @else
                                                        <span class="badge bg-success fs-6">Pendaftaran Dibuka</span>
                                                    @endif
                                                </div>
                                                
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('public.competition.detail', $competition->slug) }}" class="btn btn-outline-primary">
                                                        <i class="bi bi-eye me-1"></i>Lihat Detail
                                                    </a>
                                                    @if($competition->registration_start <= now() && $competition->registration_end >= now())
                                                        <a href="{{ route('login') }}" class="btn btn-primary">
                                                            <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-trophy text-muted mb-3" style="font-size: 4rem;"></i>
                            <h3 class="text-muted">Belum Ada Kompetisi</h3>
                            <p class="text-muted">Kompetisi akan segera dibuka. Pantau terus website kami untuk informasi terbaru!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($competitions->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $competitions->links() }}
            </div>
        </div>
    @endif

    <!-- Call to Action Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center mb-4">
                <i class="bi bi-rocket text-warning"></i> 
                Siap Menunjukkan Inovasimu?
            </h2>
        </div>
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-white text-center">
                    <h3 class="card-title mb-0">Bergabunglah dengan UNAS Fest 2025</h3>
                    <p class="mb-0">Jangan lewatkan kesempatan emas untuk menunjukkan kemampuan terbaikmu</p>
                </div>
                <div class="card-body text-center">
                    <p class="lead mb-4">
                        Bergabunglah dengan ribuan peserta lainnya dan wujudkan ide terbaikmu di UNAS Fest 2025!
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.contact') }}" class="btn btn-outline-primary btn-lg w-100">
                                <i class="bi bi-envelope me-2"></i>Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
