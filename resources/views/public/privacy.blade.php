@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold text-white mb-4 font-poppins">
                    Privacy Policy
                </h1>
                <p class="lead text-white-50 mb-4">
                    Kebijakan privasi UNAS Fest 2025 mengenai pengumpulan, penggunaan, dan perlindungan data pribadi
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Privacy Policy Content -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="mb-5" data-aos="fade-up">
                            <h3 class="fw-bold mb-3">1. Informasi yang Kami Kumpulkan</h3>
                            <p class="text-muted">
                                Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami, seperti:
                            </p>
                            <ul class="text-muted">
                                <li>Nama lengkap dan informasi kontak</li>
                                <li>Informasi institusi/universitas</li>
                                <li>Data pendaftaran kompetisi</li>
                                <li>Dokumen dan file yang diunggah</li>
                            </ul>
                        </div>

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="100">
                            <h3 class="fw-bold mb-3">2. Bagaimana Kami Menggunakan Informasi</h3>
                            <p class="text-muted">
                                Informasi yang kami kumpulkan digunakan untuk:
                            </p>
                            <ul class="text-muted">
                                <li>Memproses pendaftaran kompetisi</li>
                                <li>Berkomunikasi dengan peserta</li>
                                <li>Menyelenggarakan acara dan kompetisi</li>
                                <li>Memberikan dukungan teknis</li>
                                <li>Meningkatkan layanan kami</li>
                            </ul>
                        </div>

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
                            <h3 class="fw-bold mb-3">3. Perlindungan Data</h3>
                            <p class="text-muted">
                                Kami berkomitmen untuk melindungi informasi pribadi Anda dengan:
                            </p>
                            <ul class="text-muted">
                                <li>Enkripsi data sensitif</li>
                                <li>Akses terbatas pada informasi pribadi</li>
                                <li>Sistem keamanan berlapis</li>
                                <li>Pemantauan keamanan 24/7</li>
                            </ul>
                        </div>

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="300">
                            <h3 class="fw-bold mb-3">4. Berbagi Informasi</h3>
                            <p class="text-muted">
                                Kami tidak akan menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali:
                            </p>
                            <ul class="text-muted">
                                <li>Untuk keperluan penyelenggaraan kompetisi</li>
                                <li>Jika diwajibkan oleh hukum</li>
                                <li>Untuk melindungi hak dan keamanan</li>
                            </ul>
                        </div>

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="400">
                            <h3 class="fw-bold mb-3">5. Hak Anda</h3>
                            <p class="text-muted">
                                Anda memiliki hak untuk:
                            </p>
                            <ul class="text-muted">
                                <li>Mengakses informasi pribadi Anda</li>
                                <li>Memperbarui atau mengoreksi data</li>
                                <li>Menghapus akun dan data</li>
                                <li>Menarik persetujuan penggunaan data</li>
                            </ul>
                        </div>

                        <div class="mb-5" data-aos="fade-up" data-aos-delay="500">
                            <h3 class="fw-bold mb-3">6. Kontak</h3>
                            <p class="text-muted">
                                Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami di:
                            </p>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1"><strong>Email:</strong> privacy@unasfest.com</p>
                                <p class="mb-1"><strong>Telepon:</strong> +62 21 7806700</p>
                                <p class="mb-0"><strong>Alamat:</strong> Universitas Nasional, Jakarta Selatan</p>
                            </div>
                        </div>

                        <div class="text-center" data-aos="fade-up" data-aos-delay="600">
                            <p class="text-muted small">
                                Kebijakan privasi ini terakhir diperbarui pada {{ date('d F Y') }}
                            </p>
                            <a href="{{ route('public.home') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
