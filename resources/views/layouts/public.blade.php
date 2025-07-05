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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
            font-family: 'Poppins', sans-serif;
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

        .font-poppins {
            font-family: 'Poppins', sans-serif;
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

        /* Visitor Stats Widget */
        .visitor-stats-widget {
            margin-bottom: 1rem;
        }

        .stats-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stats-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 600;
            color: #60a5fa;
        }

        .stats-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .stat-number {
            font-size: 16px;
            font-weight: 700;
            color: #60a5fa;
        }

        /* Map Styling */
        #footer-map {
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" data-aos="fade-up" data-aos-duration="800">
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
                        <a class="nav-link {{ request()->routeIs('public.faq') ? 'active' : '' }}" href="{{ route('public.faq') }}">
                            <i class="bi bi-question-circle me-1"></i>FAQ
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
                    <li class="nav-item ms-2 pt-1">
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
        <div class="container" data-aos="fade-up" data-aos-duration="800">
            <div class="row g-4">
                <div class="col-lg-3">
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

                <div class="col-lg-2 col-md-6">
                    <h6 class="font-poppins mb-3">Kompetisi</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Teknologi</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Kesehatan</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Biodiversitas</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Panduan Pendaftaran</a></li>
                    </ul>
                </div>

                <div class="col-lg-2">
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

                <div class="col-lg-3">
                    <div class="row g-3">
                        <!-- Visitor Stats -->
                        <div class="col-12">
                            <h6 class="font-poppins mb-3">Statistik Pengunjung</h6>
                            <div class="visitor-stats-widget">
                                <div class="stats-card">
                                    <div class="stats-header">
                                        <i class="bi bi-graph-up"></i>
                                        <span>STATS</span>
                                    </div>
                                    <div class="stats-content">
                                        <div class="stat-item">
                                            <span class="stat-label">HARI INI</span>
                                            <span class="stat-number" id="today-visitors">0</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">MINGGU INI</span>
                                            <span class="stat-number" id="week-visitors">0</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">TOTAL</span>
                                            <span class="stat-number" id="total-visitors">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location Map -->
                        <div class="col-12">
                            <h6 class="font-poppins mb-3">Lokasi Kami</h6>
                            <div id="footer-map" style="height: 200px; border-radius: 10px; overflow: hidden;"></div>
                        </div>
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
    
    <!-- Google Maps API -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dO9O8nce6hq9qU&callback=initFooterMap"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
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

        // Initialize Footer Map
        function initFooterMap() {
            const universitas = { lat: -6.2697, lng: 106.8049 }; // Universitas Nasional Jakarta coordinates

            const map = new google.maps.Map(document.getElementById('footer-map'), {
                zoom: 15,
                center: universitas,
                styles: [
                    {
                        "featureType": "all",
                        "elementType": "labels.text.fill",
                        "stylers": [{"color": "#ffffff"}]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.text.stroke",
                        "stylers": [{"color": "#000000"}, {"lightness": 13}]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.fill",
                        "stylers": [{"color": "#000000"}]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [{"color": "#144b53"}, {"lightness": 14}, {"weight": 1.4}]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "all",
                        "stylers": [{"color": "#08304b"}]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [{"color": "#0c4152"}, {"lightness": 5}]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.fill",
                        "stylers": [{"color": "#000000"}]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [{"color": "#0b434f"}, {"lightness": 25}]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry.fill",
                        "stylers": [{"color": "#000000"}]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry.stroke",
                        "stylers": [{"color": "#0b3d51"}, {"lightness": 16}]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [{"color": "#000000"}]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "all",
                        "stylers": [{"color": "#146474"}]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [{"color": "#021019"}]
                    }
                ],
                disableDefaultUI: true,
                zoomControl: false,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                rotateControl: false,
                fullscreenControl: false
            });

            const marker = new google.maps.Marker({
                position: universitas,
                map: map,
                title: 'Universitas Nasional Jakarta',
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,<svg width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="16" fill="%2360a5fa"/><circle cx="16" cy="16" r="8" fill="white"/></svg>',
                    scaledSize: new google.maps.Size(32, 32)
                }
            });

            const infoWindow = new google.maps.InfoWindow({
                content: '<div style="color: #333; font-weight: 600;">Universitas Nasional Jakarta</div>'
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
        }

        // Visitor Stats Counter
        function animateCounter(element, target, duration = 2000) {
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;

            const counter = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(counter);
                } else {
                    element.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        }

        // Initialize visitor stats
        document.addEventListener('DOMContentLoaded', function() {
            // Simulated visitor data - in real implementation, fetch from API
            const visitorData = {
                today: Math.floor(Math.random() * 500) + 200,
                week: Math.floor(Math.random() * 2000) + 1500,
                total: Math.floor(Math.random() * 50000) + 130000
            };

            // Animate counters when footer is visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(document.getElementById('today-visitors'), visitorData.today);
                        animateCounter(document.getElementById('week-visitors'), visitorData.week);
                        animateCounter(document.getElementById('total-visitors'), visitorData.total);
                        observer.unobserve(entry.target);
                    }
                });
            });

            const statsWidget = document.querySelector('.visitor-stats-widget');
            if (statsWidget) {
                observer.observe(statsWidget);
            }
        });
    </script>

    @stack('scripts')

    @php $seo = app(\App\Services\SEOService::class); @endphp
    {!! $seo->generateStructuredData() !!}
</body>
</html>
