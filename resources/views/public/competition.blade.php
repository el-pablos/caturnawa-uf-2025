@extends('layouts.public')

@php
    $seoPage = 'competition';
    $seoData = [
        'title' => $competition->name . ' - UNAS Fest 2025',
        'description' => $competition->description,
        'keywords' => 'kompetisi ' . strtolower($competition->category) . ', ' . strtolower($competition->name) . ', unas fest 2025',
        'og_image' => $competition->image ? asset('storage/' . $competition->image) : null,
    ];
@endphp

@section('content')
<!-- Hero Section -->
<section class="competition-hero">
    <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>

    <div class="container position-relative">
        <div class="row align-items-center" style="min-height: 80vh;">
            <div class="col-lg-8" data-aos="fade-up">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('public.home') }}" class="text-white-50">
                                <i class="bi bi-house me-1"></i>Beranda
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('public.competitions') }}" class="text-white-50">Kompetisi</a>
                        </li>
                        <li class="breadcrumb-item active text-white">{{ $competition->name }}</li>
                    </ol>
                </nav>

                <!-- Category Badge -->
                <div class="category-badge mb-3">
                    <i class="bi bi-{{ $competition->category === 'technology' ? 'laptop' : ($competition->category === 'health' ? 'heart-pulse' : 'tree') }} me-2"></i>
                    {{ ucfirst($competition->category) }}
                </div>

                <!-- Title and Description -->
                <h1 class="hero-title">{{ $competition->name }}</h1>
                <p class="hero-subtitle">{{ $competition->description }}</p>

                <!-- Quick Info -->
                <div class="quick-info-grid" data-aos="fade-up" data-aos-delay="200">
                    <div class="quick-info-item">
                        <i class="bi bi-calendar-event"></i>
                        <div>
                            <span class="info-label">Pendaftaran</span>
                            <span class="info-value">{{ $competition->registration_start->format('d M') }} - {{ $competition->registration_end->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="quick-info-item">
                        <i class="bi bi-people"></i>
                        <div>
                            <span class="info-label">Peserta</span>
                            <span class="info-value">{{ $registrationsCount ?? 0 }}/{{ $competition->max_participants ?? '∞' }}</span>
                        </div>
                    </div>
                    <div class="quick-info-item">
                        <i class="bi bi-trophy"></i>
                        <div>
                            <span class="info-label">Hadiah</span>
                            <span class="info-value">Rp {{ number_format($competition->price ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="hero-actions" data-aos="fade-up" data-aos-delay="300">
                    @auth
                        @if(auth()->user()->hasRole('Peserta'))
                            @if(now() >= $competition->registration_start && now() <= $competition->registration_end)
                                <a href="{{ route('peserta.competitions.show', $competition) }}" class="btn-primary-custom">
                                    <i class="bi bi-person-plus me-2"></i>
                                    <span>Daftar Sekarang</span>
                                </a>
                            @else
                                <button class="btn-secondary-custom" disabled>
                                    <i class="bi bi-calendar-x me-2"></i>
                                    <span>Pendaftaran Ditutup</span>
                                </button>
                            @endif
                        @else
                            <div class="alert alert-warning">
                                <small>Hanya peserta yang dapat mendaftar kompetisi</small>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-primary-custom">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            <span>Masuk untuk Daftar</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn-secondary-custom">
                            <i class="bi bi-person-plus me-2"></i>
                            <span>Buat Akun</span>
                        </a>
                    @endauth
                </div>
            </div>

            <div class="col-lg-4" data-aos="fade-left" data-aos-delay="400">
                <!-- Competition Image -->
                <div class="competition-image-container">
                    @if($competition->image)
                        <img src="{{ asset('storage/' . $competition->image) }}" 
                             class="competition-hero-image" 
                             alt="{{ $competition->name }}">
                    @else
                        <div class="competition-hero-placeholder">
                            <i class="bi bi-trophy"></i>
                            <span>{{ $competition->name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Competition Details -->
<section class="competition-details">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- About Competition -->
                <div class="detail-card" data-aos="fade-up">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i>
                        <h3>Tentang Kompetisi</h3>
                    </div>
                    <div class="card-content">
                        <p>{{ $competition->description }}</p>
                        
                        @if($competition->theme)
                            <div class="theme-section">
                                <h6><i class="bi bi-lightbulb me-2"></i>Tema</h6>
                                <p class="text-muted">{{ $competition->theme }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Requirements -->
                @if($competition->requirements)
                <div class="detail-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-header">
                        <i class="bi bi-clipboard-check"></i>
                        <h3>Persyaratan</h3>
                    </div>
                    <div class="card-content">
                        @if(is_array($competition->requirements))
                            <ul class="requirements-list">
                                @foreach($competition->requirements as $requirement)
                                    <li>
                                        <i class="bi bi-check-circle text-success"></i>
                                        <span>{{ $requirement }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ $competition->requirements }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Rules -->
                @if($competition->rules)
                <div class="detail-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header">
                        <i class="bi bi-shield-check"></i>
                        <h3>Aturan & Ketentuan</h3>
                    </div>
                    <div class="card-content">
                        @if(is_array($competition->rules))
                            <ol class="rules-list">
                                @foreach($competition->rules as $rule)
                                    <li>{{ $rule }}</li>
                                @endforeach
                            </ol>
                        @else
                            <p>{{ $competition->rules }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Prizes -->
                @if($competition->prizes)
                <div class="detail-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-header">
                        <i class="bi bi-award"></i>
                        <h3>Hadiah</h3>
                    </div>
                    <div class="card-content">
                        @if(is_array($competition->prizes))
                            <div class="prizes-grid">
                                @foreach($competition->prizes as $position => $prize)
                                    <div class="prize-item">
                                        <div class="prize-icon">
                                            <i class="bi bi-trophy 
                                                @if($loop->first) text-warning
                                                @elseif($loop->iteration == 2) text-secondary
                                                @else text-bronze @endif"></i>
                                        </div>
                                        <div class="prize-info">
                                            <h6>{{ is_numeric($position) ? 'Juara ' . ($position + 1) : $position }}</h6>
                                            <p>{{ $prize }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>{{ $competition->prizes }}</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Registration Info -->
                <div class="sidebar-card sticky-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-header">
                        <i class="bi bi-calendar-plus"></i>
                        <h4>Informasi Pendaftaran</h4>
                    </div>
                    <div class="card-content">
                        <!-- Price Display -->
                        <div class="price-section">
                            @if($competition->early_bird_deadline && now() <= $competition->early_bird_deadline)
                                <div class="price-badge early-bird">
                                    <i class="bi bi-lightning"></i>
                                    <span>Early Bird!</span>
                                </div>
                                <div class="price-display">
                                    <span class="current-price">Rp {{ number_format($competition->early_bird_price, 0, ',', '.') }}</span>
                                    <span class="original-price">Rp {{ number_format($competition->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="price-note">
                                    Valid sampai {{ $competition->early_bird_deadline->format('d M Y') }}
                                </div>
                            @else
                                <div class="price-display">
                                    <span class="price-label">Biaya Pendaftaran</span>
                                    <span class="current-price">Rp {{ number_format($competition->price, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Timeline -->
                        <div class="timeline-section">
                            <h6><i class="bi bi-clock me-2"></i>Timeline</h6>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="bi bi-calendar-plus"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Pendaftaran Dibuka</h6>
                                        <p>{{ $competition->registration_start->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="bi bi-calendar-x"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Pendaftaran Ditutup</h6>
                                        <p>{{ $competition->registration_end->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="bi bi-play-circle"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Kompetisi Dimulai</h6>
                                        <p>{{ $competition->competition_start->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="bi bi-flag-checkered"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6>Kompetisi Berakhir</h6>
                                        <p>{{ $competition->competition_end->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Participants Progress -->
                        @if($competition->max_participants)
                        <div class="participants-section">
                            @php
                                $registered = $registrationsCount ?? 0;
                                $percentage = $competition->max_participants > 0 ? ($registered / $competition->max_participants) * 100 : 0;
                            @endphp
                            <div class="participants-header">
                                <h6><i class="bi bi-people me-2"></i>Peserta Terdaftar</h6>
                                <span class="participants-count">{{ $registered }}/{{ $competition->max_participants }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                            </div>
                            @if($percentage >= 80)
                                <div class="participants-warning">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <span>Hampir penuh!</span>
                                </div>
                            @endif
                        </div>
                        @endif

                        <!-- Team Info -->
                        @if($competition->is_team_competition)
                        <div class="team-info">
                            <div class="team-badge">
                                <i class="bi bi-people"></i>
                                <span>Kompetisi Tim</span>
                            </div>
                            @if($competition->min_team_members && $competition->max_team_members)
                                <p class="team-size">
                                    Ukuran tim: {{ $competition->min_team_members }}-{{ $competition->max_team_members }} anggota
                                </p>
                            @endif
                        </div>
                        @endif

                        <!-- Registration Button -->
                        <div class="registration-cta">
                            @auth
                                @if(auth()->user()->hasRole('Peserta'))
                                    @if(now() >= $competition->registration_start && now() <= $competition->registration_end)
                                        <a href="{{ route('peserta.competitions.show', $competition) }}" class="btn-register">
                                            <i class="bi bi-person-plus me-2"></i>
                                            Daftar Kompetisi
                                        </a>
                                    @else
                                        <button class="btn-register disabled" disabled>
                                            <i class="bi bi-calendar-x me-2"></i>
                                            Pendaftaran Ditutup
                                        </button>
                                    @endif
                                @else
                                    <div class="role-warning">
                                        <i class="bi bi-info-circle"></i>
                                        <span>Hanya peserta yang dapat mendaftar</span>
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-register">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Masuk untuk Daftar
                                </a>
                                <a href="{{ route('register') }}" class="btn-register-secondary">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Buat Akun Baru
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Contact Person -->
                @if($competition->contact_person)
                <div class="sidebar-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-header">
                        <i class="bi bi-person-lines-fill"></i>
                        <h5>Narahubung</h5>
                    </div>
                    <div class="card-content">
                        <div class="contact-info">
                            <i class="bi bi-person-circle"></i>
                            <span>{{ $competition->contact_person }}</span>
                        </div>
                        <div class="contact-info">
                            <i class="bi bi-telephone"></i>
                            <a href="tel:0858-1737-8442">0858-1737-8442</a>
                        </div>
                        <div class="contact-info">
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:info@unasfest.com">info@unasfest.com</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Related Competitions -->
<section class="related-competitions">
    <div class="container">
        <div class="section-header text-center" data-aos="fade-up">
            <h2>Kompetisi Lainnya</h2>
            <p>Jelajahi kompetisi menarik lainnya yang tersedia</p>
        </div>
        
        <div class="row">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="related-card">
                    <div class="related-icon technology">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h5>Kompetisi Teknologi</h5>
                    <p>Wujudkan inovasi teknologi terdepan</p>
                    <a href="{{ route('public.competitions') }}#technology" class="related-link">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="related-card">
                    <div class="related-icon health">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h5>Kompetisi Kesehatan</h5>
                    <p>Solusi inovatif untuk kesehatan masyarakat</p>
                    <a href="{{ route('public.competitions') }}#health" class="related-link">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="related-card">
                    <div class="related-icon biodiversity">
                        <i class="bi bi-tree"></i>
                    </div>
                    <h5>Kompetisi Biodiversitas</h5>
                    <p>Konservasi dan pelestarian lingkungan</p>
                    <a href="{{ route('public.competitions') }}#biodiversity" class="related-link">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection@push('styles')
<style>
    /* Competition Hero Section */
    .competition-hero {
        min-height: 80vh;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        color: white;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
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

    .breadcrumb {
        background: none;
        padding: 0;
        margin: 0;
    }

    .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: white;
    }

    .category-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        line-height: 1.1;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .quick-info-grid {
        display: flex;
        gap: 30px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    .quick-info-item {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.1);
        padding: 15px 20px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .quick-info-item i {
        font-size: 1.5rem;
        color: var(--accent-color);
    }

    .info-label {
        display: block;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
    }

    .info-value {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        color: white;
    }

    .hero-actions {
        display: flex;
        gap: 20px;
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
        border: none;
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
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-secondary-custom:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        transform: translateY(-3px);
    }

    .btn-secondary-custom:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .competition-image-container {
        position: relative;
    }

    .competition-hero-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        border: 3px solid rgba(255, 255, 255, 0.2);
    }

    .competition-hero-placeholder {
        width: 100%;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255, 255, 255, 0.2);
        text-align: center;
    }

    .competition-hero-placeholder i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: var(--accent-color);
    }

    .competition-hero-placeholder span {
        font-size: 1.2rem;
        font-weight: 600;
        color: white;
    }

    /* Competition Details Section */
    .competition-details {
        padding: 80px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 20px 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .card-header i {
        font-size: 1.5rem;
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .card-content {
        padding: 30px;
    }

    .theme-section {
        margin-top: 25px;
        padding-top: 25px;
        border-top: 1px solid #e2e8f0;
    }

    .theme-section h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 10px;
    }

    .requirements-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .requirements-list li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 15px;
        padding: 15px;
        background: #f8fafc;
        border-radius: 10px;
        border-left: 4px solid var(--success-color);
    }

    .requirements-list i {
        font-size: 1.2rem;
        margin-top: 2px;
    }

    .rules-list {
        counter-reset: rule-counter;
        padding: 0;
        margin: 0;
    }

    .rules-list li {
        counter-increment: rule-counter;
        margin-bottom: 15px;
        padding: 15px 15px 15px 50px;
        background: #f8fafc;
        border-radius: 10px;
        position: relative;
        border-left: 4px solid var(--primary-color);
    }

    .rules-list li::before {
        content: counter(rule-counter);
        position: absolute;
        left: 15px;
        top: 15px;
        background: var(--primary-color);
        color: white;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .prizes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .prize-item {
        background: #f8fafc;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .prize-item:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
    }

    .prize-icon {
        margin-bottom: 15px;
    }

    .prize-icon i {
        font-size: 2.5rem;
    }

    .text-bronze {
        color: #cd7f32;
    }

    .prize-info h6 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 10px;
    }

    /* Sidebar Styles */
    .sidebar-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .sidebar-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .sticky-card {
        position: sticky;
        top: 100px;
    }

    .sidebar-card .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 20px 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar-card .card-header h4,
    .sidebar-card .card-header h5 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .sidebar-card .card-content {
        padding: 25px;
    }

    .price-section {
        margin-bottom: 30px;
        text-align: center;
    }

    .price-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #ff6b35, #f7931e);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .price-display {
        margin-bottom: 10px;
    }

    .price-label {
        display: block;
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 8px;
    }

    .current-price {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary-color);
        display: block;
    }

    .original-price {
        font-size: 1.2rem;
        color: #94a3b8;
        text-decoration: line-through;
        display: block;
        margin-top: 5px;
    }

    .price-note {
        font-size: 0.85rem;
        color: #64748b;
        font-style: italic;
    }

    .timeline-section {
        margin-bottom: 30px;
    }

    .timeline-section h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 20px;
    }

    .timeline {
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 25px;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        background: white;
        border: 3px solid var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1rem;
        z-index: 2;
        position: relative;
    }

    .timeline-content h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 5px;
    }

    .timeline-content p {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }

    .participants-section {
        margin-bottom: 30px;
    }

    .participants-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .participants-header h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin: 0;
    }

    .participants-count {
        background: var(--primary-color);
        color: white;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .progress-bar {
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        transition: width 0.3s ease;
    }

    .participants-warning {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--warning-color);
        font-size: 0.85rem;
        font-weight: 600;
    }

    .team-info {
        margin-bottom: 30px;
    }

    .team-badge {
        display: flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, var(--success-color), #059669);
        color: white;
        padding: 12px 18px;
        border-radius: 15px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .team-size {
        font-size: 0.9rem;
        color: #64748b;
        margin: 0;
    }

    .registration-cta {
        margin-bottom: 20px;
    }

    .btn-register {
        width: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 15px 20px;
        border-radius: 15px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        border: none;
        margin-bottom: 15px;
    }

    .btn-register:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
    }

    .btn-register.disabled {
        background: #94a3b8;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .btn-register-secondary {
        width: 100%;
        background: transparent;
        color: var(--primary-color);
        padding: 12px 20px;
        border: 2px solid var(--primary-color);
        border-radius: 15px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .btn-register-secondary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    .role-warning {
        background: #fef3cd;
        color: #856404;
        padding: 15px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
    }

    .contact-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        padding: 10px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .contact-info i {
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .contact-info a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    .contact-info a:hover {
        text-decoration: underline;
    }

    /* Related Competitions */
    .related-competitions {
        padding: 80px 0;
        background: white;
    }

    .section-header {
        margin-bottom: 60px;
    }

    .section-header h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .section-header p {
        font-size: 1.1rem;
        color: #64748b;
    }

    .related-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        height: 100%;
    }

    .related-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-color);
    }

    .related-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: white;
    }

    .related-icon.technology {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .related-icon.health {
        background: linear-gradient(135deg, var(--success-color), #059669);
    }

    .related-icon.biodiversity {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .related-card h5 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 15px;
    }

    .related-card p {
        color: #64748b;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .related-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .related-link:hover {
        color: var(--secondary-color);
        gap: 12px;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .quick-info-grid {
            justify-content: center;
        }

        .sticky-card {
            position: static;
        }

        .prizes-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .competition-hero {
            min-height: 70vh;
            text-align: center;
        }

        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .quick-info-grid {
            flex-direction: column;
            align-items: center;
        }

        .quick-info-item {
            width: 100%;
            max-width: 300px;
        }

        .hero-actions {
            flex-direction: column;
            align-items: center;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 100%;
            max-width: 280px;
            justify-content: center;
        }

        .competition-hero-image,
        .competition-hero-placeholder {
            height: 250px;
        }

        .card-content {
            padding: 20px;
        }

        .timeline::before {
            left: 15px;
        }

        .timeline-icon {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 576px) {
        .competition-details {
            padding: 40px 0;
        }

        .related-competitions {
            padding: 40px 0;
        }

        .section-header h2 {
            font-size: 2rem;
        }

        .detail-card,
        .sidebar-card {
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px 20px;
        }

        .card-content {
            padding: 15px;
        }

        .related-card {
            padding: 20px;
        }
    }
</style>
@endpush