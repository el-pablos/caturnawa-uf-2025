@extends('layouts.public')

@php
    $seoPage = 'home';
@endphp

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6 hero-content" data-aos="fade-right">
                <h1 class="display-3 fw-bold text-white mb-4 font-poppins">
                    UNAS Fest 2025
                    <span class="d-block text-warning">Festival Kompetisi Nasional</span>
                </h1>
                <p class="lead text-white-50 mb-4">
                    Bergabunglah dengan festival kompetisi nasional terbesar di Indonesia yang menggabungkan 
                    <strong class="text-warning">Teknologi</strong>, 
                    <strong class="text-success">Kesehatan</strong>, dan 
                    <strong class="text-info">Biodiversitas</strong> 
                    untuk masa depan berkelanjutan.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('public.competitions') }}" class="btn btn-warning btn-lg px-4 py-3">
                        <i class="bi bi-trophy me-2"></i>Daftar Kompetisi
                    </a>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-light btn-lg px-4 py-3">
                        <i class="bi bi-telephone me-2"></i>Hubungi Kami
                    </a>
                </div>
                <div class="d-flex align-items-center text-white-50">
                    <i class="bi bi-calendar-event me-2"></i>
                    <span>Pendaftaran dibuka: <strong class="text-white">1 Januari - 28 Februari 2025</strong></span>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="text-center">
                    <img src="{{ asset('assets/images/hero/hero-illustration.svg') }}" 
                         alt="UNAS Fest 2025 Illustration" 
                         class="img-fluid" 
                         style="max-height: 500px;"
                         onerror="this.src='{{ asset('assets/images/hero/hero-placeholder.png') }}'">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4">
        <div class="text-white text-center">
            <small>Scroll untuk melihat lebih banyak</small>
            <div class="mt-2">
                <i class="bi bi-chevron-down fs-4 animate-bounce"></i>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-people fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-primary mb-2">{{ number_format($stats['participants'] ?? 10000) }}+</h3>
                    <p class="text-muted mb-0">Peserta Terdaftar</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-trophy fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-2">{{ $stats['competitions'] ?? 15 }}</h3>
                    <p class="text-muted mb-0">Kategori Kompetisi</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-gift fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-warning mb-2">{{ number_format($stats['total_prize'] / 1000000) }} Juta</h3>
                    <p class="text-muted mb-0">Total Hadiah</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="text-center">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-building fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-info mb-2">{{ $stats['universities'] ?? 100 }}+</h3>
                    <p class="text-muted mb-0">Universitas Partner</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Competition Categories -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins">Kategori Kompetisi</h2>
            <p class="section-subtitle">
                Tiga pilar utama kompetisi yang menggabungkan inovasi untuk masa depan berkelanjutan
            </p>
        </div>
        
        <div class="row g-4">
            <!-- Technology -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 250px;">
                        <img src="{{ asset('assets/images/competitions/technology-banner.jpg') }}" 
                             alt="Kompetisi Teknologi" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-75 d-flex align-items-center justify-content-center">
                            <i class="bi bi-cpu text-white" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-primary mb-3">
                            <i class="bi bi-laptop me-2"></i>Teknologi
                        </h4>
                        <p class="card-text text-muted mb-4">
                            Kompetisi pengembangan aplikasi, AI, IoT, dan solusi teknologi inovatif untuk menyelesaikan masalah nyata di masyarakat.
                        </p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Mobile App Development</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Web Development</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>AI & Machine Learning</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>IoT Solutions</li>
                        </ul>
                        <a href="{{ route('public.competitions') }}#technology" class="btn btn-primary w-100">
                            <i class="bi bi-arrow-right me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Health -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 250px;">
                        <img src="{{ asset('assets/images/competitions/health-banner.jpg') }}" 
                             alt="Kompetisi Kesehatan" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-success bg-opacity-75 d-flex align-items-center justify-content-center">
                            <i class="bi bi-heart-pulse text-white" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-success mb-3">
                            <i class="bi bi-heart me-2"></i>Kesehatan
                        </h4>
                        <p class="card-text text-muted mb-4">
                            Inovasi dalam bidang kesehatan masyarakat, teknologi medis, dan solusi kesehatan digital untuk meningkatkan kualitas hidup.
                        </p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Health Tech Innovation</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Medical Device Design</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Public Health Solutions</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Digital Health Platform</li>
                        </ul>
                        <a href="{{ route('public.competitions') }}#health" class="btn btn-success w-100">
                            <i class="bi bi-arrow-right me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Biodiversity -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 250px;">
                        <img src="{{ asset('assets/images/competitions/biodiversity-banner.jpg') }}" 
                             alt="Kompetisi Biodiversitas" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-info bg-opacity-75 d-flex align-items-center justify-content-center">
                            <i class="bi bi-tree text-white" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-info mb-3">
                            <i class="bi bi-globe me-2"></i>Biodiversitas
                        </h4>
                        <p class="card-text text-muted mb-4">
                            Solusi inovatif untuk konservasi lingkungan, pelestarian biodiversitas, dan pembangunan berkelanjutan.
                        </p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Environmental Conservation</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Sustainable Development</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Eco-Innovation</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Green Technology</li>
                        </ul>
                        <a href="{{ route('public.competitions') }}#biodiversity" class="btn btn-info w-100">
                            <i class="bi bi-arrow-right me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<section class="section bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins">Timeline UNAS Fest 2025</h2>
            <p class="section-subtitle">
                Jadwal lengkap kegiatan dari pendaftaran hingga pengumuman pemenang
            </p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="timeline">
                    <div class="timeline-item" data-aos="fade-right" data-aos-delay="100">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-primary mb-2">
                                        <i class="bi bi-calendar-plus me-2"></i>Pendaftaran Dibuka
                                    </h5>
                                    <p class="text-muted mb-2">1 Januari - 28 Februari 2025</p>
                                    <p class="card-text">Pendaftaran peserta untuk semua kategori kompetisi dibuka secara online.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-left" data-aos-delay="200">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-warning mb-2">
                                        <i class="bi bi-upload me-2"></i>Pengumpulan Karya
                                    </h5>
                                    <p class="text-muted mb-2">1 - 15 Maret 2025</p>
                                    <p class="card-text">Periode pengumpulan karya dan proposal untuk semua kategori kompetisi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-right" data-aos-delay="300">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-info mb-2">
                                        <i class="bi bi-search me-2"></i>Penilaian Juri
                                    </h5>
                                    <p class="text-muted mb-2">16 - 25 Maret 2025</p>
                                    <p class="card-text">Proses penilaian karya oleh tim juri ahli dari berbagai bidang.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item" data-aos="fade-left" data-aos-delay="400">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-success mb-2">
                                        <i class="bi bi-trophy me-2"></i>Pengumuman Pemenang
                                    </h5>
                                    <p class="text-muted mb-2">30 Maret 2025</p>
                                    <p class="card-text">Pengumuman pemenang dan acara penutupan UNAS Fest 2025.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Contact Section -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="font-poppins fw-bold mb-4">Siap Bergabung?</h2>
                <p class="lead text-muted mb-4">
                    Jangan lewatkan kesempatan emas untuk menunjukkan inovasi terbaikmu di UNAS Fest 2025. 
                    Daftar sekarang dan raih prestasi gemilang!
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('public.competitions') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-trophy me-2"></i>Daftar Sekarang
                    </a>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-chat-dots me-2"></i>Tanya Panitia
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-envelope me-2"></i>Kontak Cepat
                        </h5>
                        <form action="{{ route('public.contact.send') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" name="message" rows="3" placeholder="Pesan Anda" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .animate-bounce {
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0,-30px,0);
        }
        70% {
            transform: translate3d(0,-15px,0);
        }
        90% {
            transform: translate3d(0,-4px,0);
        }
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 20px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #e5e7eb;
    }
    
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endpush
