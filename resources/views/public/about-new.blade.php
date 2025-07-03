@extends('layouts.simple')

@php
    $seoPage = 'about';
@endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">Tentang UNAS Fest 2025</h1>
                <p class="lead text-muted">Kompetisi teknologi terbesar yang menggabungkan inovasi, kreativitas, dan kolaborasi</p>
            </div>
            
            <div class="row mb-5">
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="bi bi-eye-fill text-primary fs-1"></i>
                            </div>
                            <h3 class="text-center mb-4">Visi Kami</h3>
                            <p class="text-muted">
                                Menjadi platform kompetisi teknologi terdepan yang menginspirasi generasi muda Indonesia 
                                untuk menciptakan inovasi digital yang berkelanjutan dan berdampak positif bagi kemajuan bangsa.
                            </p>
                            <div class="mt-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Inovasi Berkelanjutan</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Dampak Positif</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>Kemajuan Bangsa</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="bi bi-target text-primary fs-1"></i>
                            </div>
                            <h3 class="text-center mb-4">Misi Kami</h3>
                            <div class="mission-list">
                                <div class="d-flex mb-4">
                                    <div class="badge bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">1</div>
                                    <div>
                                        <h6 class="mb-1">Mengembangkan Talenta Digital</h6>
                                        <small class="text-muted">Memberikan wadah bagi mahasiswa untuk mengasah kemampuan teknologi dan inovasi</small>
                                    </div>
                                </div>
                                <div class="d-flex mb-4">
                                    <div class="badge bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">2</div>
                                    <div>
                                        <h6 class="mb-1">Membangun Ekosistem Kolaboratif</h6>
                                        <small class="text-muted">Menciptakan jaringan kolaborasi antara akademisi, industri, dan pemerintah</small>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="badge bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">3</div>
                                    <div>
                                        <h6 class="mb-1">Mendorong Inovasi Berkelanjutan</h6>
                                        <small class="text-muted">Menghasilkan solusi teknologi yang dapat diimplementasikan untuk kemajuan masyarakat</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mb-5">
                <h2 class="fw-bold text-primary mb-3">Nilai-Nilai Kami</h2>
                <p class="text-muted">Prinsip-prinsip yang menjadi fondasi dalam setiap kegiatan UNAS Fest 2025</p>
            </div>
            
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <i class="bi bi-lightbulb-fill text-warning fs-1 mb-3"></i>
                            <h5>Inovasi</h5>
                            <p class="text-muted small">Mendorong pemikiran kreatif dan solusi out-of-the-box untuk tantangan teknologi masa depan</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <i class="bi bi-people-fill text-info fs-1 mb-3"></i>
                            <h5>Kolaborasi</h5>
                            <p class="text-muted small">Membangun sinergi antar peserta, mentor, dan stakeholder untuk mencapai tujuan bersama</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <i class="bi bi-award-fill text-success fs-1 mb-3"></i>
                            <h5>Keunggulan</h5>
                            <p class="text-muted small">Berkomitmen untuk memberikan pengalaman kompetisi berkualitas tinggi dan standar internasional</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <i class="bi bi-shield-check-fill text-primary fs-1 mb-3"></i>
                            <h5>Integritas</h5>
                            <p class="text-muted small">Menjunjung tinggi kejujuran, transparansi, dan fair play dalam setiap aspek kompetisi</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <i class="bi bi-globe text-danger fs-1 mb-3"></i>
                            <h5>Dampak Sosial</h5>
                            <p class="text-muted small">Mengutamakan solusi yang memberikan manfaat nyata bagi masyarakat dan lingkungan</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <i class="bi bi-graph-up text-success fs-1 mb-3"></i>
                            <h5>Pertumbuhan</h5>
                            <p class="text-muted small">Memberikan kesempatan belajar dan berkembang bagi semua peserta dan stakeholder</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
