@extends('layouts.modern')

@section('title', 'UNAS Fest 2025 - Festival Kompetisi Nasional Terbesar Indonesia')

@push('meta')
<meta name="description" content="UNAS Fest 2025 - Festival Kompetisi Nasional terbesar di Indonesia. Bergabunglah dengan kompetisi Teknologi, Kesehatan, dan Biodiversitas. Hadiah total 500 juta rupiah.">
<meta name="keywords" content="UNAS Fest 2025, kompetisi nasional, teknologi, kesehatan, biodiversitas, universitas nasional, festival mahasiswa, lomba, hadiah">
<meta name="author" content="Universitas Nasional">
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="UNAS Fest 2025 - Festival Kompetisi Nasional">
<meta property="og:description" content="Festival Kompetisi Nasional terbesar di Indonesia. Bergabunglah dengan kompetisi Teknologi, Kesehatan, dan Biodiversitas. Hadiah total 500 juta rupiah.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/') }}">
<meta property="og:image" content="{{ asset('assets/images/og/unas-fest-2025-og.jpg') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="UNAS Fest 2025">
<meta property="og:locale" content="id_ID">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="UNAS Fest 2025 - Festival Kompetisi Nasional">
<meta name="twitter:description" content="Festival Kompetisi Nasional terbesar di Indonesia. Bergabunglah dengan kompetisi Teknologi, Kesehatan, dan Biodiversitas.">
<meta name="twitter:image" content="{{ asset('assets/images/og/unas-fest-2025-twitter.jpg') }}">
<meta name="twitter:creator" content="@unasfest">

<!-- Canonical URL -->
<link rel="canonical" href="{{ url('/') }}">

<!-- Additional SEO Tags -->
<meta name="theme-color" content="#2563eb">
<meta name="msapplication-TileColor" content="#2563eb">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('assets/images/favicon/site.webmanifest') }}">

<!-- JSON-LD Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Event",
  "name": "UNAS Fest 2025",
  "description": "Festival Kompetisi Nasional terbesar di Indonesia yang menggabungkan Teknologi, Kesehatan, dan Biodiversitas",
  "startDate": "2025-01-01",
  "endDate": "2025-03-30",
  "eventAttendanceMode": "https://schema.org/MixedEventAttendanceMode",
  "eventStatus": "https://schema.org/EventScheduled",
  "location": {
    "@type": "Place",
    "name": "Universitas Nasional",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Jl. Sawo Manila, Pejaten Timur",
      "addressLocality": "Jakarta Selatan",
      "postalCode": "12520",
      "addressCountry": "ID"
    }
  },
  "organizer": {
    "@type": "Organization",
    "name": "Universitas Nasional",
    "url": "https://unas.ac.id"
  },
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "IDR",
    "availability": "https://schema.org/InStock",
    "validFrom": "2025-01-01"
  }
}
</script>
@endpush

