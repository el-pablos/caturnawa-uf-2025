@extends('layouts.simple')

@php
    $seoPage = 'terms';
@endphp

@section('title', 'Terms & Conditions - Caturnawa UNAS FEST 2025')

@section('content')
<style>
    .modern-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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
        animation: floatTrophy 3s ease-in-out infinite;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
    }
    
    @keyframes floatTrophy {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        color: #4a5568;
    }
    
    .glass-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .glass-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-bottom: 1px solid rgba(0,0,0,0.05);
        position: relative;
    }
    
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

    }    .modern-container {
        position: relative;
        z-index: 1;
    }

    .terms-content h3 {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .terms-content p, .terms-content ul {
        color: #718096;
        line-height: 1.8;
    }

    .terms-content ul {
        padding-left: 20px;
    }

    .terms-content ul li {
        margin-bottom: 0.5rem;
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
                        <i class="bi bi-file-text"></i>
                    </div>
                    <h1 class="modern-title mb-4">
                        Terms & Conditions
                    </h1>
                    <p class="modern-subtitle mb-0">
                        Please read carefully before participating in Caturnawa UNAS FEST 2025.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Content -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="glass-card">
                <div class="glass-header p-4">
                    <h2 class="card-title mb-0 text-center fw-bold" style="color: #764ba2;">
                        <i class="bi bi-file-earmark-text me-2"></i>Terms & Conditions Details
                    </h2>
                </div>
                <div class="card-body p-5 terms-content">
                    <div class="mb-5">
                        <h3 class="fw-bold">1. Acceptance of Terms</h3>
                        <p>
                            By accessing and using the Caturnawa UNAS FEST 2025 services, you agree to be bound by these terms and conditions. If you do not agree to these terms, please do not use our services.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold">2. Registration and Account</h3>
                        <p>To participate in competitions, you must:</p>
                        <ul>
                            <li>Provide accurate and complete information</li>
                            <li>Maintain the confidentiality of your account and password</li>
                            <li>Be responsible for all activities in your account</li>
                            <li>Immediately report unauthorized account usage</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold">3. Competition Rules</h3>
                        <p>Competition participants must:</p>
                        <ul>
                            <li>Follow all competition rules and guidelines</li>
                            <li>Submit original work that does not violate copyright</li>
                            <li>Respect other participants and organizers</li>
                            <li>Accept judges' decisions as final</li>
                            <li>Attend mandatory events</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold">4. Intellectual Property Rights</h3>
                        <p>
                            Participants retain copyright to their work, but grant permission to UNAS Fest to:
                        </p>
                        <ul>
                            <li>Display work for evaluation purposes</li>
                            <li>Publish winning entries for promotion</li>
                            <li>Use work for event documentation</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold">5. Payment and Refunds</h3>
                        <p>Payment terms:</p>
                        <ul>
                            <li>Registration fees must be paid according to schedule</li>
                            <li>Payments made are non-refundable</li>
                            <li>Refund exceptions only under certain conditions</li>
                            <li>All additional costs are the participant's responsibility</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold">6. Disqualification</h3>
                        <p>
                            Organizers reserve the right to disqualify participants who:
                        </p>
                        <ul>
                            <li>Violate competition rules</li>
                            <li>Provide false information</li>
                            <li>Commit plagiarism or copyright infringement</li>
                            <li>Behave inappropriately or harmfully</li>
                            <li>Fail to meet established requirements</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="fw-bold">7. Limitation of Liability</h3>
                        <p>
                            UNAS Fest is not responsible for:
                        </p>
                        <ul>
                            <li>Losses arising from participation in competitions</li>
                            <li>Technical problems or system disruptions</li>
                            <li>Loss or damage to submitted work</li>
                            <li>Schedule changes or event cancellations</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold">8. Changes to Terms</h3>
                        <p>
                            We reserve the right to change these terms and conditions at any time. Changes will be notified through the official website and registered email.
                        </p>
                    </div>
                </div>
                <div class="card-footer bg-transparent p-5">
                    <hr class="my-5" style="border-color: rgba(0,0,0,0.1);">

                    <div class="text-center">
                        <p class="text-muted small mb-4">
                            These terms and conditions were last updated on {{ date('F d, Y') }}
                        </p>
                        <h3 class="fw-bold mb-4" style="color: #667eea;">Have Other Questions?</h3>
                        <p class="text-muted mb-4">
                            If any part of these terms and conditions is unclear, please don't hesitate to contact us.
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-auto mb-2">
                                <a href="{{ route('public.contact') }}" class="btn modern-btn w-auto">
                                    <i class="bi bi-envelope me-2"></i>Contact Us
                                </a>
                            </div>
                            <div class="col-md-auto mb-2">
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
</div>
@endsection
