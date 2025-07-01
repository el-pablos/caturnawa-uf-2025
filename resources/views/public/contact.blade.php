@extends('layouts.simple')

@php
    $seoPage = 'contact';
@endphp

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }

    .contact-hero::before {
        content: '';
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

    .contact-hero .container {
        position: relative;
        z-index: 2;
    }

    .contact-form-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .contact-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        padding: 3rem;
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
    }

    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.75rem;
    }

    .btn-contact {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        border: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.3);
    }

    .btn-contact:hover {
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(37, 99, 235, 0.4);
    }

    .contact-info-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.8);
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .contact-info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .contact-info-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .contact-info-card:hover::before {
        transform: scaleX(1);
    }

    .contact-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 2rem;
        transition: all 0.3s ease;
    }

    .contact-info-card:hover .contact-icon {
        transform: scale(1.1) rotate(10deg);
    }

    .contact-info-card h5 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .contact-info-card p {
        color: #64748b;
        margin: 0;
        font-weight: 500;
    }

    .contact-info-card a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .contact-info-card a:hover {
        color: var(--secondary-color);
        text-decoration: underline;
    }

    .map-section {
        padding: 5rem 0;
        background: white;
    }

    .map-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 3px solid rgba(255, 255, 255, 0.8);
        height: 500px;
        position: relative;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
        filter: grayscale(20%) contrast(1.1);
        transition: filter 0.3s ease;
    }

    .map-container:hover iframe {
        filter: grayscale(0%) contrast(1.2);
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 1rem;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: 2px;
    }

    .section-subtitle {
        font-size: 1.2rem;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .contact-hero {
            padding: 2rem 0;
        }

        .contact-form-section {
            padding: 3rem 0;
        }

        .map-section {
            padding: 3rem 0;
        }

        .contact-card {
            padding: 2rem;
        }

        .map-container {
            height: 350px;
        }

        .section-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="contact-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Hubungi Kami</h1>
                <p class="lead">Punya pertanyaan tentang UNAS Fest 2025? Tim kami siap membantu Anda dengan segala informasi yang dibutuhkan.</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="contact-form-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-card">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary mb-3">Kirim Pesan</h2>
                        <p class="text-muted">Isi formulir di bawah ini dan kami akan merespons secepatnya</p>
                    </div>

                    <form action="{{ route('public.contact.send') }}" method="POST">
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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">Subjek</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required placeholder="Tuliskan pesan Anda di sini..."></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn-contact">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Info Cards -->
        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <h5>Email</h5>
                    <p><a href="mailto:info@unasfest.com">info@unasfest.com</a></p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <h5>Telepon</h5>
                    <p><a href="tel:0858-1737-8442">0858-1737-8442</a></p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <h5>WhatsApp</h5>
                    <p><a href="https://wa.me/6285817378442" target="_blank">Chat dengan Kami</a></p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h5>Alamat</h5>
                    <p>Universitas Nasional Jakarta<br>Jl. Sawo Manila, Pejaten<br>Jakarta Selatan 12520</p>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <h5>Jam Operasional</h5>
                    <p>Senin - Jumat: 08:00 - 17:00 WIB<br>Sabtu: 08:00 - 14:00 WIB<br>Minggu: Tutup</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Lokasi Kami</h2>
            <p class="section-subtitle">Temukan kami di kampus Universitas Nasional Jakarta</p>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="map-container">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.6479849388844!2d106.84437731476919!3d-6.309021595432588!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ec72c799ba61%3A0x5c2f6a1adb5b0c0c!2sUniversitas%20Nasional!5e0!3m2!1sid!2sid!4v1625123456789!5m2!1sid!2sid"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi Universitas Nasional Jakarta">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Location Details -->
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="contact-info-card text-start">
                    <h5 class="text-center mb-4"><i class="bi bi-building me-2"></i>Tentang Lokasi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i>Akses mudah dengan transportasi umur</li>
                        <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i>Parkir luas dan aman</li>
                        <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i>Fasilitas lengkap dan modern</li>
                        <li class="mb-3"><i class="bi bi-check-circle text-success me-2"></i>Lingkungan yang kondusif</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="contact-info-card text-start">
                    <h5 class="text-center mb-4"><i class="bi bi-signpost me-2"></i>Petunjuk Arah</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="bi bi-arrow-right text-primary me-2"></i>Dari Stasiun Pasar Minggu: 10 menit berkendara</li>
                        <li class="mb-3"><i class="bi bi-arrow-right text-primary me-2"></i>Dari Terminal Lebak Bulus: 15 menit berkendara</li>
                        <li class="mb-3"><i class="bi bi-arrow-right text-primary me-2"></i>Akses TransJakarta: Halte Pejaten Barat</li>
                        <li class="mb-3"><i class="bi bi-arrow-right text-primary me-2"></i>Landmark: Dekat Mall Pejaten Village</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Form validation and enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, textarea');

        // Add real-time validation
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Mengirim...';
            submitBtn.disabled = true;

            // Simulate form submission (replace with actual AJAX call)
            setTimeout(() => {
                alert('Pesan berhasil dikirim! Kami akan merespons dalam 1x24 jam.');
                form.reset();
                inputs.forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });

        // Phone number formatting
        const phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = value.substring(1);
            }
            if (value.length > 0) {
                value = '+62 ' + value;
            }
            this.value = value;
        });
    });

    // Map interaction enhancement
    const mapContainer = document.querySelector('.map-container');
    if (mapContainer) {
        mapContainer.addEventListener('click', function() {
            this.style.transform = 'scale(1.02)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
        });
    }

    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
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

    // Add hover effects to contact cards
    const contactCards = document.querySelectorAll('.contact-info-card');
    contactCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
</script>
@endpush
