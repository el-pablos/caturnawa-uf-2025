@extends('layouts.error')

@section('title', '419 - Sesi Kedaluwarsa')

@section('content')
<div class="error-header">
    <div class="error-icon">
        <i class="bi bi-clock-history"></i>
    </div>
    <h1 class="error-code">419</h1>
    <h2 class="error-title">Sesi Kedaluwarsa</h2>
</div>

<div class="error-body">
    <p class="error-description">
        Sesi Anda telah kedaluwarsa karena tidak ada aktivitas dalam waktu yang lama. Untuk keamanan, silakan muat ulang halaman dan coba lagi.
    </p>
    
    <div class="error-actions">
        <a href="javascript:window.location.reload()" class="btn-error-primary">
            <i class="bi bi-arrow-clockwise me-2"></i>Muat Ulang Halaman
        </a>
        
        <a href="{{ route('public.home') }}" class="btn-error-secondary">
            <i class="bi bi-house me-2"></i>Beranda
        </a>
    </div>
</div>

<div class="error-footer">
    <small>
        <i class="bi bi-info-circle me-1"></i>
        Ini terjadi untuk melindungi keamanan data Anda. Silakan muat ulang halaman untuk melanjutkan.
    </small>
</div>
@endsection

@section('scripts')
<script>
    // Auto reload after 5 seconds
    setTimeout(function() {
        window.location.reload();
    }, 5000);
</script>
@endsection
