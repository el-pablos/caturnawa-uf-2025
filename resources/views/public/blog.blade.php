@extends('layouts.simple')

@section('title', 'Blog & Artikel - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5">
                <div class="text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-journal-text me-3"></i>Blog & Artikel
                    </h1>
                    <p class="lead mb-4">
                        Temukan tips, panduan, dan informasi terbaru seputar UNAS Fest 2025. 
                        Tingkatkan peluang sukses Anda dengan membaca artikel-artikel berkualitas dari tim ahli kami.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.competitions') }}" class="btn btn-warning btn-lg w-100">
                                <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#newsletter" class="btn btn-light btn-lg w-100">
                                <i class="bi bi-envelope me-2"></i>Berlangganan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-file-text-fill text-primary" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ count($posts) + 3 }}</h3>
                    <p class="text-muted">Artikel Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-eye-fill text-success" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">15,000+</h3>
                    <p class="text-muted">Total Pembaca</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Article -->
    @if(count($posts) > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">
                <i class="bi bi-star text-warning"></i> 
                Artikel Unggulan
            </h2>
        </div>
        <div class="col-12">
            <div class="card shadow">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <img src="{{ $posts[0]['featured_image'] }}" 
                             alt="{{ $posts[0]['title'] }}" 
                             class="w-100 h-100"
                             style="min-height: 300px; object-fit: cover;"
                             onerror="this.src='{{ asset('assets/images/blog/default-featured.jpg') }}'">
                    </div>
                    <div class="col-lg-6">
                        <div class="card-body p-4 h-100 d-flex flex-column">
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
                                    <i class="bi bi-person-circle text-primary me-2" style="font-size: 1.5rem;"></i>
                                    <small class="text-muted">{{ $posts[0]['author'] }}</small>
                                </div>
                                <a href="{{ route('public.blog.detail', $posts[0]['slug']) }}" class="btn btn-primary">
                                    Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Blog Posts Grid -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">
                <i class="bi bi-grid text-info"></i> 
                Artikel Terbaru
            </h2>
        </div>

        <!-- Existing Posts -->
        @foreach($posts as $index => $post)
            @if($index > 0) <!-- Skip first post as it's featured -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="position-relative">
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
                                <i class="bi bi-person-circle text-secondary me-2"></i>
                                <small class="text-muted">{{ $post['author'] }}</small>
                            </div>
                            <a href="{{ route('public.blog.detail', $post['slug']) }}" class="btn btn-outline-primary btn-sm">
                                Baca <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
        
        <!-- Additional Sample Posts -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <div class="bg-warning d-flex align-items-center justify-content-center" style="height: 200px;">
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
                            <i class="bi bi-person-circle text-secondary me-2"></i>
                            <small class="text-muted">Tim UNAS Fest</small>
                        </div>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            Baca <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <div class="bg-success d-flex align-items-center justify-content-center" style="height: 200px;">
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
                            <i class="bi bi-person-circle text-secondary me-2"></i>
                            <small class="text-muted">Tim UNAS Fest</small>
                        </div>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            Baca <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <div class="bg-info d-flex align-items-center justify-content-center" style="height: 200px;">
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
                            <i class="bi bi-person-circle text-secondary me-2"></i>
                            <small class="text-muted">Tim UNAS Fest</small>
                        </div>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            Baca <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="row" id="newsletter">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="card-title mb-0">
                        <i class="bi bi-envelope-heart me-2"></i>Berlangganan Newsletter
                    </h2>
                    <p class="mb-0">Dapatkan artikel terbaru, tips kompetisi, dan informasi penting langsung di email Anda!</p>
                </div>
                <div class="card-body p-4">
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
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            Kami menghormati privasi Anda. Tidak ada spam!
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection