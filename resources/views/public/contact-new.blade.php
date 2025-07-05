@extends('layouts.simple')

@php
    $seoPage = 'contact';
@endphp

@section('title', 'Kontak - UNAS Fest 2025')

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
    }

    .contact-content {
        padding: 4rem 0;
        background: #f8fafc;
    }

    .contact-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        text-align: center;
        border: 1px solid #e2e8f0;
        height: 100%;
        margin-bottom: 2rem;
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
    }

    .contact-card h5 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .contact-card p {
        color: #64748b;
        margin: 0;
        font-weight: 500;
    }

    .contact-card a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }

    .contact-card a:hover {
        color: var(--secondary-color);
    }

    .map-section {
        padding: 4rem 0;
        background: white;
    }

    .map-container {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        height: 400px;
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
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
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
            <div class="col-lg-8 mx-auto">
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
            <div class="col-md-4 mb-3">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <h5>Email</h5>
                    <p><a href="mailto:info@unasfest.com">info@unasfest.com</a></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <h5>Telepon</h5>
                    <p><a href="tel:0858-1737-8442">0858-1737-8442</a></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <h5>WhatsApp</h5>
                    <p><a href="https://wa.me/6285817378442" target="_blank">Chat dengan Kami</a></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <h5>Alamat</h5>
                    <p>Jl. Sawo Manila No.61, RT.14/RW.7<br>Pejaten Barat, Pasar Minggu<br>Jakarta Selatan 12520</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
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
            <p class="section-subtitle">Temukan kami di Universitas Nasional Jakarta</p>
        </div>
        
        <div class="row">
            <div class="col-12">
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
@endsection
