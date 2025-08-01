@extends('layouts.error-animated')

@section('title', '419 - Sesi Kedaluwarsa')

@section('error-title', 'Session Expired')
@section('error-code', '419')
@section('error-description', 'Sesi Anda telah kedaluwarsa karena tidak ada aktivitas dalam waktu yang lama.')
@section('error-message', 'Untuk keamanan, silakan muat ulang halaman dan coba lagi.')

@section('error-actions')
    <a href="javascript:window.location.reload()" class="btn-error">
        🔄 Muat Ulang Halaman
    </a>
    <a href="{{ route('public.home') }}" class="btn-error">
        Beranda
    </a>
@endsection

@section('scripts')
<script>
    // Auto reload after 5 seconds
    setTimeout(function() {
        window.location.reload();
    }, 5000);
</script>
@endsection
