@extends('layouts.error')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="error-header">
    <div class="error-icon">
        <i class="bi bi-compass"></i>
    </div>
    <h1 class="error-code">404</h1>
    <h2 class="error-title">Halaman Tidak Ditemukan</h2>
</div>

<div class="error-body">
    <p class="error-description">
        Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman tersebut telah dipindahkan, dihapus, atau URL yang Anda masukkan salah.
    </p>

    <div class="error-actions">
        @auth
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin'))
                <a href="{{ route('admin.dashboard') }}" class="btn-error-primary">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
                </a>
            @elseif(auth()->user()->hasRole('juri'))
                <a href="{{ route('juri.juri.dashboard') }}" class="btn-error-primary">
                    <i class="bi bi-clipboard-check me-2"></i>Dashboard Juri
                </a>
            @elseif(auth()->user()->hasRole('peserta'))
                <a href="{{ route('peserta.dashboard') }}" class="btn-error-primary">
                    <i class="bi bi-person-circle me-2"></i>Dashboard Peserta
                </a>
            @else
                <a href="{{ route('public.home') }}" class="btn-error-primary">
                    <i class="bi bi-house me-2"></i>Beranda
                </a>
            @endif
        @else
            <a href="{{ route('public.home') }}" class="btn-error-primary">
                <i class="bi bi-house me-2"></i>Beranda
            </a>
        @endauth

        <a href="{{ route('public.competitions') }}" class="btn-error-secondary">
            <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
        </a>
    </div>
</div>

<div class="error-footer">
    <small>
        <i class="bi bi-info-circle me-1"></i>
        Jika Anda yakin ini adalah kesalahan, silakan hubungi administrator atau coba lagi nanti.
    </small>
</div>
@endsection

@section('scripts')
<script>
    // Auto redirect after 10 seconds if user doesn't interact
    let redirectTimer = setTimeout(function() {
        @auth
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin'))
                window.location.href = "{{ route('admin.dashboard') }}";
            @elseif(auth()->user()->hasRole('juri'))
                window.location.href = "{{ route('juri.juri.dashboard') }}";
            @elseif(auth()->user()->hasRole('peserta'))
                window.location.href = "{{ route('peserta.dashboard') }}";
            @else
                window.location.href = "{{ route('public.home') }}";
            @endif
        @else
            window.location.href = "{{ route('public.home') }}";
        @endauth
    }, 10000);

    // Clear timer if user clicks any button
    document.querySelectorAll('.btn-error-primary, .btn-error-secondary').forEach(function(btn) {
        btn.addEventListener('click', function() {
            clearTimeout(redirectTimer);
        });
    });
</script>
@endsection


