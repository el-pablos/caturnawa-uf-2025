@extends('layouts.public')

@php
    $seoPage = 'competitions';
@endphp

@section('content')
<!-- Hero Section -->
<section class="competitions-hero">
    <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>

    <div class="container position-relative">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                <div class="hero-badge">
                    <i class="bi bi-trophy me-2" ></i>
                    Kompetisi Nasional 2025
                </div>

                <h1 class="hero-title">
                    Kompetisi
                    <span class="text-gradient">UNAS Fest 2025</span>
                </h1>

                <p class="hero-subtitle">
                    Tunjukkan inovasi terbaikmu dalam tiga kategori kompetisi utama yang akan membentuk masa depan Indonesia
                </p>

                <div class="competition-categories">
                    <div class="category-pill technology" data-aos="fade-up" data-aos-delay="100">
                        <i class="bi bi-cpu"></i>
                        <span>Teknologi</span>
                    </div>
                    <div class="category-pill health" data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-heart-pulse"></i>
                        <span>Kesehatan</span>
                    </div>
                    <div class="category-pill biodiversity" data-aos="fade-up" data-aos-delay="300">
                        <i class="bi bi-tree"></i>
                        <span>Biodiversitas</span>
                    </div>
                </div>

                <div class="hero-actions" data-aos="fade-up" data-aos-delay="400">
                    <a href="#competitions-list" class="btn-primary-custom">
                        <span>Jelajahi Kompetisi</span>
                        <i class="bi bi-arrow-down"></i>
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary-custom">
                        <i class="bi bi-person-plus me-2"></i>
                        Daftar Sekarang
                    </a>
                </div>

                <div class="hero-stats" data-aos="fade-up" data-aos-delay="500">
                    <div class="stat-item">
                        <span class="stat-number">500M</span>
                        <span class="stat-label">Total Hadiah</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number">10K+</span>
                        <span class="stat-label">Peserta</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number">100+</span>
                        <span class="stat-label">Universitas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Competition Overview -->
<section id="competitions-list" class="competitions-overview">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <div class="section-badge">
                <i class="bi bi-award me-2"></i>
                Kompetisi Utama
            </div>
            <h2 class="section-title">Tiga Pilar Inovasi</h2>
            <p class="section-subtitle">
                Setiap kategori kompetisi dirancang untuk mendorong inovasi dalam bidang yang akan membentuk masa depan Indonesia
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats" data-aos="fade-up" data-aos-delay="200">
            <div class="stats-grid d-flex justify-content-center gap-4">
                <div class="quick-stat-item">
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number counter" data-target="10000">0</span>
                        <span class="stat-label">Peserta Terdaftar</span>
                    </div>
                </div>

                <div class="quick-stat-item">
                    <div class="stat-icon bg-gradient-success">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">15</span>
                        <span class="stat-label">Kategori Kompetisi</span>
                    </div>
                </div>

                <div class="quick-stat-item">
                    <div class="stat-icon bg-gradient-warning">
                        <i class="bi bi-gift-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number">500M</span>
                        <span class="stat-label">Total Hadiah</span>
                    </div>
                </div>

                <div class="quick-stat-item">
                    <div class="stat-icon bg-gradient-info">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-number counter" data-target="100">0</span>
                        <span class="stat-label">Universitas Partner</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technology Competition -->
