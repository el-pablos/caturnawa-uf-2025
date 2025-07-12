@extends('layouts.app')

@section('title', 'Pembayaran Tidak Ditemukan')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Pembayaran Tidak Ditemukan
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-search text-warning" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h5 class="text-dark mb-3">{{ $message }}</h5>
                    
                    <div class="alert alert-info">
                        <strong>ID Pembayaran:</strong> {{ $payment_id }}
                    </div>
                    
                    <p class="text-muted mb-4">
                        Data pembayaran yang Anda cari tidak ditemukan dalam sistem. 
                        Hal ini bisa terjadi karena:
                    </p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-clock text-warning me-2"></i>
                                        Kemungkinan Penyebab
                                    </h6>
                                    <ul class="list-unstyled text-start">
                                        <li><i class="bi bi-dot"></i> ID pembayaran salah atau tidak valid</li>
                                        <li><i class="bi bi-dot"></i> Pembayaran sudah dihapus dari sistem</li>
                                        <li><i class="bi bi-dot"></i> Link pembayaran sudah kedaluwarsa</li>
                                        <li><i class="bi bi-dot"></i> Anda tidak memiliki akses ke pembayaran ini</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-lightbulb text-primary me-2"></i>
                                        Langkah Selanjutnya
                                    </h6>
                                    <ul class="list-unstyled text-start">
                                        <li><i class="bi bi-dot"></i> Periksa kembali link pembayaran</li>
                                        <li><i class="bi bi-dot"></i> Cek email konfirmasi pendaftaran</li>
                                        <li><i class="bi bi-dot"></i> Lihat riwayat pendaftaran Anda</li>
                                        <li><i class="bi bi-dot"></i> Hubungi administrator jika perlu</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($available_payments) && $available_payments->count() > 0)
                    <div class="mt-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-credit-card me-2"></i>
                            Pembayaran Anda yang Tersedia
                        </h6>
                        <div class="row">
                            @foreach($available_payments as $availablePayment)
                            <div class="col-md-6 mb-2">
                                <div class="card border-primary">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">Order ID:</small><br>
                                                <strong>{{ $availablePayment->order_id }}</strong>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ $availablePayment->status === 'paid' ? 'success' : ($availablePayment->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($availablePayment->status) }}
                                                </span><br>
                                                <a href="{{ route('payment.finish', $availablePayment->id) }}" class="btn btn-sm btn-primary mt-1">
                                                    <i class="bi bi-eye me-1"></i>Lihat
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('peserta.registrations.index') }}" class="btn btn-primary me-2">
                            <i class="bi bi-list-ul me-1"></i>
                            Lihat Pendaftaran Saya
                        </a>
                        <a href="{{ route('public.competitions') }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-trophy me-1"></i>
                            Daftar Kompetisi
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house me-1"></i>
                            Beranda
                        </a>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted">
                            Jika Anda yakin ini adalah kesalahan sistem, silakan hubungi administrator dengan menyertakan ID pembayaran: <strong>{{ $payment_id }}</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
