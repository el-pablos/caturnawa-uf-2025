@extends('layouts.error-animated')

@section('title', '500 - Kesalahan Server')

@section('error-title', 'Internal Server Error')
@section('error-code', '500')
@section('error-description', 'Maaf, terjadi kesalahan pada server kami. Tim teknis telah diberitahu dan sedang memperbaiki masalah ini.')
@section('error-message', 'Silakan coba lagi dalam beberapa saat.')

@section('error-actions')
    <a href="javascript:history.back()" class="btn-error">
        ← Kembali
    </a>
    <a href="{{ route('public.home') }}" class="btn-error">
        Beranda
    </a>
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


