@extends('layouts.simple')

@section('title', 'Syarat & Ketentuan - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5">
                <div class="text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-file-text me-3"></i>Syarat & Ketentuan
                    </h1>
                    <p class="lead mb-4">
                        Syarat dan ketentuan penggunaan layanan UNAS Fest 2025. Mohon dibaca dengan seksama sebelum berpartisipasi
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.privacy') }}" class="btn btn-warning btn-lg w-100">
                                <i class="bi bi-shield-check me-2"></i>Privacy Policy
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.home') }}" class="btn btn-light btn-lg w-100">
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
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h2 class="card-title mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Syarat & Ketentuan Layanan
                    </h2>
                    <p class="mb-0">Aturan dan ketentuan yang berlaku untuk semua pengguna UNAS Fest 2025</p>
                </div>
                <div class="card-body p-5">
                    <div class="mb-4">
                        <h3 class="fw-bold mb-3 text-primary">1. Penerimaan Syarat</h3>
                        <p class="text-muted">
                            Dengan mengakses dan menggunakan layanan UNAS Fest 2025, Anda menyetujui untuk terikat oleh syarat dan ketentuan ini. Jika Anda tidak setuju dengan syarat ini, mohon untuk tidak menggunakan layanan kami.
                        </p>
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold mb-3 text-primary">2. Pendaftaran dan Akun</h3>
                        <p class="text-muted">Untuk berpartisipasi dalam kompetisi, Anda harus:</p>
                        <ul class="text-muted">
                            <li>Memberikan informasi yang akurat dan lengkap</li>
                            <li>Menjaga kerahasiaan akun dan password</li>
                            <li>Bertanggung jawab atas semua aktivitas di akun Anda</li>
                            <li>Segera melaporkan penggunaan akun yang tidak sah</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold mb-3 text-primary">3. Aturan Kompetisi</h3>
                        <p class="text-muted">Peserta kompetisi wajib:</p>
                        <ul class="text-muted">
                            <li>Mengikuti semua aturan dan panduan kompetisi</li>
                            <li>Menyerahkan karya yang original dan tidak melanggar hak cipta</li>
                            <li>Menghormati peserta lain dan panitia</li>
                            <li>Menerima keputusan juri sebagai keputusan final</li>
                            <li>Hadir pada acara yang diwajibkan</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold mb-3 text-primary">4. Hak Kekayaan Intelektual</h3>
                        <p class="text-muted">
                            Peserta mempertahankan hak cipta atas karya mereka, namun memberikan izin kepada UNAS Fest untuk:
                        </p>
                        <ul class="text-muted">
                            <li>Menampilkan karya untuk keperluan penilaian</li>
                            <li>Mempublikasikan karya pemenang untuk promosi</li>
                            <li>Menggunakan karya untuk dokumentasi acara</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold mb-3 text-primary">5. Pembayaran dan Pengembalian Dana</h3>
                        <p class="text-muted">Ketentuan pembayaran:</p>
                        <ul class="text-muted">
                            <li>Biaya pendaftaran harus dibayar sesuai jadwal</li>
                            <li>Pembayaran yang telah dilakukan tidak dapat dikembalikan</li>
                            <li>Pengecualian pengembalian dana hanya dalam kondisi tertentu</li>
                            <li>Semua biaya tambahan menjadi tanggung jawab peserta</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold mb-3 text-primary">6. Diskualifikasi</h3>
                        <p class="text-muted">
                            Panitia berhak mendiskualifikasi peserta yang:
                        </p>
                        <ul class="text-muted">
                            <li>Melanggar aturan kompetisi</li>
                            <li>Memberikan informasi palsu</li>
                            <li>Melakukan plagiarisme atau pelanggaran hak cipta</li>
                            <li>Berperilaku tidak pantas atau merugikan</li>
                            <li>Tidak memenuhi persyaratan yang ditetapkan</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold mb-3 text-primary">7. Batasan Tanggung Jawab</h3>
                        <p class="text-muted">
                            UNAS Fest tidak bertanggung jawab atas:
                        </p>
                        <ul class="text-muted">
                            <li>Kerugian yang timbul dari partisipasi dalam kompetisi</li>
                            <li>Masalah teknis atau gangguan sistem</li>
                            <li>Kehilangan atau kerusakan karya yang dikirimkan</li>
                            <li>Perubahan jadwal atau pembatalan acara</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3 class="fw-bold mb-3 text-primary">8. Perubahan Syarat</h3>
                        <p class="text-muted">
                            Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diberitahukan melalui website resmi dan email terdaftar.
                        </p>
                    </div>

                    <div class="text-center">
                        <p class="text-muted small mb-3">
                            Syarat dan ketentuan ini terakhir diperbarui pada {{ date('d F Y') }}
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('public.home') }}" class="btn btn-primary w-100">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('public.contact') }}" class="btn btn-outline-primary w-100">
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
