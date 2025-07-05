<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Caturnawa UNAS Fest 2025 - Festival Kompetisi Nasional Terbesar Indonesia')</title>
    <meta name="description" content="@yield('description', 'Caturnawa UNAS Fest 2025 - Festival kompetisi nasional terbesar di Indonesia. Kompetisi Debat Bahasa Indonesia, English Debate, Short Movie, dan Scientific Paper. Total hadiah 200 juta rupiah.')">
    <meta name="keywords" content="@yield('keywords', 'caturnawa, unas fest 2025, kompetisi debat indonesia, english debate competition, short movie competition, scientific paper competition, lomba nasional, festival mahasiswa')">
    <meta name="author" content="Caturnawa UNAS Fest 2025">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', 'Caturnawa UNAS Fest 2025 - Festival Kompetisi Nasional')">
    <meta property="og:description" content="@yield('og_description', 'Festival kompetisi nasional terbesar di Indonesia dengan total hadiah 200 juta rupiah')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/og/caturnawa-2025-og.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Caturnawa UNAS Fest 2025">
    <meta property="og:locale" content="id_ID">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Caturnawa UNAS Fest 2025')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Festival kompetisi nasional terbesar di Indonesia')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('assets/images/og/caturnawa-2025-twitter.jpg'))">
    <meta name="twitter:creator" content="@caturnawa2025">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Theme and App Meta -->
    <meta name="theme-color" content="#1E40AF">
    <meta name="msapplication-TileColor" content="#1E40AF">
    <meta name="application-name" content="Caturnawa 2025">
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/images/favicon/site.webmanifest') }}">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('resources/css/caturnawa-2025.css') }}">
    
    @stack('styles')
    
    <!-- JSON-LD Structured Data -->
    @stack('schema')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar" data-aos="fade-up" data-aos-duration="800">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">
                <!-- Brand -->
                <a href="{{ route('public.home') }}" class="navbar-brand" aria-label="Caturnawa UNAS Fest 2025 Home">
                    <img src="{{ asset('assets/images/logo/caturnawa-2025.svg') }}" alt="Caturnawa 2025 Logo" width="40" height="40">
                    <span>Caturnawa <strong>2025</strong></span>
                </a>
                
                <!-- Desktop Navigation -->
                <ul class="navbar-nav d-none d-lg-flex" data-aos="fade-up" data-aos-duration="800">
                    <li><a href="{{ route('public.home') }}" class="nav-link @if(request()->routeIs('public.home')) active @endif">Beranda</a></li>
                    <li><a href="{{ route('public.competitions') }}" class="nav-link @if(request()->routeIs('public.competitions')) active @endif">Kompetisi</a></li>
                    <li><a href="{{ route('public.timeline') }}" class="nav-link @if(request()->routeIs('public.timeline')) active @endif">Timeline</a></li>
                    <li><a href="{{ route('public.faq') }}" class="nav-link @if(request()->routeIs('public.faq')) active @endif">FAQ</a></li>
                    <li><a href="{{ route('public.contact') }}" class="nav-link @if(request()->routeIs('public.contact')) active @endif">Kontak</a></li>
                </ul>
                
                <!-- CTA Buttons -->
                <div class="d-none d-lg-flex align-items-center gap-2" style="gap: 1rem;">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-ghost btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('peserta.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('peserta.profile') }}">Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="d-lg-none btn btn-ghost btn-sm" type="button" id="mobileMenuBtn" aria-label="Toggle navigation">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div class="mobile-nav d-lg-none" id="mobileNav" data-aos="fade-down" data-aos-duration="800">
            <div class="container">
                <ul class="mobile-nav-list">
                    <li><a href="{{ route('public.home') }}" class="mobile-nav-link @if(request()->routeIs('public.home')) active @endif">Beranda</a></li>
                    <li><a href="{{ route('public.competitions') }}" class="mobile-nav-link @if(request()->routeIs('public.competitions')) active @endif">Kompetisi</a></li>
                    <li><a href="{{ route('public.timeline') }}" class="mobile-nav-link @if(request()->routeIs('public.timeline')) active @endif">Timeline</a></li>
                    <li><a href="{{ route('public.faq') }}" class="mobile-nav-link @if(request()->routeIs('public.faq')) active @endif">FAQ</a></li>
                    <li><a href="{{ route('public.contact') }}" class="mobile-nav-link @if(request()->routeIs('public.contact')) active @endif">Kontak</a></li>
                    <li class="mobile-nav-divider"></li>
                    @guest
                        <li><a href="{{ route('login') }}" class="mobile-nav-link">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="mobile-nav-link">Daftar</a></li>
                    @else
                        <li><a href="{{ route('peserta.dashboard') }}" class="mobile-nav-link">Dashboard</a></li>
                        <li><a href="{{ route('peserta.profile') }}" class="mobile-nav-link">Profil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="mobile-nav-link w-100 text-left">Keluar</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main id="main-content">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer" data-aos="fade-up" data-aos-duration="800">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6 mb-6">
                    <div class="footer-section">
                        <a href="{{ route('public.home') }}" class="footer-brand">
                            <img src="{{ asset('assets/images/logo/caturnawa-2025-white.svg') }}" alt="Caturnawa 2025" width="40" height="40">
                            <span>Caturnawa <strong>2025</strong></span>
                        </a>
                        <p class="footer-description">
                            Festival kompetisi nasional terbesar di Indonesia yang menggabungkan Debat, Film Pendek, dan Karya Ilmiah untuk mengembangkan talenta terbaik bangsa.
                        </p>
                        <div class="footer-social">
                            <a href="https://instagram.com/caturnawa2025" target="_blank" aria-label="Instagram Caturnawa">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="https://linkedin.com/company/caturnawa" target="_blank" aria-label="LinkedIn Caturnawa">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            <a href="https://tiktok.com/@caturnawa2025" target="_blank" aria-label="TikTok Caturnawa">
                                <i class="bi bi-tiktok"></i>
                            </a>
                            <a href="https://youtube.com/@caturnawa2025" target="_blank" aria-label="YouTube Caturnawa">
                                <i class="bi bi-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-6">
                    <div class="footer-section">
                        <h5 class="footer-title">Navigasi</h5>
                        <ul class="footer-links">
                            <li><a href="{{ route('public.home') }}">Beranda</a></li>
                            <li><a href="{{ route('public.competitions') }}">Kompetisi</a></li>
                            <li><a href="{{ route('public.timeline') }}">Timeline</a></li>
                            <li><a href="{{ route('public.faq') }}">FAQ</a></li>
                            <li><a href="{{ route('public.contact') }}">Kontak</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Competitions -->
                <div class="col-lg-3 col-md-6 mb-6">
                    <div class="footer-section">
                        <h5 class="footer-title">Kompetisi</h5>
                        <ul class="footer-links">
                            <li><a href="{{ route('public.competitions') }}#kdbi">Kompetisi Debat Bahasa Indonesia</a></li>
                            <li><a href="{{ route('public.competitions') }}#edc">English Debate Competition</a></li>
                            <li><a href="{{ route('public.competitions') }}#sm">Short Movie Competition</a></li>
                            <li><a href="{{ route('public.competitions') }}#spc">Scientific Paper Competition</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6 mb-6">
                    <div class="footer-section">
                        <h5 class="footer-title">Kontak</h5>
                        <div class="footer-contact">
                            <div class="contact-item">
                                <i class="bi bi-geo-alt"></i>
                                <span>Universitas Nasional<br>Jakarta Selatan, Indonesia</span>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-envelope"></i>
                                <span>info@caturnawa2025.com</span>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-phone"></i>
                                <span>+62 21 7806700</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="footer-copyright">
                            &copy; {{ date('Y') }} Caturnawa UNAS Fest. Semua hak cipta dilindungi.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="footer-legal">
                            <li><a href="{{ route('public.privacy') }}">Kebijakan Privasi</a></li>
                            <li><a href="{{ route('public.terms') }}">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Kembali ke atas">
        <i class="bi bi-arrow-up"></i>
    </button>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    
    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/caturnawa-2025.js') }}"></script>
    
    @stack('scripts')
    
    <!-- Analytics -->
    @if(config('app.env') === 'production')
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID');

        AOS.init({
        duration: 800,
        once: true
    });
    </script>
    @endif
</body>
</html>