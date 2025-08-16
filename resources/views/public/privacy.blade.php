@extends('layouts.simple')

@php
    $seoPage = 'privacy';
@endphp

@section('title', 'Privacy Policy - Caturnawa UNAS FEST 2025')

@section('content')
<style>
    .modern-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .modern-title {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(45deg, #fff, #f8f9fa, #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        letter-spacing: -1px;
    }
    
    .modern-subtitle {
        font-size: 1.3rem;
        font-weight: 300;
        color: rgba(255,255,255,0.9);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        line-height: 1.6;
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        color: #4a5568;
    }
    
    .glass-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .dynamic-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background: linear-gradient(45deg, #f8f9fa 0%, #e9ecef 100%);
        overflow: hidden;
    }
    
    .dynamic-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
            radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
        animation: float 20s ease-in-out infinite;
    }
    
    .modern-container {
        position: relative;
        z-index: 1;
    }

    .modern-btn {
        background: linear-gradient(45deg, #ff6b6b, #feca57);
        border: none;
        border-radius: 50px;
        padding: 15px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px rgba(255,107,107,0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }
    
    .modern-btn:hover::before {
        left: 100%;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(255,107,107,0.4);
    }
    
    .modern-btn-outline {
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 13px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white;
        transition: all 0.3s ease;
    }
    
    .modern-btn-outline:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255,255,255,0.2);
        color: white;
    }

    .floating-icon {
        font-size: 4rem;
        animation: floatIcon 3s ease-in-out infinite;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
    }
    
    @keyframes floatIcon {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(-5deg); }
    }

    /* Bubbles animation */
    .bubbles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }

    .bubbles li {
        position: absolute;
        list-style: none;
        display: block;
        width: 20px;
        height: 20px;
        background: rgba(255, 255, 255, 0.2);
        animation: animate-bubbles 25s linear infinite;
        bottom: -150px;
        border-radius: 50%;
    }

    .bubbles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
    .bubbles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
    .bubbles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
    .bubbles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
    .bubbles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
    .bubbles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
    .bubbles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
    .bubbles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
    .bubbles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
    .bubbles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

    @keyframes animate-bubbles {
        0% { transform: translateY(0) rotate(0deg); opacity: 1; }
        100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
</style>

<div class="dynamic-bg"></div>
<div class="container my-5 modern-container">
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5 mb-5">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                <div class="hero-content text-center">
                    <div class="floating-icon mb-4">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h1 class="modern-title mb-4">
                        Privacy Policy
                    </h1>
                    <p class="modern-subtitle mb-5 pt-2">
                        Our commitment to protecting and respecting your privacy at Caturnawa UNAS FEST 2025.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.contact') }}" class="btn modern-btn btn-lg w-100">
                                <i class="bi bi-envelope me-2"></i>Contact Us
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.home') }}" class="btn modern-btn-outline btn-lg w-100">
                                <i class="bi bi-house me-2"></i>Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Content -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="glass-card">
                <div class="glass-header p-4 text-center">
                    <h2 class="card-title mb-0 fw-bold" style="color: #764ba2;">
                        <i class="bi bi-shield-check me-2"></i>Privacy Policy Details
                    </h2>
                    <p class="mb-0 text-muted">Our commitment to protecting your data and privacy</p>
                </div>
                <div class="card-body p-5">
                    
                    <!-- Section 1 -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 text-primary">
                            <i class="bi bi-info-circle me-2"></i>1. Information We Collect
                        </h3>
                        <p class="text-muted mb-3">
                            We collect information that you provide directly to us, such as:
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Full name and contact information</li>
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Institution/university information</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Competition registration data</li>
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Documents and uploaded files</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 text-success">
                            <i class="bi bi-gear me-2"></i>2. How We Use Information
                        </h3>
                        <p class="text-muted mb-3">
                            The information we collect is used to:
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Process competition registrations</li>
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Communicate with participants</li>
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Organize events and competitions</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Provide technical support</li>
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Improve our services</li>
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Send updates and information</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 text-warning">
                            <i class="bi bi-shield-lock me-2"></i>3. Data Protection
                        </h3>
                        <p class="text-muted mb-3">
                            We are committed to protecting your personal information with:
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-warning mb-3">
                                    <div class="card-body text-center">
                                        <i class="bi bi-lock-fill text-warning" style="font-size: 2rem;"></i>
                                        <h5 class="mt-2">Data Encryption</h5>
                                        <p class="text-muted small">Sensitive data is encrypted with industry standards</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success mb-3">
                                    <div class="card-body text-center">
                                        <i class="bi bi-eye-slash-fill text-success" style="font-size: 2rem;"></i>
                                        <h5 class="mt-2">Limited Access</h5>
                                        <p class="text-muted small">Only authorized staff can access data</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 text-danger">
                            <i class="bi bi-share me-2"></i>4. Information Sharing
                        </h3>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> We will not sell, rent, or share your personal information with third parties without your consent.
                        </div>
                        <p class="text-muted mb-3">Exceptions only apply to:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-dot text-danger"></i>Competition organization purposes</li>
                            <li class="mb-2"><i class="bi bi-dot text-danger"></i>When required by law</li>
                            <li class="mb-2"><i class="bi bi-dot text-danger"></i>To protect rights and security</li>
                        </ul>
                    </div>

                    <!-- Section 5 -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 text-info">
                            <i class="bi bi-person-check me-2"></i>5. Your Rights
                        </h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-eye text-info me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Data Access</h6>
                                        <p class="text-muted small mb-0">Access your personal information</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-pencil text-info me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Correct Data</h6>
                                        <p class="text-muted small mb-0">Update or correct data</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-trash text-info me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Delete Data</h6>
                                        <p class="text-muted small mb-0">Delete your account and data</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-x-circle text-info me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Withdraw Consent</h6>
                                        <p class="text-muted small mb-0">Withdraw consent for data use</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="text-center">
                        <h3 class="fw-bold mb-3 text-secondary">
                            <i class="bi bi-telephone me-2"></i>Contact Us
                        </h3>
                        <p class="text-muted mb-4">
                            If you have any questions about this privacy policy, please contact us:
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <i class="bi bi-envelope text-primary" style="font-size: 1.5rem;"></i>
                                                <p class="small mb-1"><strong>Email</strong></p>
                                                <p class="small mb-0">privacy@unasfest.com</p>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <i class="bi bi-telephone text-success" style="font-size: 1.5rem;"></i>
                                                <p class="small mb-1"><strong>Phone</strong></p>
                                                <p class="small mb-0">+62 21 7806700</p>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <i class="bi bi-geo-alt text-danger" style="font-size: 1.5rem;"></i>
                                                <p class="small mb-1"><strong>Address</strong></p>
                                                <p class="small mb-0">UNAS Jakarta Selatan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar me-1"></i>
                                This privacy policy was last updated on {{ date('F d, Y') }}
                            </p>
                            <a href="{{ route('public.home') }}" class="btn modern-btn-outline w-auto" style="color: #764ba2; border-color: #764ba2;" onmouseover="this.style.backgroundColor='rgba(118, 75, 162, 0.1)'; this.style.color='#764ba2';" onmouseout="this.style.backgroundColor='transparent';">
                                <i class="bi bi-house me-2"></i>Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
