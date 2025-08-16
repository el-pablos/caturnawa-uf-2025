@extends('layouts.error-animated')

@section('title', '403 - Akses Ditolak')

@section('error-title', 'Access Forbidden')
@section('error-code', '403')
@section('error-description', 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.')
@section('error-message', 'Silakan hubungi administrator jika Anda yakin ini adalah kesalahan.')

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
        <a href="{{ route('logout') }}" class="btn-error"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            🚪 Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @else
        <a href="{{ route('login') }}" class="btn-error">
            🔑 Login
        </a>
        <a href="{{ route('public.home') }}" class="btn-error">
            Beranda
        </a>
    @endauth
@endsection
