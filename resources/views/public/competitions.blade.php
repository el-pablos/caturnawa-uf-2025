@extends('layouts.simple')

@php
    $seoPage = 'competitions';
@endphp

@section('title', 'Kompetisi - UNAS Fest 2025')

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
    
    .modern-btn-outline {
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 13px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white;
        transition: all 0.3s ease;
    }
    
    .modern-btn-outline:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255,255,255,0.2);
        color: white;
    }
    
    .floating-trophy {
        font-size: 4rem;
        animation: floatTrophy 3s ease-in-out infinite;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
    }
    
    @keyframes floatTrophy {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    }
    
    .glass-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .glass-header {
        background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255,255,255,0.1);
        position: relative;
    }
    
    .glass-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    }
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    .stats-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 25px 50px rgba(102, 126, 234, 0.4);
    }
    
    .stats-inner-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stats-inner-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
        transition: left 0.5s;
    }
    
    .stats-inner-card:hover::before {
        left: 100%;
    }
    
    .competition-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .competition-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #667eea, #764ba2);
        transition: width 0.3s ease;
    }
    
    .competition-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .competition-card:hover::before {
        width: 8px;
    }
    
    .modern-badge {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        border-radius: 20px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        animation: pulse 2s infinite;
    }
    
    .modern-badge.bg-success {
        background: linear-gradient(45deg, #48bb78, #38a169);
        box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
    }
    
    .modern-badge.bg-warning {
        background: linear-gradient(45deg, #ed8936, #dd6b20);
        box-shadow: 0 4px 15px rgba(237, 137, 54, 0.3);
    }
    
    .modern-badge.bg-danger {
        background: linear-gradient(45deg, #f56565, #e53e3e);
        box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
    }
    
    .cta-card {
        background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(253, 203, 110, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .cta-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        animation: rotate 25s linear infinite reverse;
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
    
    .dynamic-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
            radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(255, 235, 167, 0.1) 0%, transparent 50%);
        animation: float 20s ease-in-out infinite;
    }
    
    .dynamic-bg::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
            linear-gradient(45deg, transparent 40%, rgba(102, 126, 234, 0.05) 50%, transparent 60%),
            linear-gradient(-45deg, transparent 40%, rgba(118, 75, 162, 0.05) 50%, transparent 60%);
        animation: slide 30s linear infinite;
    }
    
    @keyframes slide {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
    }
    
    .floating-shapes::before,
    .floating-shapes::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(102, 126, 234, 0.1);
        animation: floatShapes 15s ease-in-out infinite;
    }
    
    .floating-shapes::before {
        width: 100px;
        height: 100px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .floating-shapes::after {
        width: 150px;
        height: 150px;
        top: 60%;
        right: 10%;
        background: rgba(118, 75, 162, 0.1);
        animation-delay: 7s;
    }
    
    @keyframes floatShapes {
        0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
        50% { transform: translateY(-30px) rotate(180deg); opacity: 0.6; }
    }
    
    .modern-container {
        position: relative;
        z-index: 1;
    }
</style>

<div class="dynamic-bg"></div>
<div class="container my-5 modern-container">
    <div class="floating-shapes"></div>
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5 mb-5"
                 data-aos="zoom-in"
                 data-aos-duration="1200"
                 data-aos-easing="ease-out-back">
                <div class="hero-content text-center">
                    <div class="floating-trophy mb-4"
                         data-aos="bounce"
                         data-aos-delay="300"
                         data-aos-duration="800">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h1 class="modern-title mb-4"
                        data-aos="fade-down"
                        data-aos-delay="500"
                        data-aos-duration="800"
                        data-aos-easing="ease-out-cubic">
                        Kompetisi<span style="background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"> UNAS Fest 2025</span>
                    </h1>
                    <p class="modern-subtitle mb-5"
                       data-aos="fade-up"
                       data-aos-delay="700"
                       data-aos-duration="800">
                        Bergabunglah dengan kompetisi nasional terbesar di Indonesia.<br>
                        Tunjukkan inovasi terbaikmu dalam berbagai bidang yang akan membentuk masa depan Indonesia.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3"
                             data-aos="slide-right"
                             data-aos-delay="900"
                             data-aos-duration="600">
                            <a href="{{ route('login') }}"
                               class="btn modern-btn btn-lg w-100"
                               data-aos="pulse"
                               data-aos-delay="1100"
                               data-aos-duration="600">
                                <i class="bi bi-person-plus me-2"
                                   data-aos="flip-left"
                                   data-aos-delay="1300"
                                   data-aos-duration="400"></i>Daftar Sekarang
                            </a>
                        </div>
                        <div class="col-md-3 mb-3"
                             data-aos="slide-left"
                             data-aos-delay="1000"
                             data-aos-duration="600">
                            <a href="{{ route('public.about') }}"
                               class="btn modern-btn-outline btn-lg w-100"
                               data-aos="pulse"
                               data-aos-delay="1200"
                               data-aos-duration="600">
                                <i class="bi bi-info-circle me-2"
                                   data-aos="flip-right"
                                   data-aos-delay="1400"
                                   data-aos-duration="400"></i>Tentang Kami
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
            <h2 class="text-center mb-4 fw-bold"
                style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 2.5rem;"
                data-aos="fade-down"
                data-aos-duration="800"
                data-aos-easing="ease-out-cubic">
                <i class="bi bi-graph-up"
                   style="color: #667eea;"
                   data-aos="bounce"
                   data-aos-delay="200"
                   data-aos-duration="600"></i>
                UNAS Fest dalam Angka
            </h2>
        </div>
        <div class="col-12">
            <div class="stats-card text-white"
                 data-aos="flip-up"
                 data-aos-duration="1000"
                 data-aos-delay="300"
                 data-aos-easing="ease-out-back">
                <div class="glass-header text-center p-4"
                     data-aos="fade-down"
                     data-aos-delay="500"
                     data-aos-duration="600">
                    <h3 class="mb-0 fw-bold"
                        data-aos="fade-up"
                        data-aos-delay="700"
                        data-aos-duration="500">Statistik Kompetisi</h3>
                </div>
                <div class="p-4">
                    <div class="row text-center justify-content-center">
                        <div class="col-md-6 mb-3">
                            <div class="stats-inner-card"
                                 data-aos="zoom-in"
                                 data-aos-delay="900"
                                 data-aos-duration="800"
                                 data-aos-easing="ease-out-back">
                                <div class="p-4">
                                    <i class="bi bi-trophy-fill mb-3"
                                       style="font-size: 3rem; color: #667eea;"
                                       data-aos="spin"
                                       data-aos-delay="1100"
                                       data-aos-duration="800"></i>
                                    <h3 class="fw-bold mb-2"
                                        style="color: #667eea; font-size: 2.5rem;"
                                        data-aos="fade-up"
                                        data-aos-delay="1300"
                                        data-aos-duration="600">{{ $stats['active_competitions'] ?? '0' }}</h3>
                                    <p class="text-muted mb-0 fw-semibold"
                                       data-aos="fade-up"
                                       data-aos-delay="1500"
                                       data-aos-duration="500">Kompetisi Aktif</p>
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
            <h2 class="text-center mb-4 fw-bold"
                style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 2.5rem;"
                data-aos="fade-down"
                data-aos-duration="800"
                data-aos-easing="ease-out-cubic">
                <i class="bi bi-list-task"
                   style="color: #667eea;"
                   data-aos="bounce"
                   data-aos-delay="200"
                   data-aos-duration="600"></i>
                Daftar Kompetisi
            </h2>
        </div>
        <div class="col-12">
            <div class="glass-card"
                 data-aos="fade-up"
                 data-aos-duration="1000"
                 data-aos-delay="300"
                 data-aos-easing="ease-out-back">
                <div class="glass-header text-center p-4"
                     data-aos="fade-down"
                     data-aos-delay="500"
                     data-aos-duration="600">
                    <h3 class="mb-2 fw-bold text-white"
                        data-aos="fade-up"
                        data-aos-delay="700"
                        data-aos-duration="500">Semua Kompetisi UNAS Fest 2025</h3>
                    <p class="mb-0 text-white-50"
                       data-aos="fade-up"
                       data-aos-delay="900"
                       data-aos-duration="500">Pilih kompetisi yang sesuai dengan minat dan keahlianmu</p>
                </div>
                <div class="p-4">
                    @forelse($competitions as $index => $competition)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="competition-card"
                                     data-aos="slide-right"
                                     data-aos-duration="800"
                                     data-aos-delay="{{ ($index * 200) + 100 }}"
                                     data-aos-easing="ease-out-back">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4 class="text-primary mb-2"
                                                    data-aos="fade-right"
                                                    data-aos-delay="{{ ($index * 200) + 300 }}"
                                                    data-aos-duration="600">{{ $competition->name }}</h4>
                                                <p class="text-muted mb-3"
                                                   data-aos="fade-up"
                                                   data-aos-delay="{{ ($index * 200) + 500 }}"
                                                   data-aos-duration="600">{{ Str::limit($competition->description ?? 'Kompetisi inovatif yang menantang kreativitas dan kemampuan peserta.', 150) }}</p>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-2"
                                                         data-aos="fade-left"
                                                         data-aos-delay="{{ ($index * 200) + 700 }}"
                                                         data-aos-duration="500">
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar3 me-1"
                                                               data-aos="bounce"
                                                               data-aos-delay="{{ ($index * 200) + 800 }}"
                                                               data-aos-duration="400"></i>
                                                            <strong>Pendaftaran:</strong> {{ $competition->registration_start ? \Carbon\Carbon::parse($competition->registration_start)->format('d M Y') : 'TBA' }} - {{ $competition->registration_end ? \Carbon\Carbon::parse($competition->registration_end)->format('d M Y') : 'TBA' }}
                                                        </small>
                                                    </div>
                                                    <div class="col-md-6 mb-2"
                                                         data-aos="fade-left"
                                                         data-aos-delay="{{ ($index * 200) + 900 }}"
                                                         data-aos-duration="500">
                                                        <small class="text-muted">
                                                            <i class="bi bi-people me-1"
                                                               data-aos="bounce"
                                                               data-aos-delay="{{ ($index * 200) + 1000 }}"
                                                               data-aos-duration="400"></i>
                                                            <strong>Tim:</strong> {{ $competition->max_team_members ?? 'Maksimal 3' }} orang
                                                        </small>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-2"
                                                         data-aos="fade-left"
                                                         data-aos-delay="{{ ($index * 200) + 1100 }}"
                                                         data-aos-duration="500">
                                                        <small class="text-success">
                                                            <i class="bi bi-trophy me-1"
                                                               data-aos="bounce"
                                                               data-aos-delay="{{ ($index * 200) + 1200 }}"
                                                               data-aos-duration="400"></i>
                                                            <strong>Hadiah:</strong> {{ $competition->prize_amount ? 'Rp ' . number_format($competition->prize_amount, 0, ',', '.') : 'Sertifikat & Hadiah Menarik' }}
                                                        </small>
                                                    </div>
                                                    <div class="col-md-6 mb-2"
                                                         data-aos="fade-left"
                                                         data-aos-delay="{{ ($index * 200) + 1300 }}"
                                                         data-aos-duration="500">
                                                        <small class="text-info">
                                                            <i class="bi bi-people-fill me-1"
                                                               data-aos="bounce"
                                                               data-aos-delay="{{ ($index * 200) + 1400 }}"
                                                               data-aos-duration="400"></i>
                                                            <strong>Peserta:</strong> {{ $competition->registrations->count() ?? 0 }} terdaftar
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <div class="mb-3"
                                                     data-aos="zoom-in"
                                                     data-aos-delay="{{ ($index * 200) + 1500 }}"
                                                     data-aos-duration="500">
                                                    @if($competition->registration_start > now())
                                                        <span class="modern-badge bg-warning"
                                                              data-aos="pulse"
                                                              data-aos-delay="{{ ($index * 200) + 1600 }}"
                                                              data-aos-duration="600">Segera Dibuka</span>
                                                    @elseif($competition->registration_end < now())
                                                        <span class="modern-badge bg-danger"
                                                              data-aos="pulse"
                                                              data-aos-delay="{{ ($index * 200) + 1600 }}"
                                                              data-aos-duration="600">Pendaftaran Ditutup</span>
                                                    @else
                                                        <span class="modern-badge bg-success"
                                                              data-aos="pulse"
                                                              data-aos-delay="{{ ($index * 200) + 1600 }}"
                                                              data-aos-duration="600">Pendaftaran Dibuka</span>
                                                    @endif
                                                </div>
                                                
                                                <div class="d-grid gap-2"
                                                     data-aos="fade-up"
                                                     data-aos-delay="{{ ($index * 200) + 1700 }}"
                                                     data-aos-duration="600">
                                                    <a href="{{ route('public.competition.detail', $competition->slug) }}"
                                                       class="btn btn-outline-primary"
                                                       data-aos="zoom-in"
                                                       data-aos-delay="{{ ($index * 200) + 1800 }}"
                                                       data-aos-duration="400">
                                                        <i class="bi bi-eye me-1"></i>Lihat Detail
                                                    </a>
                                                    @if($competition->registration_start <= now() && $competition->registration_end >= now())
                                                        <a href="{{ route('login') }}"
                                                           class="btn btn-primary"
                                                           data-aos="zoom-in"
                                                           data-aos-delay="{{ ($index * 200) + 1900 }}"
                                                           data-aos-duration="400">
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
                        <div class="text-center py-5"
                             data-aos="fade-up"
                             data-aos-duration="1000"
                             data-aos-easing="ease-out-back">
                            <i class="bi bi-trophy text-muted mb-3"
                               style="font-size: 4rem;"
                               data-aos="bounce"
                               data-aos-delay="200"
                               data-aos-duration="800"></i>
                            <h3 class="text-muted"
                                data-aos="fade-up"
                                data-aos-delay="400"
                                data-aos-duration="600">Belum Ada Kompetisi</h3>
                            <p class="text-muted"
                               data-aos="fade-up"
                               data-aos-delay="600"
                               data-aos-duration="600">Kompetisi akan segera dibuka. Pantau terus website kami untuk informasi terbaru!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($competitions->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center"
                 data-aos="fade-up"
                 data-aos-duration="800"
                 data-aos-delay="200"
                 data-aos-easing="ease-out-back">
                <div data-aos="zoom-in"
                     data-aos-delay="400"
                     data-aos-duration="600">
                    {{ $competitions->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Call to Action Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center mb-4 fw-bold"
                style="background: linear-gradient(45deg, #ffeaa7, #fdcb6e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 2.5rem;"
                data-aos="fade-down"
                data-aos-duration="800"
                data-aos-easing="ease-out-cubic">
                <i class="bi bi-rocket"
                   style="color: #fdcb6e;"
                   data-aos="bounce"
                   data-aos-delay="200"
                   data-aos-duration="600"></i>
                Siap Menunjukkan Inovasimu?
            </h2>
        </div>
        <div class="col-12">
            <div class="cta-card text-white"
                 data-aos="fade-up"
                 data-aos-duration="1000"
                 data-aos-delay="300"
                 data-aos-easing="ease-out-back">
                <div class="glass-header text-center p-4"
                     data-aos="fade-down"
                     data-aos-delay="500"
                     data-aos-duration="600">
                    <h3 class="mb-2 fw-bold"
                        data-aos="fade-up"
                        data-aos-delay="700"
                        data-aos-duration="500">Bergabunglah dengan UNAS Fest 2025</h3>
                    <p class="mb-0 opacity-75"
                       data-aos="fade-up"
                       data-aos-delay="900"
                       data-aos-duration="500">Jangan lewatkan kesempatan emas untuk menunjukkan kemampuan terbaikmu</p>
                </div>
                <div class="p-5 text-center position-relative" style="z-index: 2;">
                    <p class="lead mb-4 fw-semibold"
                       data-aos="fade-up"
                       data-aos-delay="1100"
                       data-aos-duration="600">
                        Bergabunglah dengan ribuan peserta lainnya dan wujudkan ide terbaikmu di UNAS Fest 2025!
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3"
                             data-aos="slide-right"
                             data-aos-delay="1300"
                             data-aos-duration="600">
                            <a href="{{ route('login') }}"
                               class="btn modern-btn btn-lg w-100"
                               data-aos="pulse"
                               data-aos-delay="1500"
                               data-aos-duration="600">
                                <i class="bi bi-person-plus me-2"
                                   data-aos="flip-left"
                                   data-aos-delay="1700"
                                   data-aos-duration="400"></i>Daftar Sekarang
                            </a>
                        </div>
                        <div class="col-md-3 mb-3"
                             data-aos="slide-left"
                             data-aos-delay="1400"
                             data-aos-duration="600">
                            <a href="{{ route('public.contact') }}"
                               class="btn modern-btn-outline btn-lg w-100"
                               data-aos="pulse"
                               data-aos-delay="1600"
                               data-aos-duration="600">
                                <i class="bi bi-envelope me-2"
                                   data-aos="flip-right"
                                   data-aos-delay="1800"
                                   data-aos-duration="400"></i>Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
