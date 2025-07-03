@extends('layouts.simple')

@extends('layouts.simple')

@php
    $seoPage = 'competitions';
@endphp

@section('content')
<!-- Hero Section -->
<section class="competitions-hero">
    <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>

    <div class="container position-relative">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <div class="hero-badge">
                    <i class="bi bi-trophy me-2"></i>
                    Kompetisi Nasional 2025
                </div>

                <h1 class="hero-title font-poppins">
                    Kompetisi
                    <span class="text-gradient">UNAS Fest 2025</span>
                </h1>

                <p class="hero-subtitle font-poppins">
                    Tunjukkan inovasi terbaikmu dalam berbagai kompetisi yang akan membentuk masa depan Indonesia
                </p>

                <div class="hero-actions" data-aos="fade-up" data-aos-delay="400">
                    <a href="#competitions-list" class="btn-primary-custom">
                        <span>Jelajahi Kompetisi</span>
                        <i class="bi bi-arrow-down"></i>
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary-custom">
                        <i class="bi bi-person-plus me-2"></i>
                        Daftar Sekarang
                    </a>
                </div>

                <div class="hero-stats" data-aos="fade-up" data-aos-delay="500">
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['total_participants'] ?? '10K+' }}</span>
                        <span class="stat-label">Peserta</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['active_competitions'] ?? '15' }}</span>
                        <span class="stat-label">Kompetisi</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($stats['total_prizes'] ?? 500000000) }}</span>
                        <span class="stat-label">Total Hadiah</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- All Competitions Section -->
