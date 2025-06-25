@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold text-white mb-4 font-poppins">
                    Terms of Service
                </h1>
                <p class="lead text-white-50 mb-4">
                    Syarat dan ketentuan penggunaan layanan UNAS Fest 2025
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="mb-5" data-aos="fade-up">
                            <h3 class="fw-bold mb-3">1. Penerimaan Syarat</h3>
                            <p class="text-muted">
                                Dengan mengakses dan menggunakan layanan UNAS Fest 2025, Anda menyetujui untuk terikat oleh syarat dan ketentuan ini. Jika Anda tidak setuju dengan syarat ini, mohon untuk tidak menggunakan layanan kami.
                            </p>
                        </div>

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="100">
                            <h3 class="fw-bold mb-3">2. Pendaftaran dan Akun</h3>
                            <p class="text-muted">Untuk berpartisipasi dalam kompetisi, Anda harus:</p>
                            <ul class="text-muted">
                                <li>Memberikan informasi yang akurat dan lengkap</li>
                                <li>Menjaga kerahasiaan akun dan password</li>
                                <li>Bertanggung jawab atas semua aktivitas di akun Anda</li>
                                <li>Segera melaporkan penggunaan akun yang tidak sah</li>
                            </ul>
                        </div>

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
                            <h3 class="fw-bold mb-3">3. Aturan Kompetisi</h3>
                            <p class="text-muted">Peserta kompetisi wajib:</p>
                            <ul class="text-muted">
                                <li>Mengikuti semua aturan dan panduan kompetisi</li>
                                <li>Menyerahkan karya yang original dan tidak melanggar hak cipta</li>
                                <li>Menghormati peserta lain dan panitia</li>
                                <li>Menerima keputusan juri sebagai keputusan final</li>
                                <li>Hadir pada acara yang diwajibkan</li>
                            </ul>
                        </div>

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="300">
                            <h3 class="fw-bold mb-3">4. Hak Kekayaan Intelektual</h3>
                            <p class="text-muted">
                                Peserta mempertahankan hak cipta atas karya mereka, namun memberikan izin kepada UNAS Fest untuk:
                            </p>
                            <ul class="text-muted">
                                <li>Menampilkan karya untuk keperluan penilaian</li>
                                <li>Mempublikasikan karya pemenang untuk promosi</li>
                                <li>Menggunakan karya untuk dokumentasi acara</li>
                            </ul>
                        </div>

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="400">
                            <h3 class="fw-bold mb-3">5. Pembayaran dan Pengembalian Dana</h3>
                            <p class="text-muted">Ketentuan pembayaran:</p>
                            <ul class="text-muted">
                                <li>Biaya pendaftaran harus dibayar sesuai jadwal</li>
                                <li>Pembayaran yang telah dilakukan tidak dapat dikembalikan</li>
                                <li>Pengecualian pengembalian dana hanya dalam kondisi tertentu</li>
                                <li>Semua biaya tambahan menjadi tanggung jawab peserta</li>
                            </ul>
                        </div>

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="500">
                            <h3 class="fw-bold mb-3">6. Diskualifikasi</h3>
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

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="600">
                            <h3 class="fw-bold mb-3">7. Batasan Tanggung Jawab</h3>
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

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="700">
                            <h3 class="fw-bold mb-3">8. Perubahan Syarat</h3>
                            <p class="text-muted">
                                Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diberitahukan melalui website resmi dan email terdaftar.
                            </p>
                        </div>

                        <div class="text-center" data-aos="fade-up" data-aos-delay="800">
                            <p class="text-muted small">
                                Syarat dan ketentuan ini terakhir diperbarui pada {{ date('d F Y') }}
                            </p>
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('public.home') }}" class="btn btn-primary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                                </a>
                                <a href="{{ route('public.privacy') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-shield-check me-2"></i>Privacy Policy
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
