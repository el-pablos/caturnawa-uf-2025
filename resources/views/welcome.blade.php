@extends('layouts.app')

@section('title', 'Welcome - Caturnawa UNAS FEST 2025')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Welcome to
                        <span class="text-gradient">Caturnawa UNAS FEST 2025</span>
                    </h1>
                    <p class="hero-subtitle">
                        The biggest competition festival at Universitas Nasional that combines creativity, 
                        innovation, and technology in various exciting competition categories.
                    </p>
                    <div class="hero-buttons">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">
                                <i class="bi bi-person-plus me-2"></i>Register Now
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </a>
                        @else
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
                                </a>
                            @elseif(auth()->user()->isJuri())
                                <a href="{{ route('juri.juri.dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-clipboard-check me-2"></i>Judge Dashboard
                                </a>
                            @else
                                <a href="{{ route('peserta.peserta.dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-trophy me-2"></i>Participant Dashboard
                                </a>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="floating-card">
                        <i class="bi bi-trophy-fill text-warning"></i>
                        <h3>Prestigious Competition</h3>
                        <p>Various competition categories with attractive prizes</p>
                    </div>
                    <div class="floating-card">
                        <i class="bi bi-people-fill text-info"></i>
                        <h3>Creative Community</h3>
                        <p>Join thousands of participants from all over Indonesia</p>
                    </div>
                    <div class="floating-card">
                        <i class="bi bi-award-fill text-success"></i>
                        <h3>Official Certificate</h3>
                        <p>Get recognition for your achievements</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="section-title">Why Choose Caturnawa UNAS FEST 2025?</h2>
                <p class="section-subtitle">Leading competition platform with various advantages</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <h4>Easy Registration</h4>
                    <p>Fast and user-friendly online registration system</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check-fill"></i>
                    </div>
                    <h4>Secure System</h4>
                    <p>Data and transaction security guaranteed with the latest technology</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h4>24/7 Support</h4>
                    <p>Support team ready to help you anytime</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="cta-title">Ready to Show Your Talent?</h2>
                <p class="cta-subtitle">
                    Join thousands of other participants and get the chance to win attractive prizes!
                </p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-rocket-takeoff me-2"></i>Start Now
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.text-gradient {
    background: linear-gradient(45deg, #ffd700, #ffed4e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-buttons .btn {
    border-radius: 50px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.hero-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.hero-image {
    position: relative;
    height: 500px;
}

.floating-card {
    position: absolute;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 1.5rem;
    color: #333;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    animation: float 6s ease-in-out infinite;
    max-width: 250px;
}

.floating-card:nth-child(1) {
    top: 50px;
    right: 50px;
    animation-delay: 0s;
}

.floating-card:nth-child(2) {
    top: 200px;
    left: 20px;
    animation-delay: 2s;
}

.floating-card:nth-child(3) {
    bottom: 50px;
    right: 20px;
    animation-delay: 4s;
}

.floating-card i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.floating-card h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.floating-card p {
    font-size: 0.9rem;
    margin: 0;
    opacity: 0.8;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.features-section {
    background: #f8f9fa;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 0;
}

.feature-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.feature-icon i {
    font-size: 2rem;
    color: white;
}

.feature-card h4 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.feature-card p {
    color: #666;
    margin: 0;
}

.cta-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .floating-card {
        position: static;
        margin-bottom: 1rem;
        animation: none;
    }
    
    .hero-image {
        height: auto;
        margin-top: 2rem;
    }
}
</style>
@endpush
