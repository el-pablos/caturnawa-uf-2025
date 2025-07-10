@extends('layouts.simple')

@php
    $seoPage = 'about';
@endphp

@section('title', 'Tentang Kami - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5">
                <div class="text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        Tentang <span class="text-warning">UNAS Fest 2025</span>
                    </h1>
                    <p class="lead mb-4">
                        Festival kompetisi nasional terbesar di Indonesia yang menggabungkan inovasi teknologi,
                        kesehatan, dan biodiversitas untuk menciptakan masa depan yang berkelanjutan.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.competitions') }}" class="btn btn-warning btn-lg w-100">
                                <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.contact') }}" class="btn btn-light btn-lg w-100">
                                <i class="bi bi-envelope me-2"></i>Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision & Mission Section -->
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-eye me-2"></i>Visi Kami
                    </h3>
                </div>
                <div class="card-body text-center">
                    <p class="mb-0">
                        Menjadi festival kompetisi nasional terdepan yang menginspirasi inovasi berkelanjutan
                        dalam bidang teknologi, kesehatan, dan biodiversitas untuk masa depan Indonesia yang lebih baik.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow h-100">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-bullseye me-2"></i>Misi Kami
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Memberikan platform kompetisi berkualitas tinggi untuk mahasiswa Indonesia</span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Mendorong inovasi dan kreativitas dalam menyelesaikan masalah nyata</span>
                        </li>
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Membangun jaringan kolaborasi antar universitas di seluruh Indonesia</span>
                        </li>
                        <li class="mb-0 d-flex align-items-start">
                            <i class="bi bi-check-circle text-success me-3 mt-1"></i>
                            <span>Mengembangkan solusi berkelanjutan untuk tantangan masa depan</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>



    <!-- Values Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white text-center">
                    <h2 class="card-title mb-0">Nilai-Nilai Kami</h2>
                    <p class="mb-0">Prinsip-prinsip yang menjadi fondasi dalam menyelenggarakan UNAS Fest 2025</p>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6 text-center">
                            <div class="p-3">
                                <i class="bi bi-lightbulb text-primary" style="font-size: 3rem;"></i>
                                <h4 class="fw-bold text-primary mt-2">Inovasi</h4>
                                <p class="text-muted">
                                    Mendorong kreativitas dan pemikiran out-of-the-box untuk menciptakan solusi inovatif yang berdampak.
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 text-center">
                            <div class="p-3">
                                <i class="bi bi-people text-success" style="font-size: 3rem;"></i>
                                <h4 class="fw-bold text-success mt-2">Kolaborasi</h4>
                                <p class="text-muted">
                                    Membangun kerjasama yang solid antar peserta, panitia, dan stakeholder untuk mencapai tujuan bersama.
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 text-center">
                            <div class="p-3">
                                <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                                <h4 class="fw-bold text-warning mt-2">Kualitas</h4>
                                <p class="text-muted">
                                    Menjaga standar tinggi dalam setiap aspek penyelenggaraan kompetisi dan pelayanan peserta.
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 text-center">
                            <div class="p-3">
                                <i class="bi bi-globe text-info" style="font-size: 3rem;"></i>
                                <h4 class="fw-bold text-info mt-2">Berkelanjutan</h4>
                                <p class="text-muted">
                                    Fokus pada solusi yang memberikan dampak jangka panjang bagi masyarakat dan lingkungan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
