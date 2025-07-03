<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $seo = app(\App\Services\SEOService::class);
        if(isset($seoPage)) {
            $seo->setPage($seoPage);
        }
        if(isset($seoData)) {
            $seo->setCustomData($seoData);
        }
    @endphp

    {!! $seo->generateMetaTags() !!}
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #3b82f6;
            --accent-color: #60a5fa;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            font-weight: 500;
            color: #64748b !important;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-1px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link.active {
            color: var(--primary-color) !important;
            font-weight: 600;
        }

        .nav-link.active::after {
            width: 100%;
        }

        /* Footer Styles */
        .main-footer {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .main-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.05) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 2px, transparent 2px);
            background-size: 50px 50px;
            animation: patternMove 20s linear infinite;
        }

        @keyframes patternMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .footer-content {
            position: relative;
            z-index: 2;
            padding: 4rem 0 2rem;
        }

        .footer-section h5 {
            color: var(--accent-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .footer-section h5::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
            border-radius: 2px;
        }

        .footer-section p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section ul li {
            margin-bottom: 0.75rem;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-section ul li a:hover {
            color: var(--accent-color);
            transform: translateX(5px);
        }

        .footer-section ul li a i {
            width: 16px;
            text-align: center;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .social-link:hover {
            background: var(--accent-color);
            color: white;
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 10px 20px rgba(96, 165, 250, 0.3);
        }

        .map-container {
            height: 300px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
            filter: grayscale(20%) contrast(1.1);
            transition: filter 0.3s ease;
        }

        .map-container:hover iframe {
            filter: grayscale(0%) contrast(1.2);
        }

        .visitor-counter {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            min-height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .visitor-counter::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .visitor-counter:hover::before {
            transform: translateX(100%);
        }

        .visitor-counter h6 {
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .counter-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .counter-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .counter-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            display: block;
            margin-bottom: 0.25rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .counter-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .total-visitors {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            border-radius: 10px;
            padding: 0.75rem;
            margin-top: 1rem;
        }

        .total-visitors .counter-number {
            font-size: 2.2rem;
            margin-bottom: 0;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem 0;
            margin-top: 2rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .footer-bottom p {
            margin: 0;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .footer-bottom a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
        }

        .footer-bottom a:hover {
            color: white;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .footer-content {
                padding: 2rem 0 1rem;
            }

            .map-container,
            .visitor-counter {
                min-height: 200px;
                margin-bottom: 2rem;
            }

            .visitor-counter {
                padding: 1rem;
            }

            .counter-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .counter-item {
                padding: 0.75rem;
            }

            .counter-number {
                font-size: 1.5rem;
            }

            .social-links {
                justify-content: center;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('public.home') }}">
                <strong>UNAS Fest 2025</strong>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}" href="{{ route('public.home') }}">
                            <i class="bi bi-house me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.competitions*') ? 'active' : '' }}" href="{{ route('public.competitions') }}">
                            <i class="bi bi-trophy me-1"></i>Kompetisi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">
                            <i class="bi bi-info-circle me-1"></i>Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">
                            <i class="bi bi-envelope me-1"></i>Kontak
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary btn-sm px-3" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 80px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-section">
                            <h5>UNAS Fest 2025</h5>
                            <p>Festival Kompetisi Nasional terbesar di Indonesia yang menggabungkan inovasi teknologi, kesehatan, dan biodiversitas untuk membentuk masa depan bangsa.</p>
                            <div class="social-links">
                                <a href="https://facebook.com/unasfest" class="social-link" target="_blank" rel="noopener">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://instagram.com/unasfest" class="social-link" target="_blank" rel="noopener">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="https://twitter.com/unasfest" class="social-link" target="_blank" rel="noopener">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="https://youtube.com/unasfest" class="social-link" target="_blank" rel="noopener">
                                    <i class="bi bi-youtube"></i>
                                </a>
                                <a href="https://linkedin.com/company/unasfest" class="social-link" target="_blank" rel="noopener">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-section">
                            <h5>Kompetisi</h5>
                            <ul>
                                <li><a href="{{ route('public.competitions') }}#technology"><i class="bi bi-laptop"></i>Teknologi</a></li>
                                <li><a href="{{ route('public.competitions') }}#health"><i class="bi bi-heart-pulse"></i>Kesehatan</a></li>
                                <li><a href="{{ route('public.competitions') }}#biodiversity"><i class="bi bi-tree"></i>Biodiversitas</a></li>
                                <li><a href="{{ route('public.competitions') }}"><i class="bi bi-list"></i>Semua Kompetisi</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-section">
                            <h5>Informasi</h5>
                            <ul>
                                <li><a href="{{ route('public.about') }}"><i class="bi bi-info-circle"></i>Tentang Kami</a></li>
                                <li><a href="{{ route('public.faq') }}"><i class="bi bi-question-circle"></i>FAQ</a></li>
                                <li><a href="{{ route('public.terms') }}"><i class="bi bi-file-text"></i>Syarat & Ketentuan</a></li>
                                <li><a href="{{ route('public.privacy') }}"><i class="bi bi-shield-check"></i>Kebijakan Privasi</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-section">
                            <h5>Kontak</h5>
                            <ul>
                                <li><a href="mailto:info@unasfest.com"><i class="bi bi-envelope"></i>info@unasfest.com</a></li>
                                <li><a href="tel:0858-1737-8442"><i class="bi bi-telephone"></i>0858-1737-8442</a></li>
                                <li><a href="{{ route('public.contact') }}"><i class="bi bi-chat-dots"></i>Hubungi Kami</a></li>
                                <li><a href="https://wa.me/6285817378442" target="_blank"><i class="bi bi-whatsapp"></i>WhatsApp</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-8 mb-4">
                        <div class="footer-section">
                            <h5>Lokasi Kami</h5>
                            <div class="map-container">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7037296897583!2d106.83746531476911!3d-6.301582695377932!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ecbc1b26f799%3A0x5b99b2d1c95b8c7d!2sJl.%20Sawo%20Manila%20No.61%2C%20RT.14%2FRW.7%2C%20Pejaten%20Bar.%2C%20Ps.%20Minggu%2C%20Kota%20Jakarta%20Selatan%2C%20Daerah%20Khusus%20Ibukota%20Jakarta%2012520!5e0!3m2!1sid!2sid!4v1625123456789!5m2!1sid!2sid"
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Lokasi Universitas Nasional Jakarta - Jl. Sawo Manila No.61">
                                </iframe>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="footer-section">
                            <h5>Statistik Pengunjung</h5>
                            <div class="visitor-counter">
                                <h6><i class="bi bi-graph-up me-2"></i>Data Real-time</h6>
                                <div class="counter-grid">
                                    <div class="counter-item">
                                        <span class="counter-number" data-target="{{ $visitorStats['today'] ?? 0 }}">0</span>
                                        <span class="counter-label">Hari Ini</span>
                                    </div>
                                    <div class="counter-item">
                                        <span class="counter-number" data-target="{{ $visitorStats['this_week'] ?? 0 }}">0</span>
                                        <span class="counter-label">Minggu Ini</span>
                                    </div>
                                </div>
                                <div class="total-visitors">
                                    <span class="counter-number" data-target="{{ $visitorStats['total'] ?? 0 }}">0</span>
                                    <span class="counter-label">Total Pengunjung</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-md-start text-center mb-2 mb-md-0">
                        <p>&copy; 2025 <a href="{{ route('public.home') }}">UNAS Fest</a>. Seluruh hak cipta dilindungi.</p>
                    </div>
                    <div class="col-md-6 text-md-end text-center">
                        <p>Dikembangkan oleh <a href="https://unasfest.com" target="_blank">Tim UNAS Fest</a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.counter-number');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const target = parseInt(counter.getAttribute('data-target'));
                        const duration = 2000;
                        const step = target / (duration / 16);
                        let current = 0;

                        const updateCounter = () => {
                            current += step;
                            if (current < target) {
                                counter.textContent = Math.floor(current).toLocaleString();
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.textContent = target.toLocaleString();
                            }
                        };

                        updateCounter();
                        observer.unobserve(counter);
                    }
                });
            });

            counters.forEach(counter => observer.observe(counter));
        }

        // Navbar scroll effect
        function handleNavbarScroll() {
            const navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                    navbar.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.15)';
                } else {
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                    navbar.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
                }
            });
        }

        // Smooth scrolling for footer links
        function initSmoothScrolling() {
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
        }

        // Initialize all functions
        document.addEventListener('DOMContentLoaded', function() {
            animateCounters();
            handleNavbarScroll();
            initSmoothScrolling();
        });

        // Add loading states for external links
        document.querySelectorAll('a[target="_blank"]').forEach(link => {
            link.addEventListener('click', function() {
                this.style.opacity = '0.7';
                setTimeout(() => {
                    this.style.opacity = '1';
                }, 300);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>