@extends('layouts.error-animated')

@section('title', '400 - Bad Request')

@section('error-title', 'Bad Request')
@section('error-code', '400')
@section('error-description', 'Permintaan yang Anda kirim tidak dapat dipahami oleh server.')
@section('error-message', 'Silakan periksa kembali data yang Anda masukkan dan coba lagi.')

@section('error-actions')
    <a href="javascript:history.back()" class="btn-error">
        ← Kembali
    </a>
    <a href="{{ route('public.home') }}" class="btn-error">
        Beranda
    </a>
@endsection