<section id="technology" class="section">
    <div class="container position-relative py-5 overflow-hidden">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins text-white">
                <i class="bi bi-cpu me-3 text-warning"></i>Kompetisi Teknologi
            </h2>
            <p class="section-subtitle">
                Wujudkan inovasi teknologi untuk menyelesaikan masalah nyata di masyarakat
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 250px;">
                        <img src="{{ asset('assets/images/competitions/technology-banner.jpg') }}" 
                             alt="Kompetisi Teknologi" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-warning bg-opacity-75 d-flex align-items-center justify-content-center">
                            <i class="bi bi-cpu text-white" style="font-size: 7rem;"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-dark mb-3">Kategori Teknologi</h4>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-phone text-warning me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Mobile App Development</h6>
                                        <small class="text-muted">Aplikasi mobile inovatif untuk Android/iOS</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-globe text-warning me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Web Development</h6>
                                        <small class="text-muted">Platform web dengan teknologi terdepan</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-robot text-warning me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">AI & Machine Learning</h6>
                                        <small class="text-muted">Solusi berbasis kecerdasan buatan</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-wifi text-warning me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">IoT Solutions</h6>
                                        <small class="text-muted">Internet of Things untuk smart city</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-primary mb-4">
                            <i class="bi bi-info-circle me-2"></i>Informasi Kompetisi
                        </h4>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="bi bi-calendar-event text-primary me-2"></i>Timeline
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2"><strong>Pendaftaran:</strong> 1 Jan - 28 Feb 2025</li>
                                <li class="mb-2"><strong>Pengumpulan:</strong> 1 - 15 Mar 2025</li>
                                <li class="mb-2"><strong>Penilaian:</strong> 16 - 25 Mar 2025</li>
                                <li class="mb-2"><strong>Pengumuman:</strong> 30 Mar 2025</li>
                            </ul>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="bi bi-trophy text-warning me-2"></i>Hadiah
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2"><strong>Juara 1:</strong> Rp 50.000.000 + Sertifikat</li>
                                <li class="mb-2"><strong>Juara 2:</strong> Rp 30.000.000 + Sertifikat</li>
                                <li class="mb-2"><strong>Juara 3:</strong> Rp 20.000.000 + Sertifikat</li>
                                <li class="mb-2"><strong>Harapan:</strong> Rp 5.000.000 + Sertifikat</li>
                            </ul>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="bi bi-people text-success me-2"></i>Persyaratan
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2">• Mahasiswa aktif S1/D3/D4</li>
                                <li class="mb-2">• Tim maksimal 3 orang</li>
                                <li class="mb-2">• Karya original dan belum dipublikasi</li>
                                <li class="mb-2">• Menggunakan teknologi terbaru</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-warning btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                            <a href="{{ route('public.faq') }}" class="btn btn-outline-dark">
                                <i class="bi bi-question-circle me-2"></i>FAQ & Panduan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Health Competition -->
<section id="health" class="section">
    <div class="container position-relative py-5 overflow-hidden">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins text-white">
                <i class="bi bi-heart-pulse me-3 text-success"></i>Kompetisi Kesehatan
            </h2>
            <p class="section-subtitle">
                Ciptakan solusi inovatif untuk meningkatkan kualitas hidup dan kesehatan masyarakat Indonesia
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 300px;">
                        <img src="{{ asset('assets/images/competitions/health-banner.jpg') }}" 
                             alt="Kompetisi Kesehatan" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-success bg-opacity-75 d-flex align-items-center justify-content-center">
                            <i class="bi bi-heart-pulse text-white" style="font-size: 7rem;"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-suscess mb-3">Kategori Kesehatan</h4>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-lightbulb text-success me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Health Tech Innovations</h6>
                                        <small class="text-muted">Inovasi teknologi kesehatan untuk meningkatkan kualitas hidup dan kesehatan masyarakat.</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-pencil text-success me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Medical Device Desain</h6>
                                        <small class="text-muted">Desain alat kesehatan untuk meningkatkan kesehatan dan kualitas hidup masyarakat.</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-globe-asia-australia text-success me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Public Health Solutions</h6>
                                        <small class="text-muted">Solusi kesehatan publik untuk meningkatkan kualitas hidup dan kesehatan masyarakat, termasuk peningkatan kesehatan masyarakat.</small><small class="text-muted"><small> </small></small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-phone text-success me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Digital Health Platform</h6>
                                        <small class="text-muted">Platform edukasi kesehatan, telemedicine, atau sistem informasi kesehatan masyarakat.</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-primary mb-4">
                            <i class="bi bi-info-circle me-2"></i>Informasi Kompetisi
                        </h4>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="bi bi-calendar-event text-primary me-2"></i>Timeline
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2"><strong>Pendaftaran:</strong> 1 Jan - 28 Feb 2025</li>
                                <li class="mb-2"><strong>Pengumpulan:</strong> 1 - 15 Mar 2025</li>
                                <li class="mb-2"><strong>Penilaian:</strong> 16 - 25 Mar 2025</li>
                                <li class="mb-2"><strong>Pengumuman:</strong> 30 Mar 2025</li>
                            </ul>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="bi bi-trophy text-warning me-2"></i>Hadiah
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2"><strong>Juara 1:</strong> Rp 50.000.000 + Sertifikat</li>
                                <li class="mb-2"><strong>Juara 2:</strong> Rp 30.000.000 + Sertifikat</li>
                                <li class="mb-2"><strong>Juara 3:</strong> Rp 20.000.000 + Sertifikat</li>
                                <li class="mb-2"><strong>Harapan:</strong> Rp 5.000.000 + Sertifikat</li>
                            </ul>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="bi bi-people text-success me-2"></i>Persyaratan
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2">• Mahasiswa aktif S1/D3/D4</li>
                                <li class="mb-2">• Tim maksimal 3 orang</li>
                                <li class="mb-2">• Karya original dan belum dipublikasi</li>
                                <li class="mb-2">• Solusi harus relevan dengan isu kesehatan masyarakat</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                            <a href="{{ route('public.faq') }}" class="btn btn-outline-dark">
                                <i class="bi bi-question-circle me-2"></i>FAQ & Panduan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Biodiversity Competition -->
<section id="health" class="section">
    <div class="container position-relative py-5 overflow-hidden">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins text-white">
                <i class="bi bi-tree-fill me-3 text-primary"></i>Kompetisi Biodiversitas
            </h2>
            <p class="section-subtitle">
                Ciptakan solusi inovatif untuk meningkatkan kualitas hidup dan kesehatan masyarakat Indonesia
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 300px;">
                        <img src="{{ asset('assets/images/competitions/biodiversity-banner.jpg') }}" 
                             alt="Kompetisi Biodiversitas" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-75 d-flex align-items-center justify-content-center">
                            <i class="bi bi-tree-fill text-white" style="font-size: 7rem;"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-dark mb-3">Kategori Biodiversitas</h4>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-tree text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Environmental Conservation</h6></h6>
                                        <small class="text-muted">Solusi untuk menjaga kelestarian lingkungan dan kualitas hidup masyarakat.</small><small class="text-muted"><small></small>.</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-recycle text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Sustainable Development</h6>
                                        <small class="text-muted">Solusi untuk menjaga kelestarian lingkungan dan kualitas hidup masyarakat.</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-trash text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Eco Innovations</h6>
                                        <small class="text-muted">Solusi untuk menjaga kelestarian lingkungan dan kualitas hidup masyarakat.</small><small class="text-muted"><small> </small></small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-cpu text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Green Technology</h6>
                                        <small class="text-muted">Solusi untuk menjaga kelestarian lingkungan dan kualitas hidup masyarakat.</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-primary mb-4">
                            <i class="bi bi-info-circle me-2"></i>Informasi Kompetisi
                        </h4>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="bi bi-calendar-event text-primary me-2"></i>Timeline
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2"><strong>Pendaftaran:</strong> 1 Jan - 28 Feb 2025</li>
                                <li class="mb-2"><strong>Pengumpulan:</strong> 1 - 15 Mar 2025</li>
                                <li class="mb-2"><strong>Penilaian:</strong> 16 - 25 Mar 2025</li>
                                <li class="mb-2"><strong>Pengumuman:</strong> 30 Mar 2025</li>
                            </ul>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="bi bi-trophy text-warning me-2"></i>Hadiah
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2"><strong>Juara 1:</strong> Rp 50.000.000 + Sertifikat</li>
                                <li class="mb-2"><strong>Juara 2:</strong> Rp 30.000.000 + Sertifikat</li>
                                <li class="mb-2"><strong>Juara 3:</strong> Rp 20.000.000 + Sertifikat</li>
                                <li class="mb-2"><strong>Harapan:</strong> Rp 5.000.000 + Sertifikat</li>
                            </ul>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="bi bi-people text-success me-2"></i>Persyaratan
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2">• Mahasiswa aktif S1/D3/D4</li>
                                <li class="mb-2">• Tim maksimal 3 orang</li>
                                <li class="mb-2">• Karya original dan belum dipublikasi</li>
                                <li class="mb-2">• Solusi harus relevan dengan isu kesehatan masyarakat</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                            <a href="{{ route('public.faq') }}" class="btn btn-outline-dark">
                                <i class="bi bi-question-circle me-2"></i>FAQ & Panduan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section bg-primary text-white">
    <div class="container text-center py-5 position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <h2 class="fw-bold mb-4">Siap Menunjukkan Inovasimu?</h2>
                <p class="lead mb-4">
                    Bergabunglah dengan ribuan peserta lainnya dan wujudkan ide terbaikmu di UNAS Fest 2025!
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-warning btn-lg px-5">
                        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                    </a>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-light btn-lg px-5">
                        <i class="bi bi-envelope me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Competitions Hero Section */
    .competitions-hero {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        z-index: 2;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        pointer-events: none;
    }

    .hero-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
            radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
            radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px);
        background-size: 50px 50px;
        animation: patternMove 20s linear infinite;
        z-index: 1;
        pointer-events: none;
    }

    @keyframes patternMove {
        0% { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }

    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
        z-index: 1;
    }

    .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 6s ease-in-out infinite;
    }

    .shape-1 {
        width: 120px;
        height: 120px;
        top: 20%;
        right: 10%;
        animation-delay: 0s;
    }

    .shape-2 {
        width: 80px;
        height: 80px;
        top: 60%;
        right: 20%;
        animation-delay: 2s;
    }

    .shape-3 {
        width: 100px;
        height: 100px;
        top: 40%;
        left: 10%;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(180deg); }
    }

    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        color: white;
        margin-bottom: 20px;
        line-height: 1.1;
    }

    .text-gradient {
        background: linear-gradient(135deg, var(--accent-color), #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 40px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .competition-categories {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    .category-pill {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 50px;
        padding: 15px 25px;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .category-pill:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-5px);
    }

    .category-pill.technology:hover {
        background: rgba(37, 99, 235, 0.3);
        border-color: rgba(37, 99, 235, 0.5);
    }

    .category-pill.health:hover {
        background: rgba(16, 185, 129, 0.3);
        border-color: rgba(16, 185, 129, 0.5);
    }

    .category-pill.biodiversity:hover {
        background: rgba(6, 182, 212, 0.3);
        border-color: rgba(6, 182, 212, 0.5);
    }

    .hero-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 50px;
        flex-wrap: wrap;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--accent-color), #f59e0b);
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
    }

    .btn-primary-custom:hover {
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary-custom {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 13px 28px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary-custom:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        transform: translateY(-3px);
    }

    .hero-stats {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .hero-stats .stat-item {
        text-align: center;
        color: white;
    }

    .hero-stats .stat-number {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .hero-stats .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .stat-divider {
        width: 1px;
        height: 40px;
        background: rgba(255, 255, 255, 0.3);
    }

    /* Competitions Overview Section */
    .competitions-overview {
        padding: 100px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
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

    .section-title {
        font-size: 3rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    .section-subtitle {
        font-size: 1.1rem;
        color: var(--text-muted);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .quick-stats {
        margin-top: 60px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .quick-stat-item {
        background: white;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .quick-stat-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    }

    .quick-stat-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 1.5rem;
        color: white;
    }

    .stat-content .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        display: block;
        margin-bottom: 10px;
    }

    .stat-content .stat-label {
        color: var(--text-muted);
        font-size: 0.9rem;
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
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .competition-categories {
            flex-direction: column;
            align-items: center;
        }

        .category-pill {
            width: 200px;
            justify-content: center;
        }

        .hero-actions {
            flex-direction: column;
            align-items: center;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 250px;
            justify-content: center;
        }

        .hero-stats {
            flex-direction: column;
            gap: 20px;
        }

        .stat-divider {
            width: 40px;
            height: 1px;
        }

        .section-title {
            font-size: 2rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .competitions-overview {
            padding: 60px 0;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 2rem;
        }

        .category-pill {
            width: 100%;
            max-width: 250px;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 100%;
            max-width: 280px;
        }

        .quick-stat-item {
            padding: 20px;
        }

        .stat-content .stat-number {
            font-size: 2rem;
        }
    }
</style>
@endpush
