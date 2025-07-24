@extends('layouts.simple')

@php
    $seoPage = 'competitions';
    $seoData = [
        'title' => 'Kompetisi UNAS Fest 2025 - Teknologi, Kesehatan & Biodiversitas',
        'description' => 'Ikuti berbagai kompetisi menarik di UNAS Fest 2025: Digital Content Competition, English Debate, Scientific Paper, dan lainnya. Daftar sekarang dan raih prestasi gemilang!',
        'keywords' => 'kompetisi teknologi, kompetisi kesehatan, kompetisi biodiversitas, lomba karya tulis, debat mahasiswa, digital content competition, scientific paper competition',
        'canonical' => url()->current(),
        'og_image' => asset('images/competitions-banner.jpg'),
    ];
@endphp

@section('title', 'Kompetisi - UNAS Fest 2025')

@push('head')
<!-- Structured Data for SEO -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Event",
    "name": "UNAS Fest 2025 - Festival Kompetisi Nasional",
    "description": "Festival kompetisi nasional terbesar di Indonesia dengan berbagai kategori: teknologi, kesehatan, dan biodiversitas",
    "startDate": "2025-01-01",
    "endDate": "2025-04-30",
    "eventStatus": "https://schema.org/EventScheduled",
    "eventAttendanceMode": "https://schema.org/MixedEventAttendanceMode",
    "location": {
        "@type": "Place",
        "name": "Universitas Nasional",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "ID"
        }
    },
    "organizer": {
        "@type": "Organization",
        "name": "UNAS Fest 2025 Committee",
        "url": "{{ url('/') }}"
    },
    "offers": {
        "@type": "Offer",
        "price": "75000",
        "priceCurrency": "IDR",
        "availability": "https://schema.org/InStock"
    }
}
</script>
@endpush

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
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .modern-title {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(45deg, #fff, #f8f9fa, #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        letter-spacing: -1px;
    }
    
    .modern-subtitle {
        font-size: 1rem;
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
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
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
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        transform: translateY(0);
    }

    /* Bubble Animation for Competition Card */
    .glass-card .glass-header {
        position: relative;
        overflow: hidden;
    }

    .glass-card .glass-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 40% 60%, rgba(255, 255, 255, 0.06) 0%, transparent 50%);
        animation: bubbleFloat 8s ease-in-out infinite;
        pointer-events: none;
    }

    .glass-card .glass-header::after {
        content: '';
        position: absolute;
        top: 10%;
        left: 10%;
        width: 6px;
        height: 6px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        animation: bubbleRise 6s ease-in-out infinite;
        box-shadow:
            20px 10px 0 2px rgba(255, 255, 255, 0.2),
            40px 20px 0 1px rgba(255, 255, 255, 0.15),
            60px 5px 0 3px rgba(255, 255, 255, 0.1),
            80px 15px 0 1px rgba(255, 255, 255, 0.2);
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

/* Performance optimizations */
* {
    box-sizing: border-box;
}

img {
    max-width: 100%;
    height: auto;
    loading: lazy;
}

/* Reduce animations on mobile for better performance */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Elegant CTA Section - Lightweight */
.elegant-cta-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.25);
    margin: 2rem 0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.elegant-cta-section:hover {
    box-shadow: 0 20px 45px rgba(102, 126, 234, 0.35);
}

.cta-background-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 30% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
    animation: float 8s ease-in-out infinite;
}

.cta-content {
    position: relative;
    z-index: 2;
    color: white;
}

.cta-icon-wrapper {
    display: inline-block;
    animation: pulse 3s ease-in-out infinite;
}

.cta-icon {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: transform 0.3s ease;
}

.cta-icon:hover {
    transform: scale(1.1);
}

.cta-icon i {
    font-size: 2.2rem;
    color: #ffffff;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.cta-title {
    font-size: 2.5rem;
    color: #ffffff;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    letter-spacing: -0.3px;
    margin-bottom: 1.5rem;
    font-weight: 700;
    animation: fadeInUp 1s ease-out;
}

.cta-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 400;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto 2rem;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.cta-btn-primary {
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    border: none;
    color: white;
    padding: 14px 28px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
    animation: fadeInUp 1s ease-out 0.4s both;
}

.cta-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
}

.cta-btn-secondary {
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.4);
    color: white;
    padding: 14px 28px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
    animation: fadeInUp 1s ease-out 0.6s both;
}

.cta-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.6);
    color: white;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

@keyframes bubbleFloat {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.8;
    }
    33% {
        transform: translateY(-10px) rotate(1deg);
        opacity: 1;
    }
    66% {
        transform: translateY(-5px) rotate(-1deg);
        opacity: 0.9;
    }
}

@keyframes bubbleRise {
    0% {
        transform: translateY(0px) scale(1);
        opacity: 0.3;
    }
    50% {
        transform: translateY(-20px) scale(1.2);
        opacity: 0.6;
    }
    100% {
        transform: translateY(-40px) scale(0.8);
        opacity: 0;
    }
}

