@extends('layouts.simple')

@section('title', 'Syarat & Ketentuan - UNAS Fest 2025')

@section('content')
<style>
    .modern-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
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
    
    .dynamic-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
            radial-gradient(circle at 20% 80%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
        animation: float 20s ease-in-out infinite;
    }
    
    .modern-container {
        position: relative;
        z-index: 1;
    }

    .terms-content h3 {
        font-weight: 700;
        color: #667eea;
        margin-bottom: 1rem;
    }

    .terms-content p, .terms-content ul {
        color: #4a5568;
        line-height: 1.8;
    }

    .terms-content ul {
        list-style: none;
        padding-left: 0;
    }

    .terms-content ul li {
        padding-left: 2rem;
        position: relative;
        margin-bottom: 0.75rem;
    }

    .terms-content ul li::before {
        content: '\F26A'; /* Bootstrap Icon: check-circle-fill */
        font-family: 'bootstrap-icons';
        position: absolute;
        left: 0;
        top: 5px;
        color: #667eea;
        font-size: 1rem;
    }
</style>

<div class="dynamic-bg"></div>
<div class="container my-5 modern-container">
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5 mb-5">
                <div class="hero-content text-center">
                    <div class="floating-icon mb-4">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <h1 class="modern-title mb-4">
                        Syarat & Ketentuan
                    </h1>
                    <p class="modern-subtitle mb-5">
                        Aturan dan panduan penggunaan layanan UNAS Fest 2025.
                        <br>Mohon dibaca dengan seksama sebelum berpartisipasi.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-auto mb-3">
                            <a href="{{ route('public.privacy') }}" class="btn modern-btn btn-lg">
                                <i class="bi bi-shield-check me-2"></i>Privacy Policy
                            </a>
                        </div>
                        <div class="col-md-auto mb-3">
                            <a href="{{ route('public.home') }}" class="btn modern-btn-outline btn-lg">
                                <i class="bi bi-house me-2"></i>Beranda
                            </a>
                        </div>
                    </div>
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
                        <i class="bi bi-file-earmark-text me-2"></i>Detail Syarat & Ketentuan
                    </h2>
                </div>
                <div class="card-body p-5 terms-content">
                    <div class="mb-5">
                        <h3>1. Penerimaan Syarat</h3>
                        <p>
                            Dengan mengakses dan menggunakan layanan UNAS Fest 2025, Anda menyetujui untuk terikat oleh syarat dan ketentuan ini. Jika Anda tidak setuju dengan syarat ini, mohon untuk tidak menggunakan layanan kami.
                        </p>
                    </div>

                    <div class="mb-5">
                        <h3>2. Pendaftaran dan Akun</h3>
                        <p>Untuk berpartisipasi dalam kompetisi, Anda harus:</p>
                        <ul>
                            <li>Memberikan informasi yang akurat dan lengkap</li>
                            <li>Menjaga kerahasiaan akun dan password</li>
                            <li>Bertanggung jawab atas semua aktivitas di akun Anda</li>
                            <li>Segera melaporkan penggunaan akun yang tidak sah</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3>3. Aturan Kompetisi</h3>
                        <p>Peserta kompetisi wajib:</p>
                        <ul>
                            <li>Mengikuti semua aturan dan panduan kompetisi</li>
                            <li>Menyerahkan karya yang original dan tidak melanggar hak cipta</li>
                            <li>Menghormati peserta lain dan panitia</li>
                            <li>Menerima keputusan juri sebagai keputusan final</li>
                            <li>Hadir pada acara yang diwajibkan</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3>4. Hak Kekayaan Intelektual</h3>
                        <p>
                            Peserta mempertahankan hak cipta atas karya mereka, namun memberikan izin kepada UNAS Fest untuk:
                        </p>
                        <ul>
                            <li>Menampilkan karya untuk keperluan penilaian</li>
                            <li>Mempublikasikan karya pemenang untuk promosi</li>
                            <li>Menggunakan karya untuk dokumentasi acara</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3>5. Pembayaran dan Pengembalian Dana</h3>
                        <p>Ketentuan pembayaran:</p>
                        <ul>
                            <li>Biaya pendaftaran harus dibayar sesuai jadwal</li>
                            <li>Pembayaran yang telah dilakukan tidak dapat dikembalikan</li>
                            <li>Pengecualian pengembalian dana hanya dalam kondisi tertentu</li>
                            <li>Semua biaya tambahan menjadi tanggung jawab peserta</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3>6. Diskualifikasi</h3>
                        <p>
                            Panitia berhak mendiskualifikasi peserta yang:
                        </p>
                        <ul>
                            <li>Melanggar aturan kompetisi</li>
                            <li>Memberikan informasi palsu</li>
                            <li>Melakukan plagiarisme atau pelanggaran hak cipta</li>
                            <li>Berperilaku tidak pantas atau merugikan</li>
                            <li>Tidak memenuhi persyaratan yang ditetapkan</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3>7. Batasan Tanggung Jawab</h3>
                        <p>
                            UNAS Fest tidak bertanggung jawab atas:
                        </p>
                        <ul>
                            <li>Kerugian yang timbul dari partisipasi dalam kompetisi</li>
                            <li>Masalah teknis atau gangguan sistem</li>
                            <li>Kehilangan atau kerusakan karya yang dikirimkan</li>
                            <li>Perubahan jadwal atau pembatalan acara</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3>8. Perubahan Syarat</h3>
                        <p>
                            Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diberitahukan melalui website resmi dan email terdaftar.
                        </p>
                    </div>

                    <hr class="my-5" style="border-color: rgba(0,0,0,0.1);">

                    <div class="text-center">
                        <p class="text-muted small mb-4">
                            Syarat dan ketentuan ini terakhir diperbarui pada {{ date('d F Y') }}
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-auto mb-2">
                                <a href="{{ route('public.home') }}" class="btn modern-btn w-auto">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                                </a>
                            </div>
                            <div class="col-md-auto mb-2">
                                <a href="{{ route('public.contact') }}" class="btn modern-btn-outline w-auto" style="color: #764ba2; border-color: #764ba2;" onmouseover="this.style.backgroundColor='rgba(118, 75, 162, 0.1)'; this.style.color='#764ba2';" onmouseout="this.style.backgroundColor='transparent';">
                                    <i class="bi bi-envelope me-2"></i>Hubungi Kami
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