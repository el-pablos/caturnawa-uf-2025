@extends('layouts.simple')

@php
    $seoPage = 'contact';
@endphp

@section('title', 'Hubungi Kami - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5" data-aos="fade-up">
                <div class="text-center">
                    <h1 class="display-4 fw-bold mb-3" data-aos="zoom-in" data-aos-delay="200">
                        <i class="bi bi-headset me-3"></i>Hubungi Kami
                    </h1>
                    <p class="lead mb-4" data-aos="fade-up" data-aos-delay="400">
                        Ada pertanyaan tentang UNAS Fest 2025? Tim profesional kami siap membantu Anda dengan respon cepat dan solusi terbaik
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="600">
                            <a href="https://wa.me/6285817378442" class="btn btn-success btn-lg w-100" target="_blank">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                        <div class="col-md-3 mb-3" data-aos="fade-up" data-aos-delay="800">
                            <a href="#contact-form" class="btn btn-light btn-lg w-100">
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
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4" data-aos="fade-up">
                <i class="bi bi-chat-dots text-success"></i>
                Hubungi Tim Kami
            </h2>
        </div>
    </div>

    <div class="row g-4">
        <!-- Contact Form -->
        <div class="col-lg-8" data-aos="fade-right">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-envelope me-2"></i>Kirim Pesan
                    </h3>
                </div>
                <div class="card-body p-4" id="contact-form">
                        
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
            <!-- Contact Details -->
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informasi Kontak
                    </h4>
                </div>
                <div class="card-body">
                    @php $seo = app(\App\Services\SEOService::class); @endphp
                    @php $contact = $seo->getContactInfo(); @endphp

                    <div class="mb-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-envelope text-primary me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Email</h6>
                                <a href="mailto:{{ $contact['email'] }}" class="text-decoration-none">
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
                                <a href="tel:{{ $contact['phone'] }}" class="text-decoration-none">
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
            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/6285817378442"
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
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock me-2"></i>Jam Operasional
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Senin - Jumat</span>
                            <span class="fw-bold">08:00 - 17:00</span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Sabtu</span>
                            <span class="fw-bold">08:00 - 12:00</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Minggu</span>
                            <span class="text-danger">Tutup</span>
                        </div>
                    </div>

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
@endsection
