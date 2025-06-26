@extends('layouts.public')

@php
    $seoPage = 'contact';
@endphp

@section('content')
<!-- Hero Section -->
<section class="contact-hero">
    <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="floating-elements">
            <div class="element element-1"></div>
            <div class="element element-2"></div>
            <div class="element element-3"></div>
        </div>
    </div>

    <div class="container position-relative">
        <div class="row align-items-center justify-content-center text-center py-5">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="hero-badge">
                    <i class="bi bi-headset me-2"></i>
                    Customer Support 24/7
                </div>

                <h1 class="hero-title">
                    Hubungi
                    <span class="text-gradient">Tim Kami</span>
                </h1>

                <p class="hero-subtitle">
                    Ada pertanyaan tentang UNAS Fest 2025? Tim profesional kami siap membantu Anda dengan respon cepat dan solusi terbaik
                </p>

                <div class="contact-methods" data-aos="fade-up" data-aos-delay="200">
                    <a href="#contact-form" class="contact-method primary">
                        <i class="bi bi-envelope"></i>
                        <span>Kirim Pesan</span>
                    </a>
                    <a href="https://wa.me/6281234567890" class="contact-method whatsapp" target="_blank">
                        <i class="bi bi-whatsapp"></i>
                        <span>WhatsApp</span>
                    </a>
                    <a href="tel:+622178067000" class="contact-method phone">
                        <i class="bi bi-telephone"></i>
                        <span>Telepon</span>
                    </a>
                </div>

                <div class="response-time" data-aos="fade-up" data-aos-delay="300">
                    <div class="response-item">
                        <i class="bi bi-clock text-warning"></i>
                        <span>Respon dalam <strong>24 jam</strong></span>
                    </div>
                    <div class="response-divider"></div>
                    <div class="response-item">
                        <i class="bi bi-shield-check text-success"></i>
                        <span>Support <strong>Profesional</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section">
    <div class="container">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-8" data-aos="fade-right">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-4">
                            <i class="bi bi-envelope text-primary me-2"></i>Kirim Pesan
                        </h2>
                        
                        <form action="{{ route('public.contact.send') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="subject" class="form-label">Subjek</label>
                                    <select class="form-select @error('subject') is-invalid @enderror" 
                                            id="subject" name="subject" required>
                                        <option value="">Pilih Subjek</option>
                                        <option value="Informasi Kompetisi" {{ old('subject') == 'Informasi Kompetisi' ? 'selected' : '' }}>Informasi Kompetisi</option>
                                        <option value="Pendaftaran" {{ old('subject') == 'Pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                                        <option value="Pembayaran" {{ old('subject') == 'Pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                                        <option value="Teknis Website" {{ old('subject') == 'Teknis Website' ? 'selected' : '' }}>Teknis Website</option>
                                        <option value="Kerjasama" {{ old('subject') == 'Kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                                        <option value="Lainnya" {{ old('subject') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="message" class="form-label">Pesan</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="5" 
                                              placeholder="Tuliskan pesan Anda di sini..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="bi bi-send me-2"></i>Kirim Pesan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="col-lg-4" data-aos="fade-left">
                <div class="sticky-top" style="top: 100px;">
                    <!-- Contact Details -->
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">
                                <i class="bi bi-info-circle text-primary me-2"></i>Informasi Kontak
                            </h4>
                            
                            @php $seo = app(\App\Services\SEOService::class); @endphp
                            @php $contact = $seo->getContactInfo(); @endphp
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-envelope text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Email</h6>
                                        <a href="mailto:{{ $contact['email'] }}" class="text-muted text-decoration-none">
                                            {{ $contact['email'] }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-telephone text-success me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Telepon</h6>
                                        <a href="tel:{{ $contact['phone'] }}" class="text-muted text-decoration-none">
                                            {{ $contact['phone'] }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-0">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt text-danger me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Alamat</h6>
                                        <p class="text-muted mb-0">{{ $contact['address'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-lightning text-warning me-2"></i>Aksi Cepat
                            </h5>
                            
                            <div class="d-grid gap-2">
                                <a href="https://wa.me/6281234567890" 
                                   class="btn btn-success" target="_blank">
                                    <i class="bi bi-whatsapp me-2"></i>Chat WhatsApp
                                </a>
                                <a href="{{ route('public.faq') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-question-circle me-2"></i>Lihat FAQ
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Office Hours -->
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-clock text-primary me-2"></i>Jam Operasional
                            </h5>
                            
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Senin - Jumat</span>
                                    <span class="fw-bold">08:00 - 17:00</span>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Sabtu</span>
                                    <span class="fw-bold">08:00 - 12:00</span>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Minggu</span>
                                    <span class="text-danger">Tutup</span>
                                </div>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Respon email dalam 24 jam
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="section bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins">Lokasi Kami</h2>
            <p class="section-subtitle">
                Kunjungi kantor kami di Universitas Nasional, Jakarta Selatan
            </p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-0">
                        <div class="ratio ratio-21x9">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7461!2d106.8456!3d-6.2615!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sUniversitas%20Nasional!5e0!3m2!1sen!2sid!4v1640995200000!5m2!1sen!2sid" 
                                    style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
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
    /* Contact Hero Section */
    .contact-hero {
        min-height: 70vh;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
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
            radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 2px, transparent 2px),
            radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 2px, transparent 2px);
        background-size: 60px 60px;
        animation: patternFloat 15s linear infinite;
    }

    @keyframes patternFloat {
        0% { transform: translate(0, 0); }
        100% { transform: translate(60px, 60px); }
    }

    .floating-elements {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .element {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: elementFloat 8s ease-in-out infinite;
    }

    .element-1 {
        width: 100px;
        height: 100px;
        top: 15%;
        right: 15%;
        animation-delay: 0s;
    }

    .element-2 {
        width: 60px;
        height: 60px;
        top: 70%;
        right: 25%;
        animation-delay: 3s;
    }

    .element-3 {
        width: 80px;
        height: 80px;
        top: 45%;
        left: 15%;
        animation-delay: 6s;
    }

    @keyframes elementFloat {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-25px) scale(1.1); }
    }

    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 20px;
        line-height: 1.1;
    }

    .text-gradient {
        background: linear-gradient(135deg, var(--accent-color), #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 40px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .contact-methods {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    .contact-method {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        padding: 15px 25px;
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        min-width: 140px;
        justify-content: center;
    }

    .contact-method:hover {
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .contact-method.primary:hover {
        background: rgba(37, 99, 235, 0.3);
        border-color: rgba(37, 99, 235, 0.5);
    }

    .contact-method.whatsapp:hover {
        background: rgba(34, 197, 94, 0.3);
        border-color: rgba(34, 197, 94, 0.5);
    }

    .contact-method.phone:hover {
        background: rgba(245, 158, 11, 0.3);
        border-color: rgba(245, 158, 11, 0.5);
    }

    .response-time {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .response-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
    }

    .response-divider {
        width: 1px;
        height: 20px;
        background: rgba(255, 255, 255, 0.3);
    }

    /* Contact Section */
    .section {
        padding: 80px 0;
    }

    /* Form Styles */
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: none;
        border-radius: 12px;
        padding: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
    }

    /* Contact Info Cards */
    .sticky-top {
        z-index: 1020;
    }

    /* Map Section */
    .bg-light {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
    }

    .ratio iframe {
        border-radius: 15px;
    }

    /* Responsive Design */
    @media (max-width: 991px) {
        .sticky-top {
            position: relative !important;
            top: auto !important;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .contact-methods {
            flex-direction: column;
            align-items: center;
        }

        .contact-method {
            width: 200px;
        }

        .response-time {
            flex-direction: column;
            gap: 10px;
        }

        .response-divider {
            width: 40px;
            height: 1px;
        }

        .section {
            padding: 60px 0;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 2rem;
        }

        .contact-method {
            width: 100%;
            max-width: 250px;
        }

        .card-body {
            padding: 20px !important;
        }
    }
</style>
@endpush
