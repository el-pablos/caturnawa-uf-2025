@extends('layouts.peserta')

@section('title', 'Pembayaran Belum Selesai')
@section('page-title', 'Pembayaran Belum Selesai')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Unfinished Payment Message -->
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark text-center">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Pembayaran Belum Selesai
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-clock-fill text-warning" style="font-size: 5rem;"></i>
                    <h2 class="text-warning mt-4 mb-3">Pembayaran Tertunda</h2>
                    <p class="lead">Anda menutup halaman pembayaran sebelum proses selesai.</p>
                    <p class="text-muted">Jangan khawatir, Anda masih bisa melanjutkan pembayaran.</p>
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
                                        <span class="badge bg-warning">Menunggu Pembayaran</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Batas Waktu:</td>
                                    <td>{{ $payment->expired_at ? $payment->expired_at->format('d M Y H:i') : '24 jam' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What Happened -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-question-circle me-2"></i>Apa yang Terjadi?
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-x-circle text-danger fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Pembayaran Dibatalkan</h6>
                                    <p class="text-muted mb-0">Anda menutup halaman pembayaran atau menekan tombol batal</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-clock text-warning fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Transaksi Masih Aktif</h6>
                                    <p class="text-muted mb-0">Order ID Anda masih valid dan bisa dilanjutkan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-arrow-right-circle me-2"></i>Langkah Selanjutnya
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-arrow-clockwise text-white fs-4"></i>
                                </div>
                                <h6>Lanjutkan Pembayaran</h6>
                                <p class="text-muted small">Klik tombol "Lanjutkan Pembayaran" untuk melanjutkan proses</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-credit-card text-white fs-4"></i>
                                </div>
                                <h6>Pilih Metode</h6>
                                <p class="text-muted small">Pilih metode pembayaran yang sesuai dengan kebutuhan Anda</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-check-circle text-white fs-4"></i>
                                </div>
                                <h6>Selesaikan</h6>
                                <p class="text-muted small">Ikuti instruksi pembayaran hingga selesai</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="alert alert-warning mt-4">
                <div class="d-flex">
                    <i class="bi bi-exclamation-triangle me-3 fs-5"></i>
                    <div>
                        <h6 class="alert-heading">Perhatian</h6>
                        <ul class="mb-0">
                            <li>Pembayaran harus diselesaikan dalam {{ $payment->expired_at ? $payment->expired_at->diffForHumans() : '24 jam' }}</li>
                            <li>Jika melewati batas waktu, Anda perlu mendaftar ulang</li>
                            <li>Pastikan koneksi internet stabil saat melakukan pembayaran</li>
                            <li>Jangan menutup halaman pembayaran sebelum proses selesai</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('payment.checkout', $registration) }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-clockwise me-1"></i>Lanjutkan Pembayaran
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
