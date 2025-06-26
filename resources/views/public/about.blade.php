@extends('layouts.public')

@php
    $seoPage = 'about';
@endphp

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6 hero-content" data-aos="fade-right">
                <h1 class="hero-title display-3 fw-bold text-white mb-4">
                    Tentang 
                    <span class="text-gradient">UNAS Fest 2025</span>
                </h1>
                <p class="lead text-white-50 mb-4">
                    Festival kompetisi nasional terbesar di Indonesia yang menggabungkan inovasi teknologi, 
                    kesehatan, dan biodiversitas untuk menciptakan masa depan yang berkelanjutan.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('public.competitions') }}" class="btn btn-warning btn-lg px-4 py-3">
                        <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                    </a>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-light btn-lg px-4 py-3">
                        <i class="bi bi-envelope me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="text-center">
                    <div class="position-relative">
                        <img src="{{ asset('assets/images/about/about-hero.svg') }}" 
                             alt="About UNAS Fest 2025" 
                             class="img-fluid" 
                             style="max-height: 500px;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="d-none align-items-center justify-content-center" style="height: 500px;">
                            <div class="text-center text-white">
                                <i class="bi bi-people-fill" style="font-size: 8rem; opacity: 0.3;"></i>
                                <h3 class="mt-3 opacity-50">Tim UNAS Fest</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission Section -->
<section class="section">
    <div class="container py-5 position-relative">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-eye fs-2"></i>
                            </div>
                            <h3 class="fw-bold text-primary">Visi Kami</h3>
                        </div>
                        <p class="text-muted text-center">
                            Menjadi festival kompetisi nasional terdepan yang menginspirasi inovasi berkelanjutan 
                            dalam bidang teknologi, kesehatan, dan biodiversitas untuk masa depan Indonesia yang lebih baik.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-bullseye fs-2"></i>
                            </div>
                            <h3 class="fw-bold text-success">Misi Kami</h3>
                        </div>
                        <ul class="list-unstyled text-muted">
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                                <span>Memberikan platform kompetisi berkualitas tinggi untuk mahasiswa Indonesia</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                                <span>Mendorong inovasi dan kreativitas dalam menyelesaikan masalah nyata</span>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                                <span>Membangun jaringan kolaborasi antar universitas di seluruh Indonesia</span>
                            </li>
                            <li class="mb-0 d-flex align-items-start">
                                <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                                <span>Mengembangkan solusi berkelanjutan untuk tantangan masa depan</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="section bg-light">
    <div class="container py-5 position-relative">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins">UNAS Fest dalam Angka</h2>
            <p class="section-subtitle">
                Pencapaian dan dampak yang telah kami ciptakan bersama komunitas mahasiswa Indonesia
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-people fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-primary mb-2 counter" data-target="10000">0</h3>
                        <p class="text-muted mb-0">Peserta Terdaftar</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-trophy fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-2 counter" data-target="15">0</h3>
                        <p class="text-muted mb-0">Kategori Kompetisi</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-gift fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-2">500 <small>Juta</small></h3>
                        <p class="text-muted mb-0">Total Hadiah</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-building fs-3"></i>
                        </div>
                        <h3 class="fw-bold text-info mb-2 counter" data-target="100">0</h3>
                        <p class="text-muted mb-0">Universitas Partner</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section">
    <div class="container py-5 position-relative">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins">Nilai-Nilai Kami</h2>
            <p class="section-subtitle">
                Prinsip-prinsip yang menjadi fondasi dalam menyelenggarakan UNAS Fest 2025
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-lightbulb fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-primary mb-3">Inovasi</h4>
                        <p class="text-muted">
                            Mendorong kreativitas dan pemikiran out-of-the-box untuk menciptakan solusi inovatif yang berdampak.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="bg-gradient-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-people fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-success mb-3">Kolaborasi</h4>
                        <p class="text-muted">
                            Membangun kerjasama yang solid antar peserta, panitia, dan stakeholder untuk mencapai tujuan bersama.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="bg-gradient-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-award fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-warning mb-3">Kualitas</h4>
                        <p class="text-muted">
                            Menjaga standar tinggi dalam setiap aspek penyelenggaraan kompetisi dan pelayanan peserta.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 shadow-sm h-100 text-center hover-lift">
                    <div class="card-body p-4">
                        <div class="bg-gradient-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-globe fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-info mb-3">Berkelanjutan</h4>
                        <p class="text-muted">
                            Fokus pada solusi yang memberikan dampak jangka panjang bagi masyarakat dan lingkungan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
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
    
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    
    .counter {
        font-size: 2.5rem;
    }
    
    @media (max-width: 768px) {
        .counter {
            font-size: 2rem;
        }
        
        .display-3 {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Counter animation
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter');
        
        const animateCounter = (counter) => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000; // 2 seconds
            const step = target / (duration / 16); // 60fps
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    counter.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        };
        
        // Intersection Observer for counter animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    if (counter.getAttribute('data-target')) {
                        animateCounter(counter);
                        observer.unobserve(counter);
                    }
                }
            });
        });
        
        counters.forEach(counter => {
            observer.observe(counter);
        });
    });
</script>
@endpush