/* Responsive optimizations */
@media (max-width: 768px) {
    .cta-title {
        font-size: 2.2rem;
    }

    .cta-icon {
        width: 60px;
        height: 60px;
    }

    .cta-icon i {
        font-size: 2rem;
    }

    .cta-btn-primary,
    .cta-btn-secondary {
        padding: 12px 25px;
        font-size: 1rem;
    }
}
</style>

<div class="dynamic-bg"></div>
<div class="container my-5 modern-container">
    <div class="floating-shapes"></div>
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5 mb-5">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                <div class="hero-content text-center">
                    <div class="floating-trophy mb-4">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h1 class="modern-title mb-4">
                        Kompetisi<span style="background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"> UNAS FEST 2025</span>
                    </h1>
                    <p class="modern-subtitle mb-5">
                        Bergabunglah dengan kompetisi nasional terbesar di Indonesia.<br>
                        Tunjukkan inovasi terbaikmu dalam berbagai bidang yang akan membentuk masa depan Indonesia.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('login') }}"
                               class="btn modern-btn btn-auto w-100">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.about') }}"
                               class="btn modern-btn-outline btn-lg w-100"">
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
            <h2 class="text-center mb-4 fw-bold"
                style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 2.5rem;">
                <i class="bi bi-graph-up"
                   style="color: #667eea;"></i>
                UNAS Fest dalam Angka
            </h2>
        </div>
        <div class="col-12">
            <div class="stats-card text-white">
                <div class="glass-header text-center p-4">
                    <h3 class="mb-0 fw-bold">Statistik Kompetisi</h3>
                </div>
                <div class="p-4">
                    <div class="row text-center justify-content-center">
                        <div class="col-md-6 mb-3">
                            <div class="stats-inner-card">
                                <div class="p-4">
                                    <i class="bi bi-trophy-fill mb-3"
                                       style="font-size: 3rem; color: #667eea;"></i>
                                    <h3 class="fw-bold mb-2"
                                        style="color: #667eea; font-size: 2.5rem;">{{ $stats['active_competitions'] ?? '0' }}</h3>
                                    <p class="text-muted mb-0 fw-semibold">Kompetisi Aktif</p>
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
                style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 2.5rem;">
                <i class="bi bi-list-task"
                   style="color: #667eea;"></i>
                Daftar Kompetisi
            </h2>
        </div>
        <div class="col-12">
            <div class="glass-card">
                <div class="glass-header text-center p-4">
                    <h3 class="mb-2 fw-bold" style="color: #667eea;">Semua Kompetisi UNAS Fest 2025</h3>
                    <p class="mb-0 text-gray-50">Pilih kompetisi yang sesuai dengan minat dan keahlianmu</p>
                </div>
                <div class="p-4">
                    @forelse($competitions as $index => $competition)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="competition-card p-4">
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
                                                        <span class="modern-badge bg-warning">Segera Dibuka</span>
                                                    @elseif($competition->registration_end < now())
                                                        <span class="modern-badge bg-danger">Pendaftaran Ditutup</span>
                                                    @else
                                                        <span class="modern-badge bg-success">Pendaftaran Dibuka</span>
                                                    @endif
                                                </div>
                                                
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('public.competition.detail', $competition->slug) }}"
                                                       class="btn btn-outline-primary">
                                                        <i class="bi bi-eye me-1"></i>Lihat Detail
                                                    </a>
                                                    @if($competition->registration_start <= now() && $competition->registration_end >= now())
                                                        <a href="{{ route('login') }}"
                                                           class="btn btn-primary">
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
                            <i class="bi bi-trophy text-muted mb-3"
                               style="font-size: 4rem;"></i>
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
                <div>
                    {{ $competitions->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Call to Action Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="elegant-cta-section">
                <div class="cta-background-pattern"></div>
                <div class="cta-content text-center p-5">
                    <div class="cta-icon-wrapper mb-4">
                        <div class="cta-icon">
                            <i class="bi bi-rocket-takeoff"></i>
                        </div>
                    </div>
                    <h2 class="cta-title mb-4 fw-bold">
                        Siap Menunjukkan Inovasimu?
                    </h2>
                    <p class="cta-subtitle mb-4 fs-5">
                        Bergabunglah dengan ribuan peserta lainnya dan wujudkan ide terbaikmu di UNAS Fest 2025!
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('login') }}" class="btn cta-btn-primary w-100">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.contact') }}" class="btn cta-btn-secondary w-100">
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

@push('scripts')
<script>
// Lightweight animation script
document.addEventListener('DOMContentLoaded', function() {
    // Simple scroll reveal with Intersection Observer
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        // Observe cards for reveal animation
        document.querySelectorAll('.glass-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    }

    // Add smooth hover effects only for primary buttons
    document.querySelectorAll('.cta-btn-primary').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