@section('content')
<!-- Hero Section with Modern Design -->
<section id="hero" class="hero-modern">
    <div class="hero-background">
        <div class="hero-particles"></div>
        <div class="hero-gradient"></div>
    </div>
    
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                <div class="hero-content">
                    <!-- Badge -->
                    <div class="hero-badge" data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-star-fill"></i>
                        <span>Festival Kompetisi Nasional #1</span>
                    </div>
                    
                    <!-- Main Title -->
                    <h1 class="hero-title" data-aos="fade-up" data-aos-delay="400">
                        <span class="title-main">UNAS Fest</span>
                        <span class="title-year">2025</span>
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="600">
                        Bergabunglah dengan festival kompetisi nasional terbesar di Indonesia yang menggabungkan
                        <span class="highlight-tech">Teknologi</span>,
                        <span class="highlight-health">Kesehatan</span>, dan
                        <span class="highlight-bio">Biodiversitas</span>
                        untuk masa depan berkelanjutan.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="hero-actions" data-aos="fade-up" data-aos-delay="800">
                        <a href="{{ route('public.competitions') }}" class="btn btn-primary btn-cta" aria-label="Daftar kompetisi UNAS Fest 2025">
                            <span class="btn-text">Daftar Kompetisi</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="#kompetisi" class="btn btn-outline btn-cta" aria-label="Lihat informasi kompetisi">
                            <span class="btn-text">Lihat Kompetisi</span>
                            <i class="bi bi-play-circle"></i>
                        </a>
                    </div>
                    
                    <!-- Quick Info -->
                    <div class="hero-info" data-aos="fade-up" data-aos-delay="1000">
                        <div class="info-item">
                            <i class="bi bi-calendar-event"></i>
                            <div>
                                <span class="info-label">Pendaftaran</span>
                                <span class="info-value">1 Jan - 28 Feb 2025</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-gift"></i>
                            <div>
                                <span class="info-label">Total Hadiah</span>
                                <span class="info-value">500 Juta Rupiah</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="400">
                <div class="hero-visual">
                    <!-- Main Illustration -->
                    <div class="hero-illustration">
                        <img src="{{ asset('assets/images/hero/hero-2025.webp') }}" 
                             alt="UNAS Fest 2025 - Festival Kompetisi Nasional" 
                             class="hero-image"
                             loading="eager"
                             width="600"
                             height="500">
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="floating-stats">
                        <div class="stat-card stat-1" data-aos="zoom-in" data-aos-delay="1200">
                            <div class="stat-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-number">10K+</span>
                                <span class="stat-label">Peserta</span>
                            </div>
                        </div>
                        
                        <div class="stat-card stat-2" data-aos="zoom-in" data-aos-delay="1400">
                            <div class="stat-icon">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-number">15</span>
                                <span class="stat-label">Kategori</span>
                            </div>
                        </div>
                        
                        <div class="stat-card stat-3" data-aos="zoom-in" data-aos-delay="1600">
                            <div class="stat-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-number">100+</span>
                                <span class="stat-label">Universitas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="scroll-indicator" data-aos="fade-up" data-aos-delay="2000">
        <a href="#stats" class="scroll-link" aria-label="Scroll ke statistik">
            <span>Scroll untuk lebih banyak</span>
            <div class="scroll-arrow">
                <i class="bi bi-chevron-down"></i>
            </div>
        </a>
    </div>
</section>

<!-- Statistics Section -->
<section id="stats" class="stats-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2 class="section-title">Mengapa UNAS Fest 2025?</h2>
            <p class="section-subtitle">Data dan fakta yang menunjukkan skala besar festival kompetisi ini</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card">
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number counter" data-target="10000">0</h3>
                        <p class="stats-label">Peserta Terdaftar</p>
                        <div class="stats-bar">
                            <div class="progress-fill" data-progress="85"></div>
                        </div>
                        <small class="stats-note">85% dari target</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number counter" data-target="15">0</h3>
                        <p class="stats-label">Kategori Kompetisi</p>
                        <div class="stats-bar">
                            <div class="progress-fill" data-progress="100"></div>
                        </div>
                        <small class="stats-note">Lengkap tersedia</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="stats-card">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number">500</h3>
                        <p class="stats-label">Juta Rupiah Hadiah</p>
                        <div class="stats-bar">
                            <div class="progress-fill" data-progress="95"></div>
                        </div>
                        <small class="stats-note">Total untuk semua kategori</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="stats-card">
                    <div class="stats-icon bg-info">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-number counter" data-target="100">0</h3>
                        <p class="stats-label">Universitas Partner</p>
                        <div class="stats-bar">
                            <div class="progress-fill" data-progress="75"></div>
                        </div>
                        <small class="stats-note">Se-Indonesia</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Competition Categories -->
