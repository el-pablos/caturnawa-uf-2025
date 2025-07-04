@extends('layouts.app')

@section('title', 'Website Sedang Maintenance')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-tools text-warning" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h1 class="display-4 mb-4">Website Sedang Maintenance</h1>
                    
                    <div class="mb-4">
                        <p class="lead">{{ $message ?? 'Maaf, website sedang dalam masa pemeliharaan. Silakan coba lagi nanti.' }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-sync-alt text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h5>Peningkatan Sistem</h5>
                            <p class="text-muted">Kami sedang melakukan peningkatan sistem untuk memberikan pengalaman yang lebih baik.</p>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-shield-alt text-success" style="font-size: 2rem;"></i>
                            </div>
                            <h5>Keamanan</h5>
                            <p class="text-muted">Proses maintenance juga mencakup peningkatan keamanan website.</p>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-clock text-info" style="font-size: 2rem;"></i>
                            </div>
                            <h5>Segera Kembali</h5>
                            <p class="text-muted">Website akan segera kembali normal. Terima kasih atas kesabarannya.</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-muted">
                            <small>Jika Anda memiliki pertanyaan mendesak, silakan hubungi admin melalui email atau WhatsApp.</small>
                        </p>
                    </div>
                    
                    <div class="mt-4">
                        <button onclick="window.location.reload()" class="btn btn-primary">
                            <i class="fas fa-refresh"></i> Coba Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        border-radius: 25px;
        padding: 10px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
</style>
@endsection
