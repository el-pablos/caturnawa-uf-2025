@extends('layouts.simple')

@php
    $seoPage = 'contact';
@endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">Hubungi Kami</h1>
                <p class="lead text-muted">Punya pertanyaan tentang UNAS Fest 2025? Kami siap membantu Anda!</p>
            </div>
            
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek</label>
                            <input type="text" class="form-control" id="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-envelope-fill text-primary fs-1 mb-3"></i>
                            <h5>Email</h5>
                            <p class="text-muted">info@unasfest2025.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-telephone-fill text-primary fs-1 mb-3"></i>
                            <h5>Telepon</h5>
                            <p class="text-muted">+62 21 7806700</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-geo-alt-fill text-primary fs-1 mb-3"></i>
                            <h5>Alamat</h5>
                            <p class="text-muted">Universitas Nasional<br>Jakarta Selatan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
