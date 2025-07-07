@extends('layouts.error')

@section('title', '500 - Kesalahan Server')

@section('content')
<div class="error-header">
    <div class="error-icon">
        <i class="bi bi-exclamation-octagon"></i>
    </div>
    <h1 class="error-code">500</h1>
    <h2 class="error-title">Kesalahan Server</h2>
</div>

<div class="error-body">
    <p class="error-description">
        Maaf, terjadi kesalahan pada server kami. Tim teknis telah diberitahu dan sedang memperbaiki masalah ini. Silakan coba lagi dalam beberapa saat.
    </p>

    <div class="error-actions">
        <a href="javascript:history.back()" class="btn-error-primary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>

        <a href="{{ route('public.home') }}" class="btn-error-secondary">
            <i class="bi bi-house me-2"></i>Beranda
        </a>
    </div>
</div>

<div class="error-footer">
    <small>
        <i class="bi bi-info-circle me-1"></i>
        Jika masalah berlanjut, silakan hubungi administrator di
        <a href="mailto:admin@unasfest.com" class="text-decoration-none">admin@unasfest.com</a>
    </small>
</div>
@endsection

@section('scripts')
<script>
    // Auto refresh after 30 seconds
    setTimeout(function() {
        if (confirm('Halaman akan dimuat ulang. Lanjutkan?')) {
            window.location.reload();
        }
    }, 30000);
</script>
@endsection


