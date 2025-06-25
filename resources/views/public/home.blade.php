@extends('layouts.public')

@php
    $seoPage = 'home';
@endphp

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden">
    <!-- Background Elements -->
    <div class="hero-bg-elements">
        <div class="floating-element floating-element-1"></div>
        <div class="floating-element floating-element-2"></div>
        <div class="floating-element floating-element-3"></div>
    </div>

    <div class="container position-relative">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-6 hero-content" data-aos="fade-right">
                <div class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-3">
                    <i class="bi bi-star-fill me-1"></i>
                    Festival Kompetisi Nasional #1
                </div>
                <h1 class="display-2 fw-bold text-white mb-4 font-poppins">
                    UNAS Fest
                    <span class="d-block text-gradient-warning">2025</span>
                </h1>
                <p class="lead text-white-75 mb-4 pe-lg-4">
                    Bergabunglah dengan festival kompetisi nasional terbesar di Indonesia yang menggabungkan
                    <span class="text-warning fw-semibold">Teknologi</span>,
                    <span class="text-success fw-semibold">Kesehatan</span>, dan
                    <span class="text-info fw-semibold">Biodiversitas</span>
                    untuk masa depan berkelanjutan.
                </p>

                <!-- CTA Buttons -->
                <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                    <a href="{{ route('public.competitions') }}" class="btn btn-warning btn-lg px-4 py-3 shadow-lg">
                        <i class="bi bi-trophy me-2"></i>Daftar Kompetisi
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-light btn-lg px-4 py-3">
                        <i class="bi bi-envelope me-2"></i>Hubungi Kami
                    </a>
                </div>

                <!-- Registration Info -->
                <div class="card bg-white bg-opacity-10 border-0 backdrop-blur">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center text-white">
                            <div class="bg-warning rounded-circle p-2 me-3">
                                <i class="bi bi-calendar-event text-dark"></i>
                            </div>
                            <div>
                                <small class="text-white-75 d-block">Pendaftaran dibuka</small>
                                <strong class="text-white">1 Januari - 28 Februari 2025</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="text-center position-relative">
                    <div class="hero-image-container">
                        <img src="{{ asset('assets/images/hero/hero-illustration.svg') }}"
                             alt="UNAS Fest 2025 Illustration"
                             class="img-fluid hero-main-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="d-none align-items-center justify-content-center hero-fallback">
                            <div class="text-center text-white">
                                <i class="bi bi-trophy-fill" style="font-size: 8rem; opacity: 0.3;"></i>
                                <h3 class="mt-3 opacity-50">UNAS Fest 2025</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Stats -->
                    <div class="floating-stats">
                        <div class="stat-card stat-card-1" data-aos="fade-up" data-aos-delay="400">
                            <div class="stat-number">10K+</div>
                            <div class="stat-label">Peserta</div>
                        </div>
                        <div class="stat-card stat-card-2" data-aos="fade-up" data-aos-delay="600">
                            <div class="stat-number">500M</div>
                            <div class="stat-label">Hadiah</div>
                        </div>
                        <div class="stat-card stat-card-3" data-aos="fade-up" data-aos-delay="800">
                            <div class="stat-number">100+</div>
                            <div class="stat-label">Universitas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="scroll-indicator">
        <div class="text-white text-center">
            <small class="d-block mb-2">Scroll untuk melihat lebih banyak</small>
            <div class="scroll-arrow">
                <i class="bi bi-chevron-down"></i>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card">
                    <div class="stats-icon bg-gradient-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number text-primary counter" data-target="{{ $stats['participants'] ?? 10000 }}">0</h3>
                        <p class="stats-label">Peserta Terdaftar</p>
                        <div class="stats-progress">
                            <div class="progress-bar bg-primary" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card">
                    <div class="stats-icon bg-gradient-success">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number text-success counter" data-target="{{ $stats['competitions'] ?? 15 }}">0</h3>
                        <p class="stats-label">Kategori Kompetisi</p>
                        <div class="stats-progress">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="stats-card">
                    <div class="stats-icon bg-gradient-warning">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number text-warning">{{ number_format(($stats['total_prize'] ?? 500000000) / 1000000) }} <small>Juta</small></h3>
                        <p class="stats-label">Total Hadiah</p>
                        <div class="stats-progress">
                            <div class="progress-bar bg-warning" style="width: 95%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="stats-card">
                    <div class="stats-icon bg-gradient-info">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number text-info counter" data-target="{{ $stats['universities'] ?? 100 }}">0</h3>
                        <p class="stats-label">Universitas Partner</p>
                        <div class="stats-progress">
                            <div class="progress-bar bg-info" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Competition Categories -->
<section class="competitions-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-badge">
                <i class="bi bi-trophy me-2"></i>Kompetisi Utama
            </div>
            <h2 class="section-title font-poppins">Kategori Kompetisi</h2>
            <p class="section-subtitle">
                Tiga pilar utama kompetisi yang menggabungkan inovasi untuk masa depan berkelanjutan
            </p>
        </div>

        <div class="row g-4">
            <!-- Technology -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="competition-card technology-card">
                    <div class="competition-header">
                        <div class="competition-icon">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <div class="competition-badge">Teknologi</div>
                    </div>

                    <div class="competition-image">
                        <img src="{{ asset('assets/images/competitions/technology-banner.jpg') }}"
                             alt="Kompetisi Teknologi"
                             onerror="this.style.display='none';">
                        <div class="competition-overlay">
                            <div class="overlay-content">
                                <i class="bi bi-laptop"></i>
                                <span>Tech Innovation</span>
                            </div>
                        </div>
                    </div>

                    <div class="competition-content">
                        <h4 class="competition-title">Teknologi</h4>
                        <p class="competition-description">
                            Kompetisi pengembangan aplikasi, AI, IoT, dan solusi teknologi inovatif untuk menyelesaikan masalah nyata di masyarakat.
                        </p>

                        <div class="competition-features">
                            <div class="feature-item">
                                <i class="bi bi-phone"></i>
                                <span>Mobile App Development</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-globe"></i>
                                <span>Web Development</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-robot"></i>
                                <span>AI & Machine Learning</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-wifi"></i>
                                <span>IoT Solutions</span>
                            </div>
                        </div>

                        <div class="competition-stats">
                            <div class="stat-item">
                                <span class="stat-number">50M</span>
                                <span class="stat-label">Hadiah Utama</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">3K+</span>
                                <span class="stat-label">Peserta</span>
                            </div>
                        </div>

                        <a href="{{ route('public.competitions') }}#technology" class="competition-btn">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Health -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 250px;">
                        <img src="{{ asset('assets/images/competitions/health-banner.jpg') }}" 
                             alt="Kompetisi Kesehatan" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-success bg-opacity-75 d-flex align-items-center justify-content-center">
                            <i class="bi bi-heart-pulse text-white" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-success mb-3">
                            <i class="bi bi-heart me-2"></i>Kesehatan
                        </h4>
                        <p class="card-text text-muted mb-4">
                            Inovasi dalam bidang kesehatan masyarakat, teknologi medis, dan solusi kesehatan digital untuk meningkatkan kualitas hidup.
                        </p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Health Tech Innovation</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Medical Device Design</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Public Health Solutions</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Digital Health Platform</li>
                        </ul>
                        <a href="{{ route('public.competitions') }}#health" class="btn btn-success w-100">
                            <i class="bi bi-arrow-right me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Biodiversity -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 250px;">
                        <img src="{{ asset('assets/images/competitions/biodiversity-banner.jpg') }}" 
                             alt="Kompetisi Biodiversitas" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-info bg-opacity-75 d-flex align-items-center justify-content-center">
                            <i class="bi bi-tree text-white" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-info mb-3">
                            <i class="bi bi-globe me-2"></i>Biodiversitas
                        </h4>
                        <p class="card-text text-muted mb-4">
                            Solusi inovatif untuk konservasi lingkungan, pelestarian biodiversitas, dan pembangunan berkelanjutan.
                        </p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Environmental Conservation</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Sustainable Development</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Eco-Innovation</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Green Technology</li>
                        </ul>
                        <a href="{{ route('public.competitions') }}#biodiversity" class="btn btn-info w-100">
                            <i class="bi bi-arrow-right me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<section class="section bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins">Timeline UNAS Fest 2025</h2>
            <p class="section-subtitle">
                Jadwal lengkap kegiatan dari pendaftaran hingga pengumuman pemenang
            </p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="timeline">
                    <div class="timeline-item" data-aos="fade-right" data-aos-delay="100">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-primary mb-2">
                                        <i class="bi bi-calendar-plus me-2"></i>Pendaftaran Dibuka
                                    </h5>
                                    <p class="text-muted mb-2">1 Januari - 28 Februari 2025</p>
                                    <p class="card-text">Pendaftaran peserta untuk semua kategori kompetisi dibuka secara online.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-left" data-aos-delay="200">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-warning mb-2">
                                        <i class="bi bi-upload me-2"></i>Pengumpulan Karya
                                    </h5>
                                    <p class="text-muted mb-2">1 - 15 Maret 2025</p>
                                    <p class="card-text">Periode pengumpulan karya dan proposal untuk semua kategori kompetisi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-right" data-aos-delay="300">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-info mb-2">
                                        <i class="bi bi-search me-2"></i>Penilaian Juri
                                    </h5>
                                    <p class="text-muted mb-2">16 - 25 Maret 2025</p>
                                    <p class="card-text">Proses penilaian karya oleh tim juri ahli dari berbagai bidang.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-left" data-aos-delay="400">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-success mb-2">
                                        <i class="bi bi-trophy me-2"></i>Pengumuman Pemenang
                                    </h5>
                                    <p class="text-muted mb-2">30 Maret 2025</p>
                                    <p class="card-text">Pengumuman pemenang dan acara penutupan UNAS Fest 2025.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Contact Section -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="font-poppins fw-bold mb-4">Siap Bergabung?</h2>
                <p class="lead text-muted mb-4">
                    Jangan lewatkan kesempatan emas untuk menunjukkan inovasi terbaikmu di UNAS Fest 2025. 
                    Daftar sekarang dan raih prestasi gemilang!
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('public.competitions') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-trophy me-2"></i>Daftar Sekarang
                    </a>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-chat-dots me-2"></i>Tanya Panitia
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-envelope me-2"></i>Kontak Cepat
                        </h5>
                        <form action="{{ route('public.contact.send') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" name="message" rows="3" placeholder="Pesan Anda" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Hero Section Styles */
    .hero-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-bg-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .floating-element {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 6s ease-in-out infinite;
    }

    .floating-element-1 {
        width: 100px;
        height: 100px;
        top: 20%;
        right: 10%;
        animation-delay: 0s;
    }

    .floating-element-2 {
        width: 150px;
        height: 150px;
        top: 60%;
        right: 20%;
        animation-delay: 2s;
    }

    .floating-element-3 {
        width: 80px;
        height: 80px;
        top: 40%;
        right: 5%;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .text-gradient-warning {
        background: linear-gradient(135deg, var(--accent-color), #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .text-white-75 {
        color: rgba(255, 255, 255, 0.75);
    }

    .backdrop-blur {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .hero-image-container {
        position: relative;
        z-index: 2;
    }

    .hero-main-image {
        max-height: 500px;
        filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));
    }

    .hero-fallback {
        height: 500px;
    }

    /* Floating Stats */
    .floating-stats {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .stat-card {
        position: absolute;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 15px 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        pointer-events: auto;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .stat-card-1 {
        top: 10%;
        left: -10%;
    }

    .stat-card-2 {
        top: 50%;
        right: -15%;
    }

    .stat-card-3 {
        bottom: 20%;
        left: -5%;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin: 0;
    }

    .scroll-indicator {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }

    .scroll-arrow {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0,-10px,0);
        }
        70% {
            transform: translate3d(0,-5px,0);
        }
        90% {
            transform: translate3d(0,-2px,0);
        }
    }

    /* Stats Section */
    .stats-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        position: relative;
    }

    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    }

    .stats-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 1.5rem;
        color: white;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        line-height: 1;
    }

    .stats-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .stats-progress {
        height: 4px;
        background: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        border-radius: 2px;
        transition: width 2s ease;
    }

    /* Competition Section */
    .competitions-section {
        padding: 100px 0;
        background: white;
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

    .competition-card {
        background: white;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: all 0.4s ease;
        position: relative;
        height: 100%;
    }

    .competition-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }

    .competition-header {
        padding: 25px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        position: relative;
    }

    .competition-icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .competition-badge {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .competition-image {
        height: 200px;
        position: relative;
        overflow: hidden;
    }

    .competition-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .competition-card:hover .competition-image img {
        transform: scale(1.1);
    }

    .competition-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.8), rgba(59, 130, 246, 0.6));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .competition-card:hover .competition-overlay {
        opacity: 1;
    }

    .overlay-content {
        text-align: center;
        color: white;
    }

    .overlay-content i {
        font-size: 3rem;
        margin-bottom: 10px;
        display: block;
    }

    .competition-content {
        padding: 30px;
    }

    .competition-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .competition-description {
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .competition-features {
        margin-bottom: 25px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .feature-item i {
        color: var(--primary-color);
        margin-right: 10px;
        width: 16px;
    }

    .competition-stats {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        padding: 20px;
        background: #f8fafc;
        border-radius: 15px;
    }

    .stat-item {
        text-align: center;
        flex: 1;
    }

    .stat-item .stat-number {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
        display: block;
    }

    .stat-item .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .competition-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .competition-btn:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        color: white;
        transform: translateY(-2px);
    }

    /* Gradient Backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, var(--secondary-color), #059669);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, var(--accent-color), #d97706);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .display-2 {
            font-size: 2.5rem;
        }

        .hero-section {
            padding: 60px 0;
        }

        .floating-stats {
            display: none;
        }

        .stats-section {
            padding: 60px 0;
        }

        .competitions-section {
            padding: 60px 0;
        }

        .stats-number {
            font-size: 2rem;
        }

        .competition-card {
            margin-bottom: 30px;
        }

        .stat-card {
            position: relative !important;
            top: auto !important;
            left: auto !important;
            right: auto !important;
            bottom: auto !important;
            margin-bottom: 20px;
        }
    }

    @media (max-width: 576px) {
        .hero-section .container {
            padding: 0 15px;
        }

        .display-2 {
            font-size: 2rem;
        }

        .lead {
            font-size: 1rem;
        }

        .btn-lg {
            padding: 12px 24px;
            font-size: 1rem;
        }

        .competition-stats {
            flex-direction: column;
            gap: 10px;
        }

        .stat-item {
            text-align: left;
        }
    }
</style>
@endpush