<section id="kompetisi" class="competitions-section">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <div class="section-badge">
                <i class="bi bi-trophy"></i>
                <span>Kategori Kompetisi</span>
            </div>
            <h2 class="section-title">Tiga Pilar Kompetisi Utama</h2>
            <p class="section-subtitle">
                Bergabunglah dengan kompetisi yang menggabungkan inovasi untuk masa depan berkelanjutan
            </p>
        </div>
        
        <div class="row g-4">
            <!-- Technology Competition -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <article class="competition-card tech-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="bi bi-cpu-fill"></i>
                        </div>
                        <div class="card-badge">Teknologi</div>
                    </div>
                    
                    <div class="card-image">
                        <img src="{{ asset('assets/images/competitions/technology-2025.webp') }}" 
                             alt="Kompetisi Teknologi UNAS Fest 2025" 
                             loading="lazy"
                             width="400"
                             height="250">
                        <div class="image-overlay">
                            <div class="overlay-content">
                                <i class="bi bi-laptop"></i>
                                <span>Inovasi Tech</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <h3 class="card-title">Kompetisi Teknologi</h3>
                        <p class="card-description">
                            Kompetisi pengembangan aplikasi, AI, IoT, dan solusi teknologi inovatif untuk 
                            menyelesaikan masalah nyata di masyarakat Indonesia.
                        </p>
                        
                        <div class="card-features">
                            <div class="feature-item">
                                <i class="bi bi-phone-fill"></i>
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
                        
                        <div class="card-stats">
                            <div class="stat-item">
                                <span class="stat-value">200 Juta</span>
                                <span class="stat-label">Hadiah Utama</span>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat-item">
                                <span class="stat-value">3000+</span>
                                <span class="stat-label">Peserta</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('public.competitions') }}#technology" class="card-button">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </article>
            </div>
            
            <!-- Health Competition -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <article class="competition-card health-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>
                        <div class="card-badge">Kesehatan</div>
                    </div>
                    
                    <div class="card-image">
                        <img src="{{ asset('assets/images/competitions/health-2025.webp') }}" 
                             alt="Kompetisi Kesehatan UNAS Fest 2025" 
                             loading="lazy"
                             width="400"
                             height="250">
                        <div class="image-overlay">
                            <div class="overlay-content">
                                <i class="bi bi-heart-pulse"></i>
                                <span>Health Innovation</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <h3 class="card-title">Kompetisi Kesehatan</h3>
                        <p class="card-description">
                            Inovasi dalam bidang kesehatan masyarakat, teknologi medis, dan solusi kesehatan 
                            digital untuk meningkatkan kualitas hidup.
                        </p>
                        
                        <div class="card-features">
                            <div class="feature-item">
                                <i class="bi bi-heart-fill"></i>
                                <span>Health Tech Innovation</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-bandaid-fill"></i>
                                <span>Medical Device Design</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-people-fill"></i>
                                <span>Public Health Solutions</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-tablet-fill"></i>
                                <span>Digital Health Platform</span>
                            </div>
                        </div>
                        
                        <div class="card-stats">
                            <div class="stat-item">
                                <span class="stat-value">150 Juta</span>
                                <span class="stat-label">Hadiah Utama</span>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat-item">
                                <span class="stat-value">2500+</span>
                                <span class="stat-label">Peserta</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('public.competitions') }}#health" class="card-button">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </article>
            </div>
            
            <!-- Biodiversity Competition -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <article class="competition-card bio-card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="bi bi-tree-fill"></i>
                        </div>
                        <div class="card-badge">Biodiversitas</div>
                    </div>
                    
                    <div class="card-image">
                        <img src="{{ asset('assets/images/competitions/biodiversity-2025.webp') }}" 
                             alt="Kompetisi Biodiversitas UNAS Fest 2025" 
                             loading="lazy"
                             width="400"
                             height="250">
                        <div class="image-overlay">
                            <div class="overlay-content">
                                <i class="bi bi-globe-americas"></i>
                                <span>Eco Innovation</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-content">
                        <h3 class="card-title">Kompetisi Biodiversitas</h3>
                        <p class="card-description">
                            Solusi inovatif untuk konservasi lingkungan, pelestarian biodiversitas, dan 
                            pembangunan berkelanjutan di Indonesia.
                        </p>
                        
                        <div class="card-features">
                            <div class="feature-item">
                                <i class="bi bi-tree-fill"></i>
                                <span>Environmental Conservation</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-recycle"></i>
                                <span>Sustainable Development</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-lightbulb-fill"></i>
                                <span>Eco-Innovation</span>
                            </div>
                            <div class="feature-item">
                                <i class="bi bi-leaf-fill"></i>
                                <span>Green Technology</span>
                            </div>
                        </div>
                        
                        <div class="card-stats">
                            <div class="stat-item">
                                <span class="stat-value">150 Juta</span>
                                <span class="stat-label">Hadiah Utama</span>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat-item">
                                <span class="stat-value">2000+</span>
                                <span class="stat-label">Peserta</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('public.competitions') }}#biodiversity" class="card-button">
                            <span>Lihat Detail</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
        color: var(--text-muted);
        font-weight: 500;
    }

    /* Competitions Section */
    .competitions-section {
        background: var(--white);
        position: relative;
    }

    .competition-card {
        background: var(--white);
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: all 0.4s ease;
        height: 100%;
        position: relative;
    }

    .competition-card:hover {
        transform: translateY(-12px);
        box-shadow: var(--shadow-xl);
    }

    .card-header {
        padding: 1.5rem;
        background: var(--gradient-primary);
        color: var(--white);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tech-card .card-header {
        background: var(--gradient-tech);
    }

    .health-card .card-header {
        background: var(--gradient-health);
    }

    .bio-card .card-header {
        background: var(--gradient-bio);
    }

    .card-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .card-badge {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .card-image {
        height: 250px;
        position: relative;
        overflow: hidden;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .competition-card:hover .card-image img {
        transform: scale(1.1);
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .competition-card:hover .image-overlay {
        opacity: 1;
    }

    .overlay-content {
        text-align: center;
        color: var(--white);
    }

    .overlay-content i {
        font-size: 3rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .overlay-content span {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .card-content {
        padding: 2rem;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .card-description {
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .card-features {
        margin-bottom: 1.5rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .feature-item i {
        color: var(--primary);
        width: 16px;
        flex-shrink: 0;
    }

    .card-stats {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1.25rem;
        background: var(--light);
        border-radius: 1rem;
    }

    .stat-item {
        flex: 1;
        text-align: center;
    }

    .stat-value {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .stat-divider {
        width: 1px;
        height: 2rem;
        background: var(--border);
    }

    .card-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        width: 100%;
        padding: 1rem;
        background: var(--gradient-primary);
        color: var(--white);
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .card-button:hover {
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
    }

    /* Timeline Section */
    .timeline-section {
        background: linear-gradient(135deg, var(--light) 0%, var(--white) 100%);
        position: relative;
    }

    .timeline-container {
        position: relative;
        max-width: 800px;
        margin: 0 auto;
    }

    .timeline-line {
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--gradient-primary);
        transform: translateX(-50%);
        z-index: 1;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 3rem;
        display: flex;
        align-items: center;
    }

    .timeline-item:nth-child(even) {
        flex-direction: row-reverse;
    }

    .timeline-marker {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 60px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 1.25rem;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .timeline-content {
        width: calc(50% - 40px);
        padding: 0 2rem;
    }

    .timeline-card {
        background: var(--white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        position: relative;
    }

    .timeline-card::before {
        content: '';
        position: absolute;
        top: 50%;
        width: 0;
        height: 0;
        border: 10px solid transparent;
    }

    .timeline-item:nth-child(odd) .timeline-card::before {
        right: -20px;
        border-left-color: var(--white);
        transform: translateY(-50%);
    }

    .timeline-item:nth-child(even) .timeline-card::before {
        left: -20px;
        border-right-color: var(--white);
        transform: translateY(-50%);
    }

    .timeline-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .timeline-date {
        color: var(--primary);
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .timeline-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .timeline-description {
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .timeline-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .timeline-status.ongoing {
        background: rgba(16, 185, 129, 0.1);
        color: var(--secondary);
    }

    .timeline-status.upcoming {
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent);
    }

    /* Benefits Section */
    .benefits-section {
        background: var(--white);
        position: relative;
    }

    .benefit-card {
        background: var(--white);
        border-radius: 1.25rem;
        padding: 2rem;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        height: 100%;
        text-align: center;
        border: 1px solid var(--border);
    }

    .benefit-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary);
    }

    .benefit-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        color: var(--white);
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
        transition: all 0.3s ease;
    }

    .benefit-card:hover .benefit-icon {
        transform: scale(1.1);
    }

    .benefit-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .benefit-description {
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* CTA Section */
    .cta-section {
        background: var(--gradient-primary);
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.15)"/></svg>');
        opacity: 0.5;
    }

    .cta-content {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2rem;
        padding: 3rem;
        color: var(--white);
        position: relative;
        z-index: 10;
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .cta-description {
        font-size: 1.125rem;
        line-height: 1.6;
        margin-bottom: 2rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .cta-features {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .cta-features .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--white);
        font-weight: 500;
    }

    .cta-features .feature-item i {
        color: var(--accent);
        font-size: 1.25rem;
    }

    .cta-actions {
        text-align: center;
    }

    .btn-xl {
        padding: 1.25rem 2.5rem;
        font-size: 1.125rem;
        border-radius: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        transition: all 0.3s ease;
        width: 100%;
        justify-content: center;
    }

    .btn-xl.btn-primary {
        background: var(--accent);
        color: var(--dark);
        border: none;
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    }

    .btn-xl.btn-primary:hover {
        background: #f59e0b;
        color: var(--dark);
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(245, 158, 11, 0.5);
    }

    .btn-xl.btn-outline {
        background: transparent;
        color: var(--white);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .btn-xl.btn-outline:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--white);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-3px);
    }

    .cta-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
        margin-top: 1rem;
    }

    .cta-note i {
        color: var(--accent);
    }

    /* FAQ Section */
    .faq-section {
        background: linear-gradient(135deg, var(--light) 0%, var(--white) 100%);
    }

    .faq-accordion .accordion-item {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 1rem;
        margin-bottom: 1rem;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .faq-accordion .accordion-button {
        background: var(--white);
        color: var(--text-primary);
        font-weight: 600;
        padding: 1.5rem;
        border: none;
        box-shadow: none;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .faq-accordion .accordion-button:not(.collapsed) {
        background: var(--primary);
        color: var(--white);
    }

    .faq-accordion .accordion-button:focus {
        box-shadow: none;
        border: none;
    }

    .faq-accordion .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23212529'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        transition: transform 0.3s ease;
    }

    .faq-accordion .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ffffff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        transform: rotate(180deg);
    }

    .faq-accordion .accordion-body {
        padding: 1.5rem;
        color: var(--text-secondary);
        line-height: 1.6;
        border-top: 1px solid var(--border);
    }

    .faq-more {
        margin-top: 3rem;
    }

    .faq-more p {
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }

    /* Animations */
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0, 0, 0);
        }
        40%, 43% {
            transform: translate3d(0, -10px, 0);
        }
        70% {
            transform: translate3d(0, -5px, 0);
        }
        90% {
            transform: translate3d(0, -2px, 0);
        }
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .floating-stats {
            display: none;
        }
    }

    @media (max-width: 992px) {
        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4rem);
        }

        .hero-actions {
            justify-content: center;
        }

        .hero-info {
            justify-content: center;
        }

        .timeline-line {
            left: 30px;
        }

        .timeline-marker {
            left: 30px;
        }

        .timeline-item {
            flex-direction: row !important;
        }

        .timeline-content {
            width: calc(100% - 80px);
            margin-left: 80px;
            padding: 0;
        }

        .timeline-card::before {
            left: -20px !important;
            right: auto !important;
            border-right-color: var(--white) !important;
            border-left-color: transparent !important;
        }

        .cta-title {
            font-size: 2rem;
        }

        .btn-xl {
            width: auto;
            margin: 0 0.5rem 1rem;
        }
    }

    @media (max-width: 768px) {
        section {
            padding: 3rem 0;
        }

        .hero-modern {
            min-height: 80vh;
            padding: 2rem 0;
        }

        .hero-title {
            font-size: clamp(2rem, 8vw, 3rem);
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .hero-actions {
            flex-direction: column;
            align-items: center;
        }

        .btn-cta {
            width: 100%;
            justify-content: center;
        }

        .hero-info {
            flex-direction: column;
            gap: 1rem;
        }

        .info-item {
            width: 100%;
        }

        .section-title {
            font-size: clamp(1.75rem, 6vw, 2.5rem);
        }

        .section-subtitle {
            font-size: 1rem;
        }

        .stats-number {
            font-size: 2rem;
        }

        .card-stats {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .stat-divider {
            width: 100%;
            height: 1px;
        }

        .cta-content {
            padding: 2rem;
        }

        .cta-title {
            font-size: 1.75rem;
        }

        .cta-features {
            flex-direction: column;
            gap: 1rem;
        }

        .timeline-line {
            left: 20px;
        }

        .timeline-marker {
            left: 20px;
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .timeline-content {
            margin-left: 60px;
        }
    }

    @media (max-width: 576px) {
        .hero-content {
            text-align: center;
        }

        .hero-badge {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }

        .section-header {
            margin-bottom: 2rem;
        }

        .stats-card,
        .benefit-card,
        .competition-card {
            margin-bottom: 2rem;
        }

        .card-content {
            padding: 1.5rem;
        }

        .cta-content {
            padding: 1.5rem;
        }

        .timeline-content {
            margin-left: 50px;
        }

        .timeline-card {
            padding: 1rem;
        }
    }

    /* Performance Optimizations */
    .hero-image,
    .card-image img {
        will-change: transform;
    }

    .stats-card,
    .benefit-card,
    .competition-card {
        will-change: transform;
    }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }

        .hero-particles,
        .floating-stats,
        .scroll-arrow {
            animation: none !important;
        }
    }

    /* Print Styles */
    @media print {
        .hero-background,
        .floating-stats,
        .scroll-indicator {
            display: none !important;
        }

        .hero-modern {
            background: var(--white) !important;
            color: var(--dark) !important;
        }

        .hero-content * {
            color: var(--dark) !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 50
    });

    // Counter Animation
    const counters = document.querySelectorAll('.counter');
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };

    const animateCounter = (counter) => {
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
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    counters.forEach(counter => {
        counterObserver.observe(counter);
    });

    // Progress Bar Animation
    const progressBars = document.querySelectorAll('.progress-fill');
    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBar = entry.target;
                const progress = progressBar.getAttribute('data-progress');
                setTimeout(() => {
                    progressBar.style.width = progress + '%';
                }, 200);
                progressObserver.unobserve(progressBar);
            }
        });
    }, observerOptions);

    progressBars.forEach(bar => {
        progressObserver.observe(bar);
    });

    // Smooth Scrolling for Anchor Links
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

    // Parallax Effect for Hero
    const hero = document.querySelector('.hero-modern');
    const heroContent = document.querySelector('.hero-content');
    
    if (hero && heroContent) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = scrolled * 0.3;
            
            if (scrolled < window.innerHeight) {
                heroContent.style.transform = `translateY(${parallax}px)`;
            }
        });
    }

    // Floating Animation for Stats Cards
    const floatingElements = document.querySelectorAll('.stat-card');
    floatingElements.forEach((element, index) => {
        element.style.animationDelay = `${index * 0.5}s`;
    });

    // Dynamic Background Particles
    function createParticle() {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
            animation: float 15s linear infinite;
        `;
        
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 15 + 's';
        
        return particle;
    }

    // Add particles to hero background
    const heroParticles = document.querySelector('.hero-particles');
    if (heroParticles) {
        for (let i = 0; i < 20; i++) {
            heroParticles.appendChild(createParticle());
        }
    }

    // Lazy loading for images
    const images = document.querySelectorAll('img[loading="lazy"]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.src; // Trigger load
                img.classList.add('loaded');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => {
        imageObserver.observe(img);
    });

    // Performance monitoring
    if ('performance' in window) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                const navigation = performance.getEntriesByType('navigation')[0];
                console.log('Page Load Time:', navigation.loadEventEnd - navigation.loadEventStart, 'ms');
            }, 0);
        });
    }
});

// Service Worker Registration for PWA
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('SW registered: ', registration);
            })
            .catch((registrationError) => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
</script>
@endpush
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endpush
