@extends('layouts.peserta')

@section('title', 'Status Pembayaran')
@section('page-title', 'Status Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Payment Status Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>Status Pembayaran
                    </h5>
                </div>
                <div class="card-body text-center py-5">
                    @if($payment->isSuccess())
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h3 class="text-success mt-3">Pembayaran Berhasil!</h3>
                        <p class="text-muted">Terima kasih, pembayaran Anda telah berhasil diproses.</p>
                    @elseif($payment->isPending())
                        <i class="bi bi-clock-fill text-warning" style="font-size: 4rem;"></i>
                        <h3 class="text-warning mt-3">Menunggu Pembayaran</h3>
                        <p class="text-muted">Silakan selesaikan pembayaran sesuai instruksi yang diberikan.</p>
                    @elseif($payment->isFailed())
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                        <h3 class="text-danger mt-3">Pembayaran Gagal</h3>
                        <p class="text-muted">Pembayaran tidak dapat diproses. Silakan coba lagi.</p>
                    @else
                        <i class="bi bi-question-circle-fill text-secondary" style="font-size: 4rem;"></i>
                        <h3 class="text-secondary mt-3">Status Tidak Diketahui</h3>
                        <p class="text-muted">Status pembayaran sedang diverifikasi.</p>
                    @endif
                </div>
            </div>

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
                                <tr>
                                    <td class="fw-semibold">Jumlah:</td>
                                    <td class="fw-bold">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold">Status:</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status_class }}">
                                            {{ $payment->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Metode:</td>
                                    <td>{{ $payment->payment_method ?: '-' }}</td>
                                </tr>
                                @if($payment->va_number)
                                <tr>
                                    <td class="fw-semibold">VA Number:</td>
                                    <td class="font-monospace">{{ $payment->va_number }}</td>
                                </tr>
                                @endif
                                @if($payment->payment_code)
                                <tr>
                                    <td class="fw-semibold">Kode Bayar:</td>
                                    <td class="font-monospace">{{ $payment->payment_code }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($payment->isPending())
                                <button class="btn btn-primary" onclick="checkPaymentStatus()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Cek Status
                                </button>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            @if($payment->isSuccess())
                                <a href="{{ route('peserta.registrations.show', $registration) }}" class="btn btn-success">
                                    <i class="bi bi-eye me-1"></i>Lihat Pendaftaran
                                </a>
                            @else
                                <a href="{{ route('payment.checkout', $registration) }}" class="btn btn-warning">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Pembayaran
                                </a>
                            @endif
                            <a href="{{ route('peserta.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house me-1"></i>Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            @if($payment->isPending())
            <div class="alert alert-info mt-4">
                <div class="d-flex">
                    <i class="bi bi-info-circle me-3 fs-5"></i>
                    <div>
                        <h6 class="alert-heading">Instruksi Pembayaran</h6>
                        <p class="mb-0">{{ $payment->payment_instruction }}</p>
                        @if($payment->pdf_url)
                            <a href="{{ $payment->pdf_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-download me-1"></i>Unduh Instruksi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

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
            location.reload();
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

// Auto refresh untuk pending payments
@if($payment->isPending())
setTimeout(() => {
    location.reload();
}, 30000); // Refresh setiap 30 detik
@endif
</script>
@endpush
