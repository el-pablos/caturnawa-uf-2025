            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            scroll-behavior: smooth;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .hero-modern {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        }
    </style>

    <!-- External CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Title -->
    <title>@stack('title', 'UNAS Fest 2025 - Festival Kompetisi Nasional')</title>

    @stack('styles')
</head>
<body>
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="visually-hidden-focusable">Skip to main content</a>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('public.home') }}" aria-label="UNAS Fest 2025 Home">
                <img src="{{ asset('assets/images/logo/unas-fest-logo.webp') }}" 
                     alt="UNAS Fest 2025 Logo" 
                     height="40" 
                     width="40" 
                     class="me-2"
                     loading="eager"
                     onerror="this.style.display='none'">
                <span class="fw-bold text-primary fs-4">UNAS Fest 2025</span>
            </a>
            
            <button class="navbar-toggler border-0" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav" 
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}" 
                           href="{{ route('public.home') }}"
                           aria-current="{{ request()->routeIs('public.home') ? 'page' : 'false' }}">
                            <i class="bi bi-house-door me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.competitions') ? 'active' : '' }}" 
                           href="{{ route('public.competitions') }}">
                            <i class="bi bi-trophy me-1"></i>Kompetisi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" 
                           href="{{ route('public.about') }}">
                            <i class="bi bi-people me-1"></i>Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.testimonials') ? 'active' : '' }}" 
                           href="{{ route('public.testimonials') }}">
                            <i class="bi bi-chat-quote me-1"></i>Testimoni
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.blog') ? 'active' : '' }}" 
                           href="{{ route('public.blog') }}">
                            <i class="bi bi-journal-text me-1"></i>Blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" 
                           href="{{ route('public.contact') }}">
                            <i class="bi bi-envelope me-1"></i>Kontak
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary rounded-pill px-3" 
                           href="{{ route('login') }}"
                           aria-label="Masuk ke dashboard">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-modern">
        <div class="container">
            <!-- Main Footer Content -->
            <div class="row g-4 py-5">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('assets/images/logo/unas-fest-logo-white.webp') }}" 
                                 alt="UNAS Fest 2025" 
                                 height="40" 
                                 width="40" 
                                 class="me-3"
                                 loading="lazy"
                                 onerror="this.style.display='none'">
                            <h5 class="text-white fw-bold mb-0">UNAS Fest 2025</h5>
                        </div>
                        <p class="text-light mb-4">
                            Festival kompetisi nasional terbesar di Indonesia yang menggabungkan 
                            teknologi, kesehatan, dan biodiversitas untuk masa depan berkelanjutan.
                        </p>
                        <div class="social-links">
                            <a href="https://instagram.com/unasfest" 
                               class="social-link" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               aria-label="Follow UNAS Fest di Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="https://youtube.com/@unasfest" 
                               class="social-link" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               aria-label="Subscribe YouTube UNAS Fest">
                                <i class="bi bi-youtube"></i>
                            </a>
                            <a href="https://linkedin.com/company/unasfest" 
                               class="social-link" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               aria-label="Follow UNAS Fest di LinkedIn">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            <a href="https://tiktok.com/@unasfest" 
                               class="social-link" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               aria-label="Follow UNAS Fest di TikTok">
                                <i class="bi bi-tiktok"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="footer-title">Navigasi</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('public.home') }}">Beranda</a></li>
                        <li><a href="{{ route('public.competitions') }}">Kompetisi</a></li>
                        <li><a href="{{ route('public.about') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('public.testimonials') }}">Testimoni</a></li>
                        <li><a href="{{ route('public.blog') }}">Blog</a></li>
                        <li><a href="{{ route('public.contact') }}">Kontak</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-title">Kompetisi</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('public.competitions') }}#technology">Teknologi</a></li>
                        <li><a href="{{ route('public.competitions') }}#health">Kesehatan</a></li>
                        <li><a href="{{ route('public.competitions') }}#biodiversity">Biodiversitas</a></li>
                        <li><a href="{{ route('public.faq') }}">FAQ</a></li>
                        <li><a href="#timeline">Timeline</a></li>
                        <li><a href="#benefits">Benefits</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-title">Kontak</h6>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:info@unasfest.com">info@unasfest.com</a>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-telephone"></i>
                            <a href="tel:+62218690406">+62 21 8690 406</a>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-geo-alt"></i>
                            <span>Jl. Sawo Manila, Pejaten Timur<br>Jakarta Selatan 12520</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-light">
                            &copy; {{ date('Y') }} UNAS Fest 2025. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="footer-legal">
                            <a href="{{ route('public.privacy') }}">Privacy Policy</a>
                            <a href="{{ route('public.terms') }}">Terms of Service</a>
                            <a href="/sitemap.xml">Sitemap</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" 
            class="back-to-top" 
            aria-label="Back to top"
            style="display: none;">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Loading Spinner -->
    <div id="pageLoader" class="page-loader">
        <div class="loader-content">
            <div class="spinner"></div>
            <p>Loading UNAS Fest 2025...</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Remove loading spinner
            const loader = document.getElementById('pageLoader');
            if (loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 300);
                }, 500);
            }

            // Navbar scroll effect
            const navbar = document.getElementById('mainNavbar');
            let lastScrollTop = 0;
            
            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 100) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
                
                // Hide/show navbar on scroll
                if (scrollTop > lastScrollTop && scrollTop > 200) {
                    navbar.style.transform = 'translateY(-100%)';
                } else {
                    navbar.style.transform = 'translateY(0)';
                }
                
                lastScrollTop = scrollTop;
            });

            // Back to top button
            const backToTop = document.getElementById('backToTop');
            
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    backToTop.style.display = 'flex';
                } else {
                    backToTop.style.display = 'none';
                }
            });

            backToTop.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const offsetTop = target.offsetTop - 80;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Mobile menu close on click
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');

            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (navbarCollapse.classList.contains('show')) {
                        navbarToggler.click();
                    }
                });
            });

            // Intersection Observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, observerOptions);

            // Observe all animatable elements
            document.querySelectorAll('[data-aos]').forEach(el => {
                observer.observe(el);
            });

            // Preload images
            const images = document.querySelectorAll('img[loading="lazy"]');
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));

            // Error handling for failed image loads
            document.querySelectorAll('img').forEach(img => {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                });
            });

            // CSRF token setup for AJAX requests
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.axios = window.axios || {};
                window.axios.defaults = window.axios.defaults || {};
                window.axios.defaults.headers = window.axios.defaults.headers || {};
                window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
            }
        });

        // Performance monitoring
        window.addEventListener('load', () => {
            if ('performance' in window) {
                const navigation = performance.getEntriesByType('navigation')[0];
                if (navigation.loadEventEnd - navigation.loadEventStart > 3000) {
                    console.warn('Page load time is slow:', navigation.loadEventEnd - navigation.loadEventStart, 'ms');
                }
            }
        });

        // Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => console.log('SW registered'))
                    .catch(error => console.log('SW registration failed'));
            });
        }
    </script>

    @stack('scripts')

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "UNAS Fest 2025",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('assets/images/logo/unas-fest-logo.webp') }}",
        "description": "Festival Kompetisi Nasional terbesar di Indonesia",
        "sameAs": [
            "https://instagram.com/unasfest",
            "https://youtube.com/@unasfest",
            "https://linkedin.com/company/unasfest",
            "https://tiktok.com/@unasfest"
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+62218690406",
            "contactType": "customer service",
            "email": "info@unasfest.com"
        },
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Jl. Sawo Manila, Pejaten Timur",
            "addressLocality": "Jakarta Selatan",
            "postalCode": "12520",
            "addressCountry": "ID"
        }
    }
    </script>

    <!-- Additional Styles -->
    <style>
        /* Navbar Styles */
        .navbar {
            transition: all 0.3s ease;
            padding: 1rem 0;
        }

        .navbar-scrolled {
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--primary) !important;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            color: #64748b !important;
            margin: 0 0.5rem;
            padding: 0.75rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary) !important;
            background: rgba(37, 99, 235, 0.1);
            transform: translateY(-1px);
        }

        .navbar-toggler {
            border: none;
            padding: 0.25rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Footer Styles */
        .footer-modern {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .footer-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        }

        .footer-title {
            color: #f8fafc;
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            color: #60a5fa;
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e2e8f0;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .social-link:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: #cbd5e1;
            font-size: 0.9rem;
        }

        .contact-item i {
            color: #60a5fa;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        .contact-item a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-item a:hover {
            color: #60a5fa;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            margin-top: 2rem;
        }

        .footer-legal {
            display: flex;
            gap: 1.5rem;
        }

        .footer-legal a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-legal a:hover {
            color: #60a5fa;
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
            cursor: pointer;
        }

        .back-to-top:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.6);
        }

        /* Page Loader */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }

        .loader-content {
            text-align: center;
            color: white;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        .loader-content p {
            font-size: 1.1rem;
            font-weight: 500;
            margin: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Accessibility */
        .visually-hidden-focusable:not(:focus):not(:focus-within) {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                border-radius: 1rem;
                padding: 1rem;
                margin-top: 1rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                border: 1px solid rgba(0, 0, 0, 0.05);
            }

            .navbar-nav .nav-link {
                margin: 0.25rem 0;
            }

            .footer-legal {
                flex-direction: column;
                gap: 0.5rem;
                margin-top: 1rem;
            }

            .social-links {
                justify-content: center;
                margin-top: 1rem;
            }
        }

        @media (max-width: 576px) {
            .back-to-top {
                width: 45px;
                height: 45px;
                bottom: 1rem;
                right: 1rem;
            }

            .footer-modern {
                text-align: center;
            }

            .contact-item {
                justify-content: center;
            }
        }

        /* Performance optimizations */
        img {
            max-width: 100%;
            height: auto;
        }

        /* Print styles */
        @media print {
            .navbar,
            .footer-modern,
            .back-to-top,
            .page-loader {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            :root {
                color-scheme: dark;
            }
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }

            .spinner {
                animation: none !important;
            }
        }
    </style>
</body>
</html>
