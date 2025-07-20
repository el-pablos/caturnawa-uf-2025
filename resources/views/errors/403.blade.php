@extends('layouts.error')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="error-header">
    <div class="error-icon">
        <i class="bi bi-shield-exclamation"></i>
    </div>
    <h1 class="error-code">403</h1>
    <h2 class="error-title">Akses Ditolak</h2>
</div>

<div class="error-body">
    <p class="error-description">
        Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda yakin ini adalah kesalahan.
    </p>

    <div class="error-actions">
        @auth
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin'))
                <a href="{{ route('admin.admin.dashboard') }}" class="btn-error-primary">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
                </a>
            @elseif(auth()->user()->hasRole('juri'))
                <a href="{{ route('juri.juri.dashboard') }}" class="btn-error-primary">
                    <i class="bi bi-clipboard-check me-2"></i>Dashboard Juri
                </a>
            @elseif(auth()->user()->hasRole('peserta'))
                <a href="{{ route('peserta.peserta.dashboard') }}" class="btn-error-primary">
                    <i class="bi bi-person-circle me-2"></i>Dashboard Peserta
                </a>
            @else
                <a href="{{ route('public.home') }}" class="btn-error-primary">
                    <i class="bi bi-house me-2"></i>Beranda
                </a>
            @endif

            <a href="{{ route('logout') }}" class="btn-error-secondary"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-error-primary">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </a>

            <a href="{{ route('public.home') }}" class="btn-error-secondary">
                <i class="bi bi-house me-2"></i>Beranda
            </a>
        @endauth
    </div>
</div>

<div class="error-footer">
    <small>
        <i class="bi bi-info-circle me-1"></i>
        Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator sistem.
    </small>
</div>
@endsection
