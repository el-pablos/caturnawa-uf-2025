@extends('layouts.public')

@php
    $seoPage = 'competitions';
@endphp

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-8 mx-auto text-center hero-content" data-aos="fade-up">
                <h1 class="display-3 fw-bold text-white mb-4 font-poppins">
                    Kompetisi UNAS Fest 2025
                </h1>
                <p class="lead text-white-50 mb-4">
                    Tunjukkan inovasi terbaikmu dalam tiga kategori kompetisi utama: 
                    <strong class="text-warning">Teknologi</strong>, 
                    <strong class="text-success">Kesehatan</strong>, dan 
                    <strong class="text-info">Biodiversitas</strong>
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="#technology" class="btn btn-warning btn-lg px-4 py-3">
                        <i class="bi bi-laptop me-2"></i>Teknologi
                    </a>
                    <a href="#health" class="btn btn-success btn-lg px-4 py-3">
                        <i class="bi bi-heart me-2"></i>Kesehatan
                    </a>
                    <a href="#biodiversity" class="btn btn-info btn-lg px-4 py-3">
                        <i class="bi bi-tree me-2"></i>Biodiversitas
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-people fs-2"></i>
                </div>
                <h3 class="fw-bold text-primary mb-2">10,000+</h3>
                <p class="text-muted mb-0">Peserta Terdaftar</p>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-trophy fs-2"></i>
                </div>
                <h3 class="fw-bold text-success mb-2">15</h3>
                <p class="text-muted mb-0">Kategori Kompetisi</p>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-gift fs-2"></i>
                </div>
                <h3 class="fw-bold text-warning mb-2">500 Juta</h3>
                <p class="text-muted mb-0">Total Hadiah</p>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-building fs-2"></i>
                </div>
                <h3 class="fw-bold text-info mb-2">100+</h3>
                <p class="text-muted mb-0">Universitas Partner</p>
            </div>
        </div>
    </div>
</section>

<!-- Technology Competition -->
<section id="technology" class="section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins text-primary">
                <i class="bi bi-laptop me-3"></i>Kompetisi Teknologi
            </h2>
            <p class="section-subtitle">
                Wujudkan inovasi teknologi untuk menyelesaikan masalah nyata di masyarakat
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 300px;">
                        <img src="{{ asset('assets/images/competitions/technology-banner.jpg') }}" 
                             alt="Kompetisi Teknologi" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-75 d-flex align-items-center justify-content-center">
                            <i class="bi bi-cpu text-white" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-primary mb-3">Kategori Teknologi</h4>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-phone text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Mobile App Development</h6>
                                        <small class="text-muted">Aplikasi mobile inovatif untuk Android/iOS</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-globe text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Web Development</h6>
                                        <small class="text-muted">Platform web dengan teknologi terdepan</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-robot text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">AI & Machine Learning</h6>
                                        <small class="text-muted">Solusi berbasis kecerdasan buatan</small>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-wifi text-primary me-3 mt-1"></i>
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
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                            <a href="{{ route('public.faq') }}" class="btn btn-outline-primary">
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
    <div class="container text-center">
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
    .object-fit-cover {
        object-fit: cover;
    }
    
    .card:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
</style>
@endpush
