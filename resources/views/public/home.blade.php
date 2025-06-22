@extends('layouts.public')

@section('title', 'UNAS Fest 2025 - Festival Kompetisi Universitas Nasional')

@section('content')
<!-- Hero Section -->
<section id="hero" class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">
                        <span class="text-primary">UNAS FEST</span>
                        <span class="text-white">2025</span>
                    </h1>
                    <p class="hero-subtitle">
                        Festival Kompetisi Terbesar Universitas Nasional
                    </p>
                    <p class="hero-description">
                        Bergabunglah dalam kompetisi bergengsi yang menggabungkan teknologi, kreativitas, 
                        dan inovasi untuk masa depan yang lebih baik.
                    </p>
                    <div class="hero-actions">
                        <a href="#competitions" class="btn btn-primary btn-lg me-3">
                            <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                        </a>
                        <a href="#about" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-info-circle me-2"></i>Tentang Kami
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <img src="{{ asset('images/hero-illustration.svg') }}" alt="UNAS Fest 2025" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <a href="#competitions" class="scroll-link">
            <i class="bi bi-chevron-down"></i>
        </a>
    </div>
</section>

<!-- Competitions Section -->
<section id="competitions" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="section-title">Kompetisi Tersedia</h2>
                <p class="section-subtitle">
                    Pilih kompetisi yang sesuai dengan minat dan keahlian Anda
                </p>
            </div>
        </div>
        
        <div class="row">
            @forelse($competitions as $competition)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="competition-card h-100">
                    <div class="card-header">
                        <div class="competition-category">
                            {{ $competition->category }}
                        </div>
                        @if($competition->featured_image)
                            <img src="{{ asset('storage/' . $competition->featured_image) }}" 
                                 alt="{{ $competition->name }}" class="competition-image">
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="competition-title">{{ $competition->name }}</h5>
                        <p class="competition-description">
                            {{ Str::limit($competition->description, 100) }}
                        </p>
                        
                        <div class="competition-meta">
                            <div class="meta-item">
                                <i class="bi bi-calendar-event"></i>
                                <span>{{ $competition->registration_end ? $competition->registration_end->format('d M Y') : 'TBA' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-people"></i>
                                <span>{{ $competition->is_team_competition ? 'Tim' : 'Individual' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-currency-dollar"></i>
                                <span>Rp {{ number_format($competition->registration_fee, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        @auth
                            @php
                                $userRegistration = $competition->registrations()->where('user_id', auth()->id())->first();
                            @endphp
                            
                            @if($userRegistration)
                                @if($userRegistration->status === 'confirmed')
                                    <button class="btn btn-success w-100" disabled>
                                        <i class="bi bi-check-circle me-2"></i>Sudah Terdaftar
                                    </button>
                                @else
                                    <button class="btn btn-warning w-100" disabled>
                                        <i class="bi bi-clock me-2"></i>Menunggu Konfirmasi
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('peserta.competitions.show', $competition) }}" class="btn btn-primary w-100">
                                    <i class="bi bi-arrow-right me-2"></i>Daftar Sekarang
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Daftar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="empty-state">
                    <i class="bi bi-trophy display-1 text-muted"></i>
                    <h4 class="mt-3">Kompetisi Segera Hadir</h4>
                    <p class="text-muted">Pantau terus untuk update kompetisi terbaru!</p>
                </div>
            </div>
            @endforelse
        </div>
        
        @if($competitions->count() > 6)
        <div class="text-center mt-4">
            <a href="{{ route('peserta.competitions.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-grid me-2"></i>Lihat Semua Kompetisi
            </a>
        </div>
        @endif
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-content">
                    <h2 class="section-title">Tentang UNAS Fest 2025</h2>
                    <p class="lead">
                        UNAS Fest adalah festival kompetisi tahunan yang diselenggarakan oleh 
                        Universitas Nasional untuk mengembangkan potensi mahasiswa di berbagai bidang.
                    </p>
                    <p>
                        Dengan tema <strong>"Inovasi untuk Masa Depan"</strong>, UNAS Fest 2025 
                        menghadirkan berbagai kompetisi yang menggabungkan teknologi, kreativitas, 
                        dan kewirausahaan.
                    </p>
                    
                    <div class="about-features">
                        <div class="feature-item">
                            <i class="bi bi-award-fill text-primary"></i>
                            <div>
                                <h6>Kompetisi Bergengsi</h6>
                                <p>Kompetisi dengan standar nasional dan internasional</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-people-fill text-primary"></i>
                            <div>
                                <h6>Networking</h6>
                                <p>Kesempatan bertemu dengan para ahli dan praktisi</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-lightbulb-fill text-primary"></i>
                            <div>
                                <h6>Pengembangan Skill</h6>
                                <p>Platform untuk mengasah dan mengembangkan kemampuan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-image">
                    <img src="{{ asset('images/about-illustration.svg') }}" alt="About UNAS Fest" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <div class="stat-item">
                    <h3 class="stat-number">{{ $stats['total_competitions'] ?? 0 }}</h3>
                    <p class="stat-label">Kompetisi</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <div class="stat-item">
                    <h3 class="stat-number">{{ $stats['total_participants'] ?? 0 }}</h3>
                    <p class="stat-label">Peserta</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <div class="stat-item">
                    <h3 class="stat-number">{{ $stats['total_universities'] ?? 0 }}</h3>
                    <p class="stat-label">Universitas</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <h3 class="stat-number">Rp {{ number_format($stats['total_prizes'] ?? 0, 0, ',', '.') }}</h3>
                    <p class="stat-label">Total Hadiah</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="section-title">Hubungi Kami</h2>
                <p class="section-subtitle">
                    Ada pertanyaan? Jangan ragu untuk menghubungi tim kami
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="contact-form">
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="bi bi-geo-alt-fill text-primary"></i>
                        <div>
                            <h6>Alamat</h6>
                            <p>Universitas Nasional<br>
                            Jl. Sawo Manila, Pejaten<br>
                            Jakarta Selatan 12560</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill text-primary"></i>
                        <div>
                            <h6>Telepon</h6>
                            <p>+62 21 7806700</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-envelope-fill text-primary"></i>
                        <div>
                            <h6>Email</h6>
                            <p>info@unasfest.com</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-clock-fill text-primary"></i>
                        <div>
                            <h6>Jam Operasional</h6>
                            <p>Senin - Jumat: 08:00 - 17:00<br>
                            Sabtu: 08:00 - 12:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Hero Section */
.hero-section {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
}

.hero-content {
    position: relative;
    z-index: 2;
    color: white;
}

.hero-title {
    font-size: 4rem;
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.1;
}

.hero-subtitle {
    font-size: 1.5rem;
    font-weight: 300;
    margin-bottom: 1.5rem;
    color: rgba(255, 255, 255, 0.9);
}

.hero-description {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    color: rgba(255, 255, 255, 0.8);
}

.hero-actions .btn {
    padding: 12px 30px;
    font-weight: 600;
    border-radius: 50px;
}

.hero-image {
    position: relative;
    z-index: 2;
}

.scroll-indicator {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
}

.scroll-link {
    color: white;
    font-size: 2rem;
    animation: bounce 2s infinite;
    text-decoration: none;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

/* Section Styles */
.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.2rem;
    color: #6c757d;
    margin-bottom: 2rem;
}

/* Competition Cards */
.competition-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    border: none;
}

.competition-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.competition-card .card-header {
    position: relative;
    padding: 0;
    border: none;
    background: none;
}

.competition-category {
    position: absolute;
    top: 15px;
    left: 15px;
    background: rgba(0, 123, 255, 0.9);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    z-index: 2;
}

.competition-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.competition-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.competition-description {
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 1rem;
}

.competition-meta {
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #6c757d;
}

.meta-item i {
    margin-right: 8px;
    width: 16px;
    color: #007bff;
}

/* About Section */
.about-features {
    margin-top: 2rem;
}

.feature-item {
    display: flex;
    align-items-flex-start;
    margin-bottom: 1.5rem;
}

.feature-item i {
    font-size: 2rem;
    margin-right: 1rem;
    margin-top: 0.25rem;
}

.feature-item h6 {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.feature-item p {
    color: #6c757d;
    margin: 0;
}

/* Statistics Section */
.stat-item {
    padding: 2rem 1rem;
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1.1rem;
    font-weight: 300;
    opacity: 0.9;
}

/* Contact Section */
.contact-form {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.contact-info {
    padding: 2rem;
}

.contact-item {
    display: flex;
    align-items-flex-start;
    margin-bottom: 2rem;
}

.contact-item i {
    font-size: 1.5rem;
    margin-right: 1rem;
    margin-top: 0.25rem;
}

.contact-item h6 {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.contact-item p {
    color: #6c757d;
    margin: 0;
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.2rem;
    }

    .section-title {
        font-size: 2rem;
    }

    .stat-number {
        font-size: 2rem;
    }
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}

/* Section Padding */
section {
    scroll-margin-top: 80px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
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

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.competition-card, .feature-item, .stat-item, .contact-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
});
</script>
@endpush
