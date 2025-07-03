@extends('layouts.simple')

@section('title', 'Privacy Policy - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5">
                <div class="text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-shield-check me-3"></i>Privacy Policy
                    </h1>
                    <p class="lead mb-4">
                        Kebijakan privasi UNAS Fest 2025 mengenai pengumpulan, penggunaan, dan perlindungan data pribadi Anda
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.contact') }}" class="btn btn-warning btn-lg w-100">
                                <i class="bi bi-envelope me-2"></i>Hubungi Kami
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

    <!-- Privacy Policy Content -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h2 class="card-title mb-0">
                        <i class="bi bi-file-text me-2"></i>Kebijakan Privasi
                    </h2>
                    <p class="mb-0">Komitmen kami dalam melindungi data dan privasi Anda</p>
                </div>
                <div class="card-body p-5">
                    
                    <!-- Section 1 -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 text-primary">
                            <i class="bi bi-info-circle me-2"></i>1. Informasi yang Kami Kumpulkan
                        </h3>
                        <p class="text-muted mb-3">
                            Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami, seperti:
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Nama lengkap dan informasi kontak</li>
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Informasi institusi/universitas</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Data pendaftaran kompetisi</li>
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Dokumen dan file yang diunggah</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 text-success">
                            <i class="bi bi-gear me-2"></i>2. Bagaimana Kami Menggunakan Informasi
                        </h3>
                        <p class="text-muted mb-3">
                            Informasi yang kami kumpulkan digunakan untuk:
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Memproses pendaftaran kompetisi</li>
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Berkomunikasi dengan peserta</li>
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Menyelenggarakan acara dan kompetisi</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Memberikan dukungan teknis</li>
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Meningkatkan layanan kami</li>
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>Mengirim update dan informasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 text-warning">
                            <i class="bi bi-shield-lock me-2"></i>3. Perlindungan Data
                        </h3>
                        <p class="text-muted mb-3">
                            Kami berkomitmen untuk melindungi informasi pribadi Anda dengan:
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-warning mb-3">
                                    <div class="card-body text-center">
                                        <i class="bi bi-lock-fill text-warning" style="font-size: 2rem;"></i>
                                        <h5 class="mt-2">Enkripsi Data</h5>
                                        <p class="text-muted small">Data sensitif dienkripsi dengan standar industri</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success mb-3">
                                    <div class="card-body text-center">
                                        <i class="bi bi-eye-slash-fill text-success" style="font-size: 2rem;"></i>
                                        <h5 class="mt-2">Akses Terbatas</h5>
                                        <p class="text-muted small">Hanya staff terotorisasi yang dapat mengakses data</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 text-danger">
                            <i class="bi bi-share me-2"></i>4. Berbagi Informasi
                        </h3>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Penting:</strong> Kami tidak akan menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda.
                        </div>
                        <p class="text-muted mb-3">Pengecualian hanya berlaku untuk:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-dot text-danger"></i>Keperluan penyelenggaraan kompetisi</li>
                            <li class="mb-2"><i class="bi bi-dot text-danger"></i>Jika diwajibkan oleh hukum</li>
                            <li class="mb-2"><i class="bi bi-dot text-danger"></i>Untuk melindungi hak dan keamanan</li>
                        </ul>
                    </div>

                    <!-- Section 5 -->
                    <div class="mb-5">
                        <h3 class="fw-bold mb-3 text-info">
                            <i class="bi bi-person-check me-2"></i>5. Hak Anda
                        </h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-eye text-info me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Akses Data</h6>
                                        <p class="text-muted small mb-0">Mengakses informasi pribadi Anda</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-pencil text-info me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Perbaiki Data</h6>
                                        <p class="text-muted small mb-0">Memperbarui atau mengoreksi data</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-trash text-info me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Hapus Data</h6>
                                        <p class="text-muted small mb-0">Menghapus akun dan data Anda</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-x-circle text-info me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Tarik Persetujuan</h6>
                                        <p class="text-muted small mb-0">Menarik persetujuan penggunaan data</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="text-center">
                        <h3 class="fw-bold mb-3 text-secondary">
                            <i class="bi bi-telephone me-2"></i>Hubungi Kami
                        </h3>
                        <p class="text-muted mb-4">
                            Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami:
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <i class="bi bi-envelope text-primary" style="font-size: 1.5rem;"></i>
                                                <p class="small mb-1"><strong>Email</strong></p>
                                                <p class="small mb-0">privacy@unasfest.com</p>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <i class="bi bi-telephone text-success" style="font-size: 1.5rem;"></i>
                                                <p class="small mb-1"><strong>Telepon</strong></p>
                                                <p class="small mb-0">+62 21 7806700</p>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <i class="bi bi-geo-alt text-danger" style="font-size: 1.5rem;"></i>
                                                <p class="small mb-1"><strong>Alamat</strong></p>
                                                <p class="small mb-0">UNAS Jakarta Selatan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar me-1"></i>
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
</div>
@endsection