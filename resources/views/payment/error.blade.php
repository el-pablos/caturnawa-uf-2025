@extends('layouts.peserta')

@section('title', 'Pembayaran Gagal')
@section('page-title', 'Pembayaran Gagal')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Error Message -->
            <div class="card border-danger">
                <div class="card-header bg-danger text-white text-center">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-x-circle-fill me-2"></i>Pembayaran Gagal
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 5rem;"></i>
                    <h2 class="text-danger mt-4 mb-3">Oops! Terjadi Kesalahan</h2>
                    <p class="lead">Pembayaran Anda tidak dapat diproses.</p>
                    <p class="text-muted">Silakan coba lagi atau gunakan metode pembayaran lain.</p>
                </div>
            </div>

            <!-- Error Details -->
            @if($payment->status_message)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Detail Error
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <strong>Pesan Error:</strong> {{ $payment->status_message }}
                    </div>
                    @if($payment->status_code)
                        <p><strong>Kode Error:</strong> {{ $payment->status_code }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Payment Details -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>Detail Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold">Order ID:</td>
                                    <td>{{ $payment->order_id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Kompetisi:</td>
                                    <td>{{ $registration->competition->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Peserta:</td>
                                    <td>{{ $registration->display_name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold">Jumlah:</td>
                                    <td class="fw-bold">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Status:</td>
                                    <td>
                                        <span class="badge bg-danger">{{ $payment->status_label }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Waktu:</td>
                                    <td>{{ now()->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Common Causes -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-question-circle me-2"></i>Kemungkinan Penyebab
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-credit-card text-warning fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Masalah Kartu</h6>
                                    <p class="text-muted mb-0">Saldo tidak mencukupi, kartu expired, atau limit terlampaui</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-wifi text-danger fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Koneksi Internet</h6>
                                    <p class="text-muted mb-0">Koneksi tidak stabil atau terputus saat proses pembayaran</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-bank text-info fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Masalah Bank</h6>
                                    <p class="text-muted mb-0">Sistem bank sedang maintenance atau mengalami gangguan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-shield-x text-danger fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Keamanan</h6>
                                    <p class="text-muted mb-0">Transaksi ditolak oleh sistem keamanan bank</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Solutions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-lightbulb me-2"></i>Solusi yang Bisa Dicoba
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-arrow-clockwise text-white fs-4"></i>
                                </div>
                                <h6>Coba Lagi</h6>
                                <p class="text-muted small">Ulangi proses pembayaran dengan data yang sama</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-credit-card text-white fs-4"></i>
                                </div>
                                <h6>Ganti Metode</h6>
                                <p class="text-muted small">Gunakan metode pembayaran yang berbeda</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-headset text-white fs-4"></i>
                                </div>
                                <h6>Hubungi CS</h6>
                                <p class="text-muted small">Kontak customer service untuk bantuan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Information -->
            <div class="alert alert-info mt-4">
                <div class="d-flex">
                    <i class="bi bi-info-circle me-3 fs-5"></i>
                    <div>
                        <h6 class="alert-heading">Butuh Bantuan?</h6>
                        <p class="mb-2">Jika masalah terus berlanjut, silakan hubungi customer service kami:</p>
                        <ul class="mb-0">
                            <li><strong>Email:</strong> support@unasfest.com</li>
                            <li><strong>WhatsApp:</strong> +62 812-3456-7890</li>
                            <li><strong>Jam Operasional:</strong> 08:00 - 17:00 WIB</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('payment.checkout', $registration) }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                        </a>
                        <button class="btn btn-outline-info" onclick="checkPaymentStatus()">
                            <i class="bi bi-search me-1"></i>Cek Status
                        </button>
                        <a href="{{ route('peserta.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-1"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>
@endpush

@push('scripts')
<script>
function checkPaymentStatus() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengecek...';
    
    fetch(`{{ route('payment.check-status') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            order_id: '{{ $payment->order_id }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.data.transaction_status === 'settlement' || data.data.transaction_status === 'capture') {
                window.location.href = '{{ route("payment.finish", $payment) }}';
            } else {
                window.location.href = '{{ route("payment.status", $payment) }}';
            }
        } else {
            alert('Gagal mengecek status: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengecek status');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}
</script>
@endpush
