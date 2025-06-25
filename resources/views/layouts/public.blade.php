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
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-trophy-fill me-2"></i>UNAS Fest 2025
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#competitions">
                            <i class="bi bi-trophy me-1"></i>Kompetisi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="bi bi-info-circle me-1"></i>Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="bi bi-envelope me-1"></i>Kontak
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Daftar
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->hasRole(['Super Admin', 'Admin']))
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('peserta.dashboard') }}">
                                    <i class="bi bi-person-workspace me-2"></i>Dashboard Peserta
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person me-2"></i>Profil
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 76px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5>UNAS Fest 2025</h5>
                    <p class="text-muted">
                        Festival kompetisi nasional yang menggabungkan inovasi teknologi, 
                        kesehatan, dan biodiversitas untuk masa depan yang berkelanjutan.
                    </p>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-linkedin fs-4"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Kompetisi</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Biodiversity</a></li>
                        <li><a href="#">Health</a></li>
                        <li><a href="#">Technology</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Informasi</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Kontak</h5>
                    <p class="text-muted">
                        <i class="bi bi-geo-alt me-2"></i>
                        Universitas Nasional, Jakarta Selatan
                    </p>
                    <p class="text-muted">
                        <i class="bi bi-envelope me-2"></i>
                        info@unasfest.com
                    </p>
                    <p class="text-muted">
                        <i class="bi bi-phone me-2"></i>
                        +62 21 1234 5678
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2025 UNAS Fest. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">Made with <i class="bi bi-heart-fill text-danger"></i> by UNAS Team</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    @stack('scripts')
</body>
</html>
