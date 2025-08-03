@extends('layouts.simple')

@php
    $seoPage = 'about';
@endphp

@section('title', 'About Us - Caturnawa UNAS FEST 2025')

@section('content')

@push('styles')
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

    .modern-container {
        position: relative;
        z-index: 1;
    }
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
@endpush

<div class="dynamic-bg"></div>
<div class="container my-5 modern-container">
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5 mb-5">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                <div class="hero-content text-center">
                    <div class="floating-icon mb-4">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <h1 class="modern-title mb-4">
                        About <span style="background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Caturnawa UNAS FEST 2025</span>
                    </h1>
                    <p class="modern-subtitle mb-5">
                        Indonesia's largest national competition festival that combines technology innovation,
                        health, and biodiversity to create a sustainable future.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.competitions') }}"
                               class="btn modern-btn btn-auto w-100">
                                <i class="bi bi-trophy me-2"></i>View Competitions
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.contact') }}"
                               class="btn modern-btn-outline btn-auto w-100">
                                <i class="bi bi-envelope me-2"></i>Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision & Mission Section -->
    <div class="row g-4 mb-5">
        <div class="col-lg-6" data-aos="fade-right">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-eye me-2"></i>Our Vision
                    </h3>
                </div>
                <div class="p-4 text-center" style="color: #2d3748;">
                    <p class="mb-0">
                        To become a leading national competition festival that inspires sustainable innovation
                        in the fields of technology, health, and biodiversity for a better future for Indonesia.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6" data-aos="fade-left">
            <div class="card shadow h-100">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-bullseye me-2"></i>Our Mission
                    </h3>
                </div>
                <div class="p-4" style="color: #2d3748;">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Providing high-quality competition platform for Indonesian students</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Encouraging innovation and creativity in solving real-world problems</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Building collaborative networks between universities across Indonesia</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Developing sustainable solutions for future challenges</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="row">
        <div class="col-12" data-aos="zoom-in">
            <div class="card shadow">
                <div class="card-header bg-info text-white text-center">
                    <h2 class="card-title mb-0">Our Values</h2>
                    <p class="mb-0">The principles that form the foundation for organizing Caturnawa UNAS FEST 2025</p>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="100">
                            <div class="p-3">
                                <i class="bi bi-lightbulb text-primary" style="font-size: 3rem;"></i>
                                <h4 class="fw-bold text-primary mt-2">Innovation</h4>
                                <p class="text-muted">
                                    Encouraging creativity and out-of-the-box thinking to create innovative solutions that make an impact.
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="200">
                            <div class="p-3">
                                <i class="bi bi-people text-success" style="font-size: 3rem;"></i>
                                <h4 class="fw-bold text-success mt-2">Collaboration</h4>
                                <p class="text-muted">
                                    Building solid cooperation between participants, committees, and stakeholders to achieve common goals.
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="300">
                            <div class="p-3">
                                <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                                <h4 class="fw-bold text-warning mt-2">Quality</h4>
                                <p class="text-muted">
                                    Maintaining high standards in every aspect of competition organization and participant services.
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="400">
                            <div class="p-3">
                                <i class="bi bi-globe text-info" style="font-size: 3rem;"></i>
                                <h4 class="fw-bold text-info mt-2">Sustainable</h4>
                                <p class="text-muted">
                                    Focus on solutions that have a long-term impact on society and the environment.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
