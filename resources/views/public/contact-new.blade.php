@extends('layouts.simple')

@php
    $seoPage = 'contact';
@endphp

@section('title', 'Kontak - UNAS Fest 2025')

@push('styles')
<style>
    /* Inherited styles from leaderboard for consistency */
    .modern-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 25px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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
        color: rgba(255,255,255,0.9);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        line-height: 1.6;
    }

    .floating-icon {
        font-size: 4rem;
        animation: floatIcon 3s ease-in-out infinite;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
    }

    @keyframes floatIcon {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
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

    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
    }

    .modern-container {
        position: relative;
        z-index: 1;
    }

    /* New styles for contact page */
    .contact-section {
        padding: 4rem 0;
    }

    .modern-contact-card {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 2rem;
        text-align: center;
        height: 100%;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .modern-contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.15);
    }

    .modern-contact-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #ff6b6b, #feca57);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 1.8rem;
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    }

    .modern-contact-card h5 {
        color: #343a40;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .modern-contact-card p, .modern-contact-card a {
        color: #495057;
        margin: 0;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .modern-contact-card a:hover {
        color: #764ba2;
    }

    .map-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
    }

    .map-container {
        height: 450px;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-title {
        font-size: 2.8rem;
        font-weight: 700;
        color: #343a40;
        margin-bottom: 1rem;
    }

    .section-subtitle {
        font-size: 1.2rem;
        color: #6c757d;
        max-width: 600px;
        margin: 0 auto;
    }
</style>
@endpush

@section('content')
<div class="dynamic-bg"></div>
<div class="modern-container container my-5">
    <div class="floating-shapes"></div>
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5 mb-5"
                 data-aos="zoom-in"
                 data-aos-duration="1000">
                <div class="hero-content text-center">
                    <div class="floating-icon mb-4"
                        data-aos="bounce-in"
                        data-aos-delay="200">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h1 class="modern-title mb-4"
                        data-aos="fade-down"
                        data-aos-delay="400">
                        Hubungi Kami
                    </h1>
                    <p class="modern-subtitle mb-0" data-aos="fade-up" data-aos-delay="600">
                        Tim kami siap membantu menjawab pertanyaan Anda tentang UNAS Fest 2025.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Cards Section -->
    <section class="contact-section">
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="modern-contact-card">
                    <div class="modern-contact-icon"><i class="bi bi-envelope-fill"></i></div>
                    <h5>Email</h5>
                    <p><a href="mailto:info@unasfest.com">info@unasfest.com</a></p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="modern-contact-card">
                    <div class="modern-contact-icon"><i class="bi bi-telephone-fill"></i></div>
                    <h5>Telepon</h5>
                    <p><a href="tel:0858-1737-8442">0858-1737-8442</a></p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="modern-contact-card">
                    <div class="modern-contact-icon"><i class="bi bi-whatsapp"></i></div>
                    <h5>WhatsApp</h5>
                    <p><a href="https://wa.me/6285817378442" target="_blank">Chat dengan Kami</a></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="modern-contact-card">
                    <div class="modern-contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <h5>Alamat</h5>
                    <p>Jl. Sawo Manila No.61, RT.14/RW.7<br>Pejaten Barat, Pasar Minggu<br>Jakarta Selatan 12520</p>
                </div>
            </div>
            <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                <div class="modern-contact-card">
                    <div class="modern-contact-icon"><i class="bi bi-clock-fill"></i></div>
                    <h5>Jam Operasional</h5>
                    <p>Senin - Jumat: 08:00 - 17:00 WIB<br>Sabtu: 08:00 - 14:00 WIB<br>Minggu: Tutup</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section" data-aos="fade-up" data-aos-duration="1000">
        <div class="section-header">
            <h2 class="section-title">Lokasi Kami</h2>
            <p class="section-subtitle">Temukan kami di Universitas Nasional Jakarta</p>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="map-card">
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7037296897583!2d106.83746531476911!3d-6.301582695377932!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ecbc1b26f799%3A0x5b99b2d1c95b8c7d!2sJl.%20Sawo%20Manila%20No.61%2C%20RT.14%2FRW.7%2C%20Pejaten%20Bar.%2C%20Ps.%20Minggu%2C%20Kota%20Jakarta%20Selatan%2C%20Daerah%20Khusus%20Ibukota%20Jakarta%2012520!5e0!3m2!1sid!2sid!4v1625123456789!5m2!1sid!2sid"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi Universitas Nasional Jakarta - Jl. Sawo Manila No.61">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50,
        });
    });
</script>
@endpush
