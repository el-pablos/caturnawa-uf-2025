@extends('layouts.simple')

@php
    $seoPage = 'contact';
@endphp

@section('title', 'Hubungi Kami - UNAS Fest 2025')

@push('styles')
<style>
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

    .modern-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        color: white;
    }

    .modern-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="50" cy="10" r="0.8" fill="rgba(255,255,255,0.12)"/><circle cx="10" cy="60" r="1.2" fill="rgba(255,255,255,0.06)"/><circle cx="90" cy="30" r="0.9" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
        animation: float 20s ease-in-out infinite;
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

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
        colot: green;

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
</style>
@endpush

@section('content')
<div class="dynamic-bg"></div>
<div class="container my-5">
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero p-5 rounded mb-5">
                <div class="hero-content text-center">
                    <h1 class="modern-title mb-3">
                        <i class="bi bi-headset me-3 floating-icon"></i>Hubungi Kami
                    </h1>
                    <p class="modern-subtitle mb-4">
                        Ada pertanyaan tentang UNAS Fest 2025? Tim kami siap membantu Anda.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-lg-3 mb-3">
                            <a href="https://wa.me/6285817378442" class="btn modern-btn btn-lg w-100 text-success" target="_blank">
                                <i class="bi bi-whatsapp me-2 text-success"></i>WhatsApp
                            </a>
                        </div>
                        <div class="col-md-4 col-lg-3 mb-3">
                            <a href="#contact-form" class="btn modern-btn-outline btn-lg w-100">
                                <i class="bi bi-envelope me-2"></i>Kirim Pesan
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
    <div class="row g-4">
        <!-- Contact Form -->
        <div class="col-lg-8" data-aos="fade-right">
            <div class="modern-card">
                <div class="card-header p-3 bg-primary text-white">
                    <h3 class="card-title mb-0"><i class="bi bi-envelope-fill me-2 w-auto"></i>Kirim Pesan Anda</h3>
                </div>
                <div class="card-body p-4" id="contact-form">
                    <form action="{{ route('public.contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label">Subjek</label>
                                <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                    <option value="">Pilih Subjek</option>
                                    <option value="Informasi Kompetisi" {{ old('subject') == 'Informasi Kompetisi' ? 'selected' : '' }}>Informasi Kompetisi</option>
                                    <option value="Pendaftaran" {{ old('subject') == 'Pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                                    <option value="Pembayaran" {{ old('subject') == 'Pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                                    <option value="Teknis Website" {{ old('subject') == 'Teknis Website' ? 'selected' : '' }}>Teknis Website</option>
                                    <option value="Kerjasama" {{ old('subject') == 'Kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                                    <option value="Lainnya" {{ old('subject') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Tuliskan pesan Anda di sini..." required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
            @php $seo = app(\App\Services\SEOService::class); @endphp
            @php $contact = $seo->getContactInfo(); @endphp
            
            <div class="modern-card info-card mb-4">
                <div class="card-header p-3 bg-info text-white">
                    <h4 class="card-title mb-0"><i class="bi bi-info-circle-fill me-2"></i>Informasi Kontak</h4>
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
                            <h6 class="fw-bold mb-0">Telepon</h6>
                            <a href="tel:{{ $contact['phone'] }}" class="text-decoration-none">{{ $contact['phone'] }}</a>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill text-danger info-icon me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Alamat</h6>
                            <p class="text-muted mb-0">{{ $contact['address'] }}</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="modern-card mb-4">
                <div class="card-header p-3 bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="bi bi-clock-fill me-2"></i>Jam Operasional</h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between mb-2"><span>Senin - Jumat</span><span class="fw-bold">08:00 - 17:00</span></div>
                    <div class="d-flex justify-content-between mb-2"><span>Sabtu</span><span class="fw-bold">08:00 - 12:00</span></div>
                    <div class="d-flex justify-content-between"><span>Minggu</span><span class="text-danger fw-bold">Tutup</span></div>
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
