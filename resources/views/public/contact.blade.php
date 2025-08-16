@extends('layouts.simple')

@php
    $seoPage = 'contact';
@endphp

@section('title', 'Contact Kami - Caturnawa UNAS FEST 2025')

@push('styles')
<style>

    .modern-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        color: white;
    }


    .modern-hero::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 30s linear infinite;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .modern-title {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(45deg, #fff, #f8f9fa, #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        letter-spacing: -1px;
    }

    .modern-container {
        position: relative;
        z-index: 1;
    }

    .modern-subtitle {
        font-size: 1.3rem;
        font-weight: 300;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .floating-icon {
        font-size: 4rem;
        animation: floatIcon 3s ease-in-out infinite;
        display: inline-block;
        -webkit-text-fill-color: white;
        text-shadow: none;
    }

    @keyframes floatIcon {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
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
    }

    .modern-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.07);
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    }

    .modern-card .card-header {
        border-radius: 15px 15px 0 0 !important;
        font-weight: 600;
    }

    .modern-card .card-header .card-title i {
        font-size: 0.9em;
        vertical-align: -1px;
    }

    .info-card .list-group-item {
        border: none;
        padding: 1rem 1.25rem;
    }

    .info-icon {
        font-size: 1.2rem;
        width: 35px;
        text-align: center;
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
    }

    @media (max-width: 768px) {
        .modern-title {
            font-size: 2.5rem;
        }

        .modern-subtitle {
            font-size: 1rem;
        }

        .floating-trophy {
            font-size: 3rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dynamic-bg"></div>
<div class="container my-5 modern-container">
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero p-5 rounded mb-5">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                    <div class="hero-content text-center">
                        <div class="floating-icon mb-4">
                            <i class="bi bi-headset mb-3"></i>
                        </div>
                        <h1 class="modern-title mb-3">
                            Contact Us
                        </h1>
                            <p class="modern-subtitle mb-4">
                            Have questions about Caturnawa UNAS FEST 2025? Our team is ready to help you.
                            </p>
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-lg-3 mb-3">
                            <a href="https://wa.me/6288219445100" class="btn modern-btn btn-auto w-100" target="_blank">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-3">
                            <a href="#contact-form" class="btn modern-btn-outline btn-auto w-100">
                                <i class="bi bi-envelope me-2"></i>Send Message
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Contact Form & Info Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center fw-bold mb-4" style="color: #667eea;">
                <i class="bi bi-chat-dots me-2"></i>
                Contact Our Team
            </h2>
        </div>
    </div>

    <div class="row g-4">
        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="card-header p-3 bg-primary text-white">
                    <h3 class="card-title mb-0"><i class="bi bi-envelope-fill me-2 w-auto"></i>Send Your Message</h3>
                </div>
                <div class="card-body p-4" id="contact-form">
                    <form action="{{ route('public.contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label">Subject</label>
                                <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                    <option value="">Choose Subject</option>
                                    <option value="Competition Information" {{ old('subject') == 'Competition Information' ? 'selected' : '' }}>Competition Information</option>
                                    <option value="Registration" {{ old('subject') == 'Registration' ? 'selected' : '' }}>Registration</option>
                                    <option value="Payment" {{ old('subject') == 'Payment' ? 'selected' : '' }}>Payment</option>
                                    <option value="Technical Website" {{ old('subject') == 'Technical Website' ? 'selected' : '' }}>Technical Website</option>
                                    <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                    <option value="Others" {{ old('subject') == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-send me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="col-lg-4" data-aos="fade-left">
            @php $seo = app(\App\Services\SEOService::class); @endphp
            @php $contact = $seo->getContactInfo(); @endphp
            
            <div class="modern-card info-card mb-4">
                <div class="card-header p-3 bg-info text-white">
                    <h4 class="card-title mb-0"><i class="bi bi-info-circle-fill me-2"></i>Contact Information</h4>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-envelope-fill text-primary info-icon me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Email</h6>
                            <a href="mailto:{{ $contact['email'] }}" class="text-decoration-none">{{ $contact['email'] }}</a>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-telephone-fill text-success info-icon me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Phone</h6>
                            <a href="tel:{{ $contact['phone'] }}" class="text-decoration-none">{{ $contact['phone'] }}</a>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill text-danger info-icon me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Address</h6>
                            <p class="text-muted mb-0">{{   $contact['address'] }}</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="modern-card mb-4">
                <div class="card-header p-3 bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="bi bi-clock-fill me-2"></i>Operational Hours</h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between mb-2"><span>Monday - Friday</span><span class="fw-bold">8:00 AM - 5:00 PM</span></div>
                    <div class="d-flex justify-content-between mb-2"><span>Saturday</span><span class="fw-bold">8:00 AM - 12:00 PM</span></div>
                    <div class="d-flex justify-content-between"><span>Sunday</span><span class="text-danger fw-bold">Closed</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
        // Smooth scroll for anchor links
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
</script>
@endpush