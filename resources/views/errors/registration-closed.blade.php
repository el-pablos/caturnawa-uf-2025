@extends('layouts.app')

@section('title', 'Pendaftaran Ditutup')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-door-closed text-danger" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h1 class="display-4 mb-4">Pendaftaran Ditutup</h1>
                    
                    <div class="mb-4">
                        <p class="lead">{{ $message ?? 'Pendaftaran sedang ditutup, silahkan tungu periode selanjutnya yaaaa....' }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-calendar-alt text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h5>Periode Berikutnya</h5>
                            <p class="text-muted">Tunggu pengumuman periode pendaftaran berikutnya dari panitia.</p>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-bell text-warning" style="font-size: 2rem;"></i>
                            </div>
                            <h5>Notifikasi</h5>
                            <p class="text-muted">Ikuti media sosial kami untuk mendapatkan notifikasi pembukaan pendaftaran.</p>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-info-circle text-info" style="font-size: 2rem;"></i>
                            </div>
                            <h5>Informasi</h5>
                            <p class="text-muted">Hubungi panitia jika Anda memiliki pertanyaan lebih lanjut.</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-muted">
                            <small>Pantau terus website ini untuk informasi terbaru mengenai pembukaan pendaftaran.</small>
                        </p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary me-2">
                            <i class="fas fa-home"></i> Kembali ke Beranda
                        </a>
                        <a href="{{ route('public.competitions') }}" class="btn btn-outline-primary">
                            <i class="fas fa-trophy"></i> Lihat Kompetisi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 20px 0;
    }
    
    .card {
        border: none;
        border-radius: 15px;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }
    
    .feature-icon {
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .display-4 {
        font-weight: 700;
        color: #2c3e50;
    }
    
    .lead {
        font-size: 1.2rem;
        color: #6c757d;
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #ff6b6b, #ee5a24);
        border: none;
        border-radius: 25px;
        padding: 10px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    }
    
    .btn-outline-primary {
        border: 2px solid #ff6b6b;
        color: #ff6b6b;
        border-radius: 25px;
        padding: 10px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background: #ff6b6b;
        color: white;
        transform: translateY(-2px);
    }
</style>
@endsection
