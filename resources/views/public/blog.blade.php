@extends('layouts.modern')

@php
    $seoPage = 'blog';
@endphp

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold text-white mb-4 font-poppins">
                    Blog & Artikel
                </h1>
                <p class="lead text-white-50 mb-4">
                    Temukan tips, panduan, dan informasi terbaru seputar UNAS Fest 2025. 
                    Tingkatkan peluang sukses Anda dengan membaca artikel-artikel berkualitas dari tim ahli kami.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Article -->
@if(count($posts) > 0)
<section class="section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins">Artikel Terbaru</h2>
            <p class="section-subtitle">
                Baca artikel terbaru dan dapatkan insight berharga untuk kesuksesan kompetisi Anda
            </p>
        </div>
        
        <!-- Featured Post -->
        <div class="row mb-5" data-aos="fade-up">
            <div class="col-12">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <img src="{{ $posts[0]['featured_image'] }}" 
                                 alt="{{ $posts[0]['title'] }}" 
                                 class="w-100 h-100 object-fit-cover"
                                 style="min-height: 300px;"
                                 onerror="this.src='{{ asset('assets/images/blog/default-featured.jpg') }}'">
                        </div>
                        <div class="col-lg-6">
                            <div class="card-body p-5 h-100 d-flex flex-column">
                                <div class="mb-3">
                                    <span class="badge bg-primary rounded-pill">{{ $posts[0]['category'] }}</span>
                                    <small class="text-muted ms-2">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $posts[0]['published_at']->format('d M Y') }}
                                    </small>
                                </div>
                                
                                <h3 class="card-title fw-bold mb-3">{{ $posts[0]['title'] }}</h3>
                                <p class="card-text text-muted mb-4 flex-grow-1">{{ $posts[0]['excerpt'] }}</p>
                                
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person fs-6"></i>
                                        </div>
                                        <small class="text-muted">{{ $posts[0]['author'] }}</small>
                                    </div>
                                    <a href="#" class="btn btn-primary">
                                        Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Blog Posts Grid -->
<section class="section bg-light">
    <div class="container">
        <div class="row g-4">
            @foreach($posts as $index => $post)
                @if($index > 0) <!-- Skip first post as it's featured -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <article class="card h-100 border-0 shadow-sm">
                        <div class="position-relative overflow-hidden">
                            <img src="{{ $post['featured_image'] }}" 
                                 alt="{{ $post['title'] }}" 
                                 class="card-img-top"
                                 style="height: 200px; object-fit: cover;"
                                 onerror="this.src='{{ asset('assets/images/blog/default-featured.jpg') }}'">
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-primary rounded-pill">{{ $post['category'] }}</span>
                            </div>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $post['published_at']->format('d M Y') }}
                                </small>
                            </div>
                            
                            <h5 class="card-title fw-bold mb-3">{{ $post['title'] }}</h5>
                            <p class="card-text text-muted mb-4 flex-grow-1">{{ $post['excerpt'] }}</p>
                            
                            <div class="d-flex align-items-center justify-content-between mt-auto">
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                                        <i class="bi bi-person fs-7"></i>
                                    </div>
                                    <small class="text-muted">{{ $post['author'] }}</small>
                                </div>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    Baca <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
                @endif
            @endforeach
            
            <!-- Add more sample posts if needed -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="position-relative overflow-hidden">
                        <div class="bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-lightbulb text-white" style="font-size: 3rem;"></i>
                        </div>
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-warning rounded-pill">Tips</span>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ now()->subDays(3)->format('d M Y') }}
                            </small>
                        </div>
                        
                        <h5 class="card-title fw-bold mb-3">Strategi Jitu Memenangkan Kompetisi</h5>
                        <p class="card-text text-muted mb-4 flex-grow-1">
                            Pelajari strategi dan tips dari para pemenang kompetisi sebelumnya untuk meningkatkan peluang sukses Anda.
                        </p>
                        
                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                                    <i class="bi bi-person fs-7"></i>
                                </div>
                                <small class="text-muted">Tim UNAS Fest</small>
                            </div>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                Baca <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="position-relative overflow-hidden">
                        <div class="bg-gradient-success d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-award text-white" style="font-size: 3rem;"></i>
                        </div>
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-success rounded-pill">Panduan</span>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ now()->subDays(7)->format('d M Y') }}
                            </small>
                        </div>
                        
                        <h5 class="card-title fw-bold mb-3">Persiapan Presentasi yang Memukau</h5>
                        <p class="card-text text-muted mb-4 flex-grow-1">
                            Panduan lengkap untuk mempersiapkan presentasi yang menarik dan persuasif di hadapan juri kompetisi.
                        </p>
                        
                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                                    <i class="bi bi-person fs-7"></i>
                                </div>
                                <small class="text-muted">Tim UNAS Fest</small>
                            </div>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                Baca <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <article class="card h-100 border-0 shadow-sm">
                    <div class="position-relative overflow-hidden">
                        <div class="bg-gradient-info d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-graph-up text-white" style="font-size: 3rem;"></i>
                        </div>
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-info rounded-pill">Analisis</span>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ now()->subDays(14)->format('d M Y') }}
                            </small>
                        </div>
                        
                        <h5 class="card-title fw-bold mb-3">Tren Teknologi 2025 dalam Kompetisi</h5>
                        <p class="card-text text-muted mb-4 flex-grow-1">
                            Analisis mendalam tentang tren teknologi terbaru yang menjadi fokus dalam kompetisi teknologi UNAS Fest 2025.
                        </p>
                        
                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                                    <i class="bi bi-person fs-7"></i>
                                </div>
                                <small class="text-muted">Tim UNAS Fest</small>
                            </div>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                Baca <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
        
        <!-- Load More Button -->
        <div class="text-center mt-5" data-aos="fade-up">
            <button class="btn btn-outline-primary btn-lg">
                <i class="bi bi-arrow-down-circle me-2"></i>Muat Lebih Banyak
            </button>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="card border-0 shadow-lg bg-primary text-white" data-aos="fade-up">
                    <div class="card-body p-5">
                        <h3 class="fw-bold mb-3">
                            <i class="bi bi-envelope-heart me-2"></i>
                            Berlangganan Newsletter
                        </h3>
                        <p class="mb-4">
                            Dapatkan artikel terbaru, tips kompetisi, dan informasi penting langsung di email Anda!
                        </p>
                        
                        <form class="row g-3 justify-content-center">
                            <div class="col-md-8">
                                <input type="email" class="form-control form-control-lg" 
                                       placeholder="Masukkan email Anda" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-warning btn-lg w-100">
                                    <i class="bi bi-send me-1"></i>Berlangganan
                                </button>
                            </div>
                        </form>
                        
                        <small class="d-block mt-3 opacity-75">
                            <i class="bi bi-shield-check me-1"></i>
                            Kami menghormati privasi Anda. Tidak ada spam!
                        </small>
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
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }
    
    .object-fit-cover {
        object-fit: cover;
    }
    
    .card:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
    }
</style>
@endpush
