@extends('layouts.error-animated')

@section('title', 'Pendaftaran Ditutup')

@section('error-title', 'Registration Closed')
@section('error-code', 'CLOSED')
@section('error-description', '{{ $message ?? "Pendaftaran sedang ditutup, silahkan tungu periode selanjutnya yaaaa...." }}')
@section('error-message', 'Pantau terus website ini untuk informasi terbaru mengenai pembukaan pendaftaran.')

@section('error-actions')
    <a href="{{ route('public.home') }}" class="btn-error">
        🏠 Kembali ke Beranda
    </a>
    <a href="{{ route('public.competitions') }}" class="btn-error">
        🏆 Lihat Kompetisi
    </a>
@endsection

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
