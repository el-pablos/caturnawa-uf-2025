@extends('layouts.public')

@php
    $seoPage = 'contact';
@endphp

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold text-white mb-4 font-poppins">
                    Hubungi Kami
                </h1>
                <p class="lead text-white-50 mb-4">
                    Ada pertanyaan tentang UNAS Fest 2025? Tim kami siap membantu Anda 24/7
                </p>
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
    .sticky-top {
        z-index: 1020;
    }
    
    @media (max-width: 991px) {
        .sticky-top {
            position: relative !important;
            top: auto !important;
        }
    }
</style>
@endpush
