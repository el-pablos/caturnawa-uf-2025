<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
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
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
            color: #64748b !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: var(--primary-color) !important;
            font-weight: 600;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.9) 0%, rgba(59, 130, 246, 0.8) 100%);
            color: white;
            padding: 6rem 0;
            text-align: center;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--dark-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            text-align: center;
            padding: 2rem;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stats-label {
            font-size: 1rem;
            color: #64748b;
            font-weight: 500;
        }

        .competition-card {
            height: 100%;
            overflow: hidden;
        }

        .competition-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .badge-category {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(37, 99, 235, 0.9);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .footer {
            background: var(--dark-color);
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer h5 {
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--accent-color);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--dark-color);
        }

        .section-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-section {
                padding: 4rem 0;
            }

            .stats-number {
                font-size: 2rem;
            }

            .navbar-nav {
                max-height: 70vh;
                overflow-y: auto;
            }

            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                margin-top: 1rem;
                border-radius: 10px;
                padding: 1rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .nav-link {
                padding: 0.75rem 1rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }

            .nav-link:last-child {
                border-bottom: none;
            }
        }

        /* Scrollbar styling for mobile navbar */
        .navbar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .navbar-nav::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .navbar-nav::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand font-poppins" href="{{ route('public.home') }}">
                <img src="{{ asset('assets/images/logo/unas-fest-logo.png') }}" alt="UNAS Fest 2025" height="40" class="me-2"
                     onerror="this.style.display='none'">
                UNAS Fest 2025
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
                        <a class="nav-link {{ request()->routeIs('public.competitions') ? 'active' : '' }}" href="{{ route('public.competitions') }}">
                            <i class="bi bi-trophy me-1"></i>Kompetisi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">
                            <i class="bi bi-people me-1"></i>Tentang Kami
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.testimonials') ? 'active' : '' }}" href="{{ route('public.testimonials') }}">
                            <i class="bi bi-chat-quote me-1"></i>Testimoni
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.blog') ? 'active' : '' }}" href="{{ route('public.blog') }}">
                            <i class="bi bi-journal-text me-1"></i>Blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">
                            <i class="bi bi-envelope me-1"></i>Kontak
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 76px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('assets/images/logo/unas-fest-logo-white.png') }}" alt="UNAS Fest 2025" height="40" class="me-3"
                             onerror="this.style.display='none'">
                        <h5 class="font-poppins mb-0">UNAS Fest 2025</h5>
                    </div>
                    <p class="text-light mb-3">Festival kompetisi nasional terbesar di Indonesia yang menggabungkan teknologi, kesehatan, dan biodiversitas.</p>
                    <div class="d-flex gap-3">
                        @php $seo = app(\App\Services\SEOService::class); @endphp
                        @foreach($seo->getSocialLinks() as $platform => $url)
                            <a href="{{ $url }}" class="text-white" target="_blank" rel="noopener">
                                <i class="bi bi-{{ $platform }} fs-5"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="font-poppins mb-3">Navigasi</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('public.home') }}" class="text-light text-decoration-none">Beranda</a></li>
                        <li><a href="{{ route('public.competitions') }}" class="text-light text-decoration-none">Kompetisi</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-light text-decoration-none">Tentang Kami</a></li>
                        <li><a href="{{ route('public.testimonials') }}" class="text-light text-decoration-none">Testimoni</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="font-poppins mb-3">Kompetisi</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Teknologi</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Kesehatan</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Biodiversitas</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Panduan Pendaftaran</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h6 class="font-poppins mb-3">Kontak</h6>
                    @php $contact = $seo->getContactInfo(); @endphp
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        <a href="mailto:{{ $contact['email'] }}" class="text-light text-decoration-none">{{ $contact['email'] }}</a>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        <a href="tel:{{ $contact['phone'] }}" class="text-light text-decoration-none">{{ $contact['phone'] }}</a>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="bi bi-geo-alt me-2 mt-1"></i>
                        <span class="text-light">{{ $contact['address'] }}</span>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-light">&copy; {{ date('Y') }} UNAS Fest 2025. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-light text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button id="backToTop" class="btn btn-primary position-fixed bottom-0 end-0 m-4 rounded-circle d-none" style="z-index: 1000;">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 120,
        });

        // Back to top button
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.remove('d-none');
            } else {
                backToTop.classList.add('d-none');
            }
        });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
                navbar.style.boxShadow = 'var(--shadow-md)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = 'none';
            }
        });
    </script>

    @stack('scripts')

    @php $seo = app(\App\Services\SEOService::class); @endphp
    {!! $seo->generateStructuredData() !!}
</body>
</html>
