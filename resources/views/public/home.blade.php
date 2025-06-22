<!DOCTYPE html>
<html lang="id" prefix="og: http://ogp.me/ns#">
<head>
    {{-- SEO Component --}}
    <x-seo
        title="UNAS Fest 2025 - Festival Kompetisi Teknologi Terbesar Indonesia"
        description="UNAS Fest 2025 adalah festival kompetisi teknologi terbesar di Indonesia. Ikuti berbagai kompetisi programming, design, business plan, dan essay writing dengan total hadiah jutaan rupiah. Daftar sekarang!"
        keywords="unas fest 2025, kompetisi teknologi, programming contest, design competition, business plan competition, essay writing, universitas nasional, jakarta, indonesia, teknologi, inovasi, hadiah jutaan"
        :image="asset('images/unas-fest-2025-og.jpg')"
        :url="route('public.home')"
        type="website"
        author="UNAS Fest 2025 Committee"
    />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- UNAS Theme CSS -->
    <link href="{{ asset('css/unas-theme.css') }}" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --bio-green: #2E7D32;
            --health-blue: #1976D2;
            --tech-purple: #7B1FA2;
            --nature-light: #A5D6A7;
            --health-light: #90CAF9;
            --tech-light: #CE93D8;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--bio-green) 0%, var(--health-blue) 50%, var(--tech-purple) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="bio" cx="20%" cy="30%"><stop offset="0%" stop-color="%23A5D6A7" stop-opacity="0.3"/><stop offset="100%" stop-color="%23A5D6A7" stop-opacity="0"/></radialGradient><radialGradient id="health" cx="70%" cy="20%"><stop offset="0%" stop-color="%2390CAF9" stop-opacity="0.3"/><stop offset="100%" stop-color="%2390CAF9" stop-opacity="0"/></radialGradient><radialGradient id="tech" cx="50%" cy="80%"><stop offset="0%" stop-color="%23CE93D8" stop-opacity="0.3"/><stop offset="100%" stop-color="%23CE93D8" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="300" r="150" fill="url(%23bio)"/><circle cx="700" cy="200" r="120" fill="url(%23health)"/><circle cx="500" cy="800" r="180" fill="url(%23tech)"/><path d="M100,500 Q300,400 500,500 T900,500" stroke="%23ffffff" stroke-width="2" fill="none" opacity="0.2"/></svg>') no-repeat center center;
            background-size: cover;
            opacity: 0.6;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .floating-icon {
            position: absolute;
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.3);
            animation: float 6s ease-in-out infinite;
        }

        .floating-icon:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-icon:nth-child(2) { top: 60%; left: 80%; animation-delay: 2s; }
        .floating-icon:nth-child(3) { top: 80%; left: 20%; animation-delay: 4s; }
        .floating-icon:nth-child(4) { top: 30%; left: 70%; animation-delay: 1s; }
        .floating-icon:nth-child(5) { top: 70%; left: 50%; animation-delay: 3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        .floating-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .competition-card {
            transition: all 0.3s ease;
            border: none;
            background: var(--unas-white);
            border-radius: var(--unas-radius-lg);
            overflow: hidden;
            position: relative;
        }

        .competition-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .competition-card.bio-theme .card-header {
            background: linear-gradient(135deg, var(--bio-green), var(--nature-light));
            color: white;
        }

        .competition-card.health-theme .card-header {
            background: linear-gradient(135deg, var(--health-blue), var(--health-light));
            color: white;
        }

        .competition-card.tech-theme .card-header {
            background: linear-gradient(135deg, var(--tech-purple), var(--tech-light));
            color: white;
        }

        .competition-card .card-header {
            border: none;
            padding: var(--unas-space-4);
            position: relative;
        }

        .competition-card .card-header::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: translate(20px, -20px);
        }
        
        .stats-counter {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--bio-green), var(--health-blue), var(--tech-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto var(--unas-space-4);
            position: relative;
            overflow: hidden;
        }

        .feature-icon.bio-icon {
            background: linear-gradient(135deg, var(--bio-green), var(--nature-light));
        }

        .feature-icon.health-icon {
            background: linear-gradient(135deg, var(--health-blue), var(--health-light));
        }

        .feature-icon.tech-icon {
            background: linear-gradient(135deg, var(--tech-purple), var(--tech-light));
        }

        .feature-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            transition: all 0.6s;
            opacity: 0;
        }

        .feature-icon:hover::before {
            animation: shine 0.6s ease-in-out;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); opacity: 0; }
        }
        
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 188, 212, 0.1);
        }
        
        .btn-cta {
            background: #FF9800;
            border: none;
            color: white;
            font-weight: 700;
            padding: var(--unas-space-4) var(--unas-space-8);
            border-radius: var(--unas-radius-full);
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: var(--unas-shadow-lg);
            background: #E65100;
            color: white;
        }
        
        .countdown-item {
            background: var(--unas-white);
            border-radius: var(--unas-radius);
            padding: var(--unas-space-4);
            text-align: center;
            box-shadow: var(--unas-shadow);
        }
        
        .countdown-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--unas-primary);
        }
        
        .countdown-label {
            font-size: 0.875rem;
            color: var(--unas-gray-600);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                <i class="bi bi-building me-2"></i>UNAS Fest 2025
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kompetisi">Kompetisi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="{{ route('login') }}" class="unas-btn-primary">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section">
        <div class="floating-elements">
            <div class="floating-icon">🌱</div>
            <div class="floating-icon">🏥</div>
            <div class="floating-icon">💻</div>
            <div class="floating-icon">🔬</div>
            <div class="floating-icon">🌍</div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="mb-4">
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill mb-3">
                            <i class="bi bi-star-fill text-warning me-2"></i>Bio Diversity • Health • Technology
                        </span>
                    </div>
                    <h1 class="display-3 fw-bold text-white mb-4">
                        UNAS Fest 2025
                        <span class="d-block" style="font-size: 0.6em; color: rgba(255,255,255,0.9);">
                            Innovating for a Sustainable Future
                        </span>
                    </h1>
                    <p class="lead text-white mb-4" style="font-size: 1.2rem; line-height: 1.6;">
                        Bergabunglah dalam festival kompetisi teknologi terbesar yang menggabungkan
                        <strong>keanekaragaman hayati</strong>, <strong>kesehatan</strong>, dan <strong>teknologi</strong>
                        untuk menciptakan solusi inovatif bagi masa depan yang berkelanjutan.
                    </p>
                    <div class="row mb-4">
                        <div class="col-4 text-center">
                            <div class="text-white">
                                <i class="bi bi-tree-fill fs-1 mb-2" style="color: var(--nature-light);"></i>
                                <div class="fw-bold">Bio Diversity</div>
                                <small>Solusi Lingkungan</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="text-white">
                                <i class="bi bi-heart-pulse-fill fs-1 mb-2" style="color: var(--health-light);"></i>
                                <div class="fw-bold">Health</div>
                                <small>Inovasi Kesehatan</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="text-white">
                                <i class="bi bi-cpu-fill fs-1 mb-2" style="color: var(--tech-light);"></i>
                                <div class="fw-bold">Technology</div>
                                <small>Teknologi Masa Depan</small>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 py-3 shadow">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </a>
                        <a href="#kompetisi" class="btn btn-outline-light btn-lg px-4 py-3">
                            <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                        </a>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <div class="text-center position-relative">
                        <div class="hero-image-container" style="position: relative;">
                            <!-- Hero Visual with Bio-Health-Tech Theme -->
                            <div class="hero-visual" style="
                                width: 100%;
                                height: 450px;
                                background: rgba(255,255,255,0.1);
                                border-radius: 20px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                backdrop-filter: blur(10px);
                                border: 1px solid rgba(255,255,255,0.2);
                                position: relative;
                                overflow: hidden;
                            ">
                                <!-- Background Pattern -->
                                <div style="
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    right: 0;
                                    bottom: 0;
                                    background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"20\" cy=\"20\" r=\"2\" fill=\"%23A5D6A7\" opacity=\"0.3\"/><circle cx=\"80\" cy=\"30\" r=\"1.5\" fill=\"%2390CAF9\" opacity=\"0.3\"/><circle cx=\"50\" cy=\"70\" r=\"2.5\" fill=\"%23CE93D8\" opacity=\"0.3\"/><circle cx=\"30\" cy=\"80\" r=\"1\" fill=\"%23A5D6A7\" opacity=\"0.2\"/><circle cx=\"70\" cy=\"60\" r=\"1.8\" fill=\"%2390CAF9\" opacity=\"0.2\"/></svg>') repeat;
                                    animation: float 20s linear infinite;
                                "></div>

                                <!-- Main Content -->
                                <div class="text-center text-white position-relative z-index-1">
                                    <div class="row g-4">
                                        <div class="col-4">
                                            <div class="theme-icon bio-icon mb-3 mx-auto" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--bio-green), var(--nature-light));">
                                                <i class="bi bi-tree fs-1"></i>
                                            </div>
                                            <div class="fw-bold">Eco Solutions</div>
                                            <small style="opacity: 0.8;">Sustainable Innovation</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="theme-icon health-icon mb-3 mx-auto" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--health-blue), var(--health-light));">
                                                <i class="bi bi-heart-pulse fs-1"></i>
                                            </div>
                                            <div class="fw-bold">Health Tech</div>
                                            <small style="opacity: 0.8;">Medical Innovation</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="theme-icon tech-icon mb-3 mx-auto" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--tech-purple), var(--tech-light));">
                                                <i class="bi bi-robot fs-1"></i>
                                            </div>
                                            <div class="fw-bold">AI & IoT</div>
                                            <small style="opacity: 0.8;">Future Technology</small>
                                        </div>
                                    </div>

                                    <!-- Countdown Timer -->
                                    <div class="row g-2 mt-4">
                                        <div class="col-3">
                                            <div class="countdown-item" style="background: rgba(255,255,255,0.2); border-radius: 10px; padding: 15px; backdrop-filter: blur(5px);">
                                                <div class="countdown-number fw-bold fs-4" id="days">30</div>
                                                <div class="countdown-label small">Hari</div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="countdown-item" style="background: rgba(255,255,255,0.2); border-radius: 10px; padding: 15px; backdrop-filter: blur(5px);">
                                                <div class="countdown-number fw-bold fs-4" id="hours">12</div>
                                                <div class="countdown-label small">Jam</div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="countdown-item" style="background: rgba(255,255,255,0.2); border-radius: 10px; padding: 15px; backdrop-filter: blur(5px);">
                                                <div class="countdown-number fw-bold fs-4" id="minutes">45</div>
                                                <div class="countdown-label small">Menit</div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="countdown-item" style="background: rgba(255,255,255,0.2); border-radius: 10px; padding: 15px; backdrop-filter: blur(5px);">
                                                <div class="countdown-number fw-bold fs-4" id="seconds">30</div>
                                                <div class="countdown-label small">Detik</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <small style="opacity: 0.9;">Menuju pembukaan pendaftaran</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stats-counter">{{ number_format($stats['total_participants']) }}+</div>
                    <h5 class="text-muted">Peserta Terdaftar</h5>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stats-counter">{{ $stats['active_competitions'] }}+</div>
                    <h5 class="text-muted">Kompetisi Aktif</h5>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stats-counter">{{ $stats['total_universities'] }}+</div>
                    <h5 class="text-muted">Universitas</h5>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="stats-counter">{{ number_format($stats['total_prizes'] / 1000000, 0) }}M+</div>
                    <h5 class="text-muted">Total Hadiah (IDR)</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- Competitions Section -->
    <section id="kompetisi" class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 fw-bold mb-3" style="background: linear-gradient(135deg, var(--bio-green), var(--health-blue), var(--tech-purple)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Kompetisi Bio-Health-Tech
                </h2>
                <p class="lead text-muted">Ikuti berbagai kompetisi inovatif yang menggabungkan keanekaragaman hayati, kesehatan, dan teknologi</p>
            </div>

            @if($competitions->count() > 0)
                <div class="row">
                    @foreach($competitions as $index => $competition)
                        @php
                            $themes = ['bio-theme', 'health-theme', 'tech-theme'];
                            $theme = $themes[$index % 3];
                            $icons = [
                                'programming' => 'bi-code-slash',
                                'design' => 'bi-palette',
                                'business' => 'bi-briefcase',
                                'essay' => 'bi-journal-text',
                                'debate' => 'bi-mic',
                                'video' => 'bi-camera-video'
                            ];
                            $icon = $icons[$competition->category] ?? 'bi-trophy';

                            $badgeColors = [
                                'individual' => 'success',
                                'team' => 'primary',
                                'group' => 'warning'
                            ];
                            $badgeColor = $badgeColors[$competition->type] ?? 'info';
                        @endphp

                        <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                            <div class="competition-card {{ $theme }} card h-100">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="{{ $icon }} me-2"></i>{{ $competition->name }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ Str::limit($competition->description, 100) }}</p>

                                    <!-- Competition Details -->
                                    <div class="mb-3">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <small class="text-muted">Kategori:</small>
                                                <div class="fw-semibold">{{ ucfirst($competition->category) }}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Tipe:</small>
                                                <div>
                                                    <span class="badge bg-{{ $badgeColor }}">{{ ucfirst($competition->type) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Registration Period -->
                                    <div class="mb-3">
                                        <small class="text-muted">Pendaftaran:</small>
                                        <div class="fw-semibold">
                                            {{ $competition->registration_start->format('d M') }} -
                                            {{ $competition->registration_end->format('d M Y') }}
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Total Hadiah:</small>
                                            <div class="fw-bold" style="color: var(--bio-green);">
                                                Rp {{ number_format($competition->prize_amount, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        @if($competition->registration_start <= now() && $competition->registration_end >= now())
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Buka
                                            </span>
                                        @elseif($competition->registration_start > now())
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>Segera
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Tutup
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    @if($competition->registration_start <= now() && $competition->registration_end >= now())
                                        <a href="{{ route('public.competition', $competition) }}" class="btn btn-primary w-100">
                                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                                        </a>
                                    @else
                                        <a href="{{ route('public.competition', $competition) }}" class="btn btn-outline-primary w-100">
                                            <i class="bi bi-eye me-2"></i>Lihat Detail
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($competitions->count() >= 6)
                    <div class="text-center mt-4" data-aos="fade-up">
                        <a href="{{ route('public.competitions') }}" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-grid me-2"></i>Lihat Semua Kompetisi
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-5" data-aos="fade-up">
                    <div class="mb-4">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Belum Ada Kompetisi Aktif</h4>
                    <p class="text-muted mb-4">Kompetisi sedang dalam persiapan. Pantau terus untuk update terbaru!</p>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="bi bi-bell me-2"></i>Daftar untuk Notifikasi
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Features Section -->
    <section id="tentang" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 fw-bold text-primary mb-3">Mengapa Memilih UNAS Fest?</h2>
                <p class="lead text-muted">Platform terdepan dengan fitur lengkap dan mudah digunakan</p>
            </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center">
                        <div class="feature-icon bio-icon">
                            <i class="bi bi-tree"></i>
                        </div>
                        <h4 class="mb-3" style="color: var(--bio-green);">Sustainable Solutions</h4>
                        <p class="text-muted">Kompetisi yang fokus pada solusi berkelanjutan untuk menjaga keanekaragaman hayati dan lingkungan.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center">
                        <div class="feature-icon health-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h4 class="mb-3" style="color: var(--health-blue);">Health Innovation</h4>
                        <p class="text-muted">Inovasi teknologi kesehatan untuk meningkatkan kualitas hidup dan kesejahteraan masyarakat.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center">
                        <div class="feature-icon tech-icon">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <h4 class="mb-3" style="color: var(--tech-purple);">Future Technology</h4>
                        <p class="text-muted">Teknologi masa depan dengan AI, IoT, dan blockchain untuk transformasi digital.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 fw-bold text-primary mb-3">Hubungi Kami</h2>
                <p class="lead text-muted">Ada pertanyaan? Jangan ragu untuk menghubungi tim kami</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="unas-card p-5" data-aos="fade-up">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-4" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-primary mb-1">Email</h5>
                                        <p class="text-muted mb-0">info@unasfest.com</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-4" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-primary mb-1">Telepon</h5>
                                        <p class="text-muted mb-0">+62 21 1234 5678</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-4" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-primary mb-1">Alamat</h5>
                                        <p class="text-muted mb-0">Jakarta, Indonesia</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-4" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-primary mb-1">Jam Operasional</h5>
                                        <p class="text-muted mb-0">24/7 Online Support</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="text-primary mb-3">
                        <i class="bi bi-building me-2"></i>UNAS Fest 2025
                    </h5>
                    <p class="text-muted">Platform manajemen kompetisi terdepan untuk mengelola berbagai jenis kompetisi dengan mudah dan efisien.</p>
                </div>
                
                <div class="col-lg-6">
                    <h6 class="text-primary mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white">
                            <i class="bi bi-instagram fs-4"></i>
                        </a>
                        <a href="#" class="text-white">
                            <i class="bi bi-youtube fs-4"></i>
                        </a>
                        <a href="#" class="text-white">
                            <i class="bi bi-linkedin fs-4"></i>
                        </a>
                        <a href="#" class="text-white">
                            <i class="bi bi-tiktok fs-4"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="text-center">
                <p class="text-muted mb-0">&copy; 2025 UNAS Fest. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });
        
        // Countdown Timer
        function updateCountdown() {
            // Set target date (example: 30 days from now)
            const targetDate = new Date();
            targetDate.setDate(targetDate.getDate() + 30);
            
            const now = new Date().getTime();
            const distance = targetDate.getTime() - now;
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('days').textContent = days;
            document.getElementById('hours').textContent = hours;
            document.getElementById('minutes').textContent = minutes;
            document.getElementById('seconds').textContent = seconds;
        }
        
        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown();
        
        // Smooth scrolling for navigation links
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
        
        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
    </script>
</body>
</html>
