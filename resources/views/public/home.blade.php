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
        .hero-section {
            background: #00BCD4;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="120" fill="url(%23a)"/></svg>') no-repeat center center;
            background-size: cover;
            opacity: 0.3;
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
        }
        
        .competition-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--unas-shadow-xl);
        }
        
        .competition-card .card-header {
            background: #00BCD4;
            color: white;
            border: none;
            padding: var(--unas-space-4);
        }
        
        .stats-counter {
            font-size: 3rem;
            font-weight: 800;
            color: #00BCD4;
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: #00BCD4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto var(--unas-space-4);
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
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="display-3 fw-bold text-white mb-4">
                        Selamat Datang di<br>
                        <span class="text-warning">UNAS Fest 2025</span>
                    </h1>
                    <p class="lead text-white mb-5">
                        Platform manajemen kompetisi terdepan untuk mengelola pendaftaran, 
                        pembayaran, dan penilaian kompetisi dengan mudah dan efisien.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn-cta">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </a>
                        <a href="#kompetisi" class="unas-btn-secondary">
                            <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="floating-card unas-card p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-trophy fs-1 text-primary mb-3"></i>
                            <h3 class="text-primary">Sistem Terpadu</h3>
                        </div>
                        
                        <!-- Countdown Timer -->
                        <div class="row g-3 mb-4">
                            <div class="col-3">
                                <div class="countdown-item">
                                    <div class="countdown-number" id="days">30</div>
                                    <div class="countdown-label">Hari</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="countdown-item">
                                    <div class="countdown-number" id="hours">12</div>
                                    <div class="countdown-label">Jam</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="countdown-item">
                                    <div class="countdown-number" id="minutes">45</div>
                                    <div class="countdown-label">Menit</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="countdown-item">
                                    <div class="countdown-number" id="seconds">30</div>
                                    <div class="countdown-label">Detik</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-muted mb-0">Menuju pembukaan pendaftaran</p>
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
                    <div class="stats-counter">1000+</div>
                    <h5 class="text-muted">Peserta Terdaftar</h5>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stats-counter">50+</div>
                    <h5 class="text-muted">Kompetisi Aktif</h5>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stats-counter">25+</div>
                    <h5 class="text-muted">Universitas</h5>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="stats-counter">100M+</div>
                    <h5 class="text-muted">Total Hadiah</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- Competitions Section -->
    <section id="kompetisi" class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 fw-bold text-primary mb-3">Kompetisi Unggulan</h2>
                <p class="lead text-muted">Ikuti berbagai kompetisi menarik dengan hadiah jutaan rupiah</p>
            </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="competition-card card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-mic me-2"></i>Kompetisi Debat Bahasa Indonesia
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Kompetisi debat untuk mengasah kemampuan berbicara dan berargumentasi dalam bahasa Indonesia.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="unas-badge-primary">Tim</span>
                                <span class="text-primary fw-bold">Rp 10.000.000</span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="#" class="unas-btn-primary w-100">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="competition-card card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-camera-video me-2"></i>Short Movie Competition
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Kompetisi pembuatan film pendek dengan tema kreativitas dan inovasi.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="unas-badge-success">Individu</span>
                                <span class="text-primary fw-bold">Rp 15.000.000</span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="#" class="unas-btn-primary w-100">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="competition-card card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-journal-text me-2"></i>Scientific Paper Competition
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Kompetisi penulisan karya tulis ilmiah dengan standar akademik tinggi.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="unas-badge-warning">Tim</span>
                                <span class="text-primary fw-bold">Rp 12.000.000</span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="#" class="unas-btn-primary w-100">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
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
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4 class="text-primary mb-3">Keamanan Terjamin</h4>
                        <p class="text-muted">Sistem keamanan berlapis dengan enkripsi data dan proteksi pembayaran yang aman.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="bi bi-lightning"></i>
                        </div>
                        <h4 class="text-primary mb-3">Proses Cepat</h4>
                        <p class="text-muted">Pendaftaran dan pembayaran dapat diselesaikan dalam hitungan menit.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h4 class="text-primary mb-3">Support 24/7</h4>
                        <p class="text-muted">Tim support siap membantu Anda kapan saja melalui berbagai channel komunikasi.</p>
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
