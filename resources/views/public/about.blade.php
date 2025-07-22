@extends('layouts.simple')

@php
    $seoPage = 'about';
@endphp

@section('title', 'Tentang Kami - UNAS Fest 2025')

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
    
    .floating-icon {
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
    
    .value-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .value-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
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
    
    .modern-container {
        position: relative;
        z-index: 1;
    }
</style>

<div class="dynamic-bg"></div>
<div class="container my-5 modern-container">
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5 mb-5">
                <div class="hero-content text-center">
                    <div class="floating-icon mb-4">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <h1 class="modern-title mb-4">
                        Tentang <span style="background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">UNAS Fest 2025</span>
                    </h1>
                    <p class="modern-subtitle mb-5">
                        Festival kompetisi nasional terbesar di Indonesia yang menggabungkan inovasi teknologi,
                        kesehatan, dan biodiversitas untuk menciptakan masa depan yang berkelanjutan.
                    </p>
                        <div class="row justify-content-center">
                            <div class="col-md-3 mb-3">
                            <a href="{{ route('public.competitions') }}"
                               class="btn modern-btn btn-lg w-auto mb-3">
                                <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.contact') }}"
                               class="btn modern-btn-outline btn-lg w-auto mb-3">
                                <i class="bi bi-envelope me-2"></i>Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision & Mission Section -->
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="glass-card h-100">
                <div class="glass-header p-4 text-center">
                    <h3 class="mb-0 fw-bold" style="color: #764ba2;"><i class="bi bi-eye me-2" style="color: #764ba2;"></i>Visi Kami</h3>
                </div>
                <div class="p-4 text-center" style="color: #2d3748;">
                    <p class="mb-0">
                        Menjadi festival kompetisi nasional terdepan yang menginspirasi inovasi berkelanjutan
                        dalam bidang teknologi, kesehatan, dan biodiversitas untuk masa depan Indonesia yang lebih baik.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="glass-card h-100">
                <div class="glass-header p-4 text-center">
                    <h3 class="mb-0 fw-bold" style="color: #764ba2;"><i class="bi bi-bullseye me-2"></i>Misi Kami</h3>
                </div>
                <div class="p-4" style="color: #2d3748;">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Memberikan platform kompetisi berkualitas tinggi untuk mahasiswa Indonesia</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Mendorong inovasi dan kreativitas dalam menyelesaikan masalah nyata</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Membangun jaringan kolaborasi antar universitas di seluruh Indonesia</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Mengembangkan solusi berkelanjutan untuk tantangan masa depan</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4 fw-bold"
                style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 2.5rem;">
                <i class="bi bi-gem" style="color: #764ba2;"></i> Nilai-Nilai Kami
            </h2>
            <p class="text-center text-muted mb-5" data-aos="fade-up" data-aos-delay="200">Prinsip-prinsip yang menjadi fondasi dalam menyelenggarakan UNAS Fest 2025</p>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="value-card text-center p-4">
                <i class="bi bi-lightbulb display-4 mb-3" style="color: #667eea;"></i>
                <h4 class="fw-bold mb-2">Inovasi</h4>
                <p class="text-muted">
                    Mendorong kreativitas dan pemikiran out-of-the-box untuk menciptakan solusi inovatif yang berdampak.
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="value-card text-center p-4">
                <i class="bi bi-people display-4 mb-3" style="color: #48bb78;"></i>
                <h4 class="fw-bold mb-2">Kolaborasi</h4>
                <p class="text-muted">
                    Membangun kerjasama yang solid antar peserta, panitia, dan stakeholder untuk mencapai tujuan bersama.
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="value-card text-center p-4">
                <i class="bi bi-award display-4 mb-3" style="color: #feca57;"></i>
                <h4 class="fw-bold mb-2">Kualitas</h4>
                <p class="text-muted">
                    Menjaga standar tinggi dalam setiap aspek penyelenggaraan kompetisi dan pelayanan peserta.
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="value-card text-center p-4">
                <i class="bi bi-globe display-4 mb-3" style="color: #5a67d8;"></i>
                <h4 class="fw-bold mb-2">Berkelanjutan</h4>
                <p class="text-muted">
                    Fokus pada solusi yang memberikan dampak jangka panjang bagi masyarakat dan lingkungan.
                </p>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="cta-card text-dark">
                <div class="p-5 text-center position-relative" style="z-index: 2;">
                    <h2 class="fw-bold mb-3" style="color: #d69e2e;">Siap Menjadi Bagian dari Perubahan?</h2>
                    <p class="text-gray lead mb-4 fw-semibold">
                        Bergabunglah dengan ribuan inovator muda lainnya dan wujudkan ide terbaikmu di UNAS Fest 2025!
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('login') }}"
                               class="btn modern-btn btn-lg w-100">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.competitions') }}"
                               class="btn modern-btn-outline btn-lg w-100"
                               style="color: #d69e2e; border-color: #d69e2e;"
                               onmouseover="this.style.backgroundColor='rgba(214, 158, 46, 0.1)'; this.style.color='#d69e2e';"
                               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#d69e2e';">
                                <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
