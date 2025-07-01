@extends('layouts.simple')

@php
    $seoPage = 'contact';
@endphp

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 3rem 0;
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

    .contact-content {
        padding: 4rem 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .contact-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
        height: 100%;
    }

    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .contact-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }

    .contact-card:hover .contact-icon {
        transform: scale(1.1);
    }

    .contact-card h5 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-align: center;
    }

    .contact-card p {
        color: #64748b;
        margin: 0;
        font-weight: 500;
        text-align: center;
    }

    .contact-card a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .contact-card a:hover {
        color: var(--secondary-color);
        text-decoration: underline;
    }

    .map-section {
        padding: 4rem 0;
        background: white;
    }

    .map-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.8);
        height: 400px;
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
        font-size: 1.1rem;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .contact-hero {
            padding: 2rem 0;
        }

        .contact-content {
            padding: 3rem 0;
        }

        .map-section {
            padding: 3rem 0;
        }

        .map-container {
            height: 300px;
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
                <h1 class="display-4 fw-bold mb-3">Hubungi Kami</h1>
                <p class="lead">Tim kami siap membantu menjawab pertanyaan Anda tentang UNAS Fest 2025</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Cards Section -->
<section class="contact-content">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <h5>Email</h5>
                    <p><a href="mailto:info@unasfest.com">info@unasfest.com</a></p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <h5>Telepon</h5>
                    <p><a href="tel:0858-1737-8442">0858-1737-8442</a></p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="contact-card">
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
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h5>Alamat</h5>
                    <p>Universitas Nasional Jakarta<br>Jl. Sawo Manila, Pejaten<br>Jakarta Selatan 12520</p>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="contact-card">
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
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Enhanced hover effects for contact cards
    document.addEventListener('DOMContentLoaded', function() {
        const contactCards = document.querySelectorAll('.contact-card');
        contactCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Map interaction enhancement
        const mapContainer = document.querySelector('.map-container');
        if (mapContainer) {
            mapContainer.addEventListener('click', function() {
                this.style.transform = 'scale(1.01)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            });
        }
    });
</script>
@endpush
