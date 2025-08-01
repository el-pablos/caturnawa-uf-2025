@extends('layouts.error-animated')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('error-title', 'Page Not Found')
@section('error-code', '404')
@section('error-description', 'Maaf, halaman yang Anda cari tidak dapat ditemukan.')
@section('error-message', 'Mungkin halaman tersebut telah dipindahkan, dihapus, atau URL yang Anda masukkan salah.')

@section('error-actions')
    @auth
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin'))
            <a href="{{ route('admin.dashboard') }}" class="btn-error">
                📊 Dashboard Admin
            </a>
        @elseif(auth()->user()->hasRole('juri'))
            <a href="{{ route('juri.juri.dashboard') }}" class="btn-error">
                📋 Dashboard Juri
            </a>
        @elseif(auth()->user()->hasRole('peserta'))
            <a href="{{ route('peserta.dashboard') }}" class="btn-error">
                👤 Dashboard Peserta
            </a>
        @else
            <a href="{{ route('public.home') }}" class="btn-error">
                Beranda
            </a>
        @endif
    @else
        <a href="{{ route('public.home') }}" class="btn-error">
            Beranda
        </a>
    @endauth
    <a href="{{ route('public.competitions') }}" class="btn-error">
        🏆 Lihat Kompetisi
    </a>
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