<section id="competitions-list" class="all-competitions-section">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <div class="section-badge">
                <i class="bi bi-award me-2"></i>
                Semua Kompetisi
            </div>
            <h2 class="section-title font-poppins">Kompetisi UNAS Fest 2025</h2>
            <p class="section-subtitle font-poppins">
                Pilih kompetisi yang sesuai dengan minat dan keahlianmu
            </p>
        </div>

        <!-- Competitions Grid -->
        <div class="row g-4">
            @forelse($competitions as $competition)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="competition-card h-100">
                        <div class="card-header">
                            <div class="card-badge">{{ $competition->category ?? 'Kompetisi' }}</div>
                            <div class="card-status">
                                @if($competition->registration_start > now())
                                    <span class="badge bg-warning">Segera Dibuka</span>
                                @elseif($competition->registration_end < now())
                                    <span class="badge bg-danger">Pendaftaran Ditutup</span>
                                @else
                                    <span class="badge bg-success">Pendaftaran Dibuka</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-image">
                            @if($competition->image)
                                <img src="{{ asset('storage/' . $competition->image) }}" 
                                     alt="{{ $competition->name }}" 
                                     class="competition-image">
                            @else
                                <div class="default-image">
                                    <i class="bi bi-trophy"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-content">
                            <h3 class="card-title font-poppins">{{ $competition->name }}</h3>
                            <p class="card-description">{{ Str::limit($competition->description ?? 'Kompetisi inovatif yang menantang kreativitas dan kemampuan peserta.', 120) }}</p>
                            
                            <div class="card-info">
                                <div class="info-item">
                                    <i class="bi bi-calendar3"></i>
                                    <div>
                                        <strong>Pendaftaran:</strong><br>
                                        {{ $competition->registration_start ? \Carbon\Carbon::parse($competition->registration_start)->format('d M Y') : 'TBA' }} - 
                                        {{ $competition->registration_end ? \Carbon\Carbon::parse($competition->registration_end)->format('d M Y') : 'TBA' }}
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <i class="bi bi-trophy"></i>
                                    <div>
                                        <strong>Hadiah:</strong><br>
                                        {{ $competition->prize_amount ? 'Rp ' . number_format($competition->prize_amount) : 'Sertifikat & Hadiah Menarik' }}
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <i class="bi bi-people"></i>
                                    <div>
                                        <strong>Tim:</strong><br>
                                        {{ $competition->max_team_members ?? 'Maksimal 3' }} orang
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-actions">
                                <a href="{{ route('public.competition', $competition) }}" class="btn-detail">
                                    <span>Lihat Detail</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                
                                @if($competition->registration_start <= now() && $competition->registration_end >= now())
                                    <a href="{{ route('login') }}" class="btn-register">
                                        <i class="bi bi-person-plus me-2"></i>
                                        Daftar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center" data-aos="fade-up">
                    <div class="empty-state">
                        <i class="bi bi-trophy" style="font-size: 4rem; color: var(--primary-color);"></i>
                        <h3 class="mt-3 font-poppins">Belum Ada Kompetisi</h3>
                        <p class="text-muted">Kompetisi akan segera dibuka. Pantau terus website kami!</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($competitions->hasPages())
            <div class="pagination-wrapper mt-5" data-aos="fade-up">
                {{ $competitions->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="section bg-primary text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <h2 class="fw-bold mb-4 font-poppins">Siap Menunjukkan Inovasimu?</h2>
                <p class="lead mb-4 font-poppins">
                    Bergabunglah dengan ribuan peserta lainnya dan wujudkan ide terbaikmu di UNAS Fest 2025!
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-warning btn-lg px-5">
                        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                    </a>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-light btn-lg px-5">
                        <i class="bi bi-envelope me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .font-poppins {
        font-family: 'Poppins', sans-serif;
    }

    /* Competitions Hero Section */
    .competitions-hero {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .hero-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
            radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
            radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px);
        background-size: 50px 50px;
        animation: patternMove 20s linear infinite;
    }

    @keyframes patternMove {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }

    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 6s ease-in-out infinite;
    }

    .shape-1 {
        width: 120px;
        height: 120px;
        top: 20%;
        right: 10%;
        animation-delay: 0s;
    }

    .shape-2 {
        width: 80px;
        height: 80px;
        top: 60%;
        right: 20%;
        animation-delay: 2s;
    }

    .shape-3 {
        width: 100px;
        height: 100px;
        top: 40%;
        left: 10%;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(180deg); }
    }

    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        color: white;
        margin-bottom: 20px;
        line-height: 1.1;
    }

    .text-gradient {
        background: linear-gradient(135deg, var(--accent-color), #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 40px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .hero-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 50px;
        flex-wrap: wrap;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--accent-color), #f59e0b);
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
    }

    .btn-primary-custom:hover {
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary-custom {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 13px 28px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary-custom:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        transform: translateY(-3px);
    }

    .hero-stats {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .hero-stats .stat-item {
        text-align: center;
        color: white;
    }

    .hero-stats .stat-number {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .hero-stats .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .stat-divider {
        width: 1px;
        height: 40px;
        background: rgba(255, 255, 255, 0.3);
    }

    /* All Competitions Section */
    .all-competitions-section {
        padding: 100px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .section-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    .section-subtitle {
        font-size: 1.1rem;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Competition Cards */
    .competition-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
    }

    .competition-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 20px;
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-badge {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .card-status .badge {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 5px 10px;
    }

    .card-image {
        height: 200px;
        position: relative;
        overflow: hidden;
    }

    .competition-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .competition-card:hover .competition-image {
        transform: scale(1.1);
    }

    .default-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }

    .card-content {
        padding: 25px;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .card-description {
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    .card-info {
        margin-bottom: 25px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 15px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
    }

    .info-item i {
        color: var(--primary-color);
        font-size: 1.2rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .info-item strong {
        color: var(--primary-color);
        font-size: 0.85rem;
    }

    .info-item div {
        font-size: 0.85rem;
        color: #64748b;
    }

    .card-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-detail {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .btn-detail:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    .btn-register {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        background: linear-gradient(135deg, var(--accent-color), #f59e0b);
        color: white;
        border: none;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .btn-register:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper .pagination {
        gap: 5px;
    }

    .pagination-wrapper .page-link {
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
        border-radius: 10px;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .pagination-wrapper .page-link:hover {
        background: var(--primary-color);
        color: white;
    }

    .pagination-wrapper .page-item.active .page-link {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .hero-actions {
            flex-direction: column;
            align-items: center;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 250px;
            justify-content: center;
        }

        .hero-stats {
            flex-direction: column;
            gap: 20px;
        }

        .stat-divider {
            width: 40px;
            height: 1px;
        }

        .section-title {
            font-size: 2rem;
        }

        .all-competitions-section {
            padding: 60px 0;
        }

        .card-actions {
            flex-direction: column;
        }

        .btn-detail,
        .btn-register {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 2rem;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 100%;
            max-width: 280px;
        }

        .competition-card {
            margin-bottom: 20px;
        }

        .card-content {
            padding: 20px;
        }

        .info-item {
            padding: 10px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
@endpush
