@extends('layouts.peserta')

@section('title', 'Invoice Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="card border-success">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-check-circle-fill me-2"></i>Pembayaran Berhasil!
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    <h2 class="text-success mt-4 mb-3">Selamat!</h2>
                    <p class="lead">Pembayaran kamu sukses, silahkan download invoice nya lalu konfirmasi ke admin event yang kamu registrasi via grup yaaa...</p>
                    <p class="text-muted">Pendaftaran Anda untuk kompetisi <strong>{{ $registration->competition->name }}</strong> sedang menunggu konfirmasi admin.</p>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>Detail Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Order ID:</strong></td>
                                    <td>{{ $payment->order_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kompetisi:</strong></td>
                                    <td>{{ $registration->competition->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Peserta:</strong></td>
                                    <td>{{ $registration->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Institusi:</strong></td>
                                    <td>{{ $registration->institution }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Metode Pembayaran:</strong></td>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah:</strong></td>
                                    <td><strong>Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td><span class="badge bg-warning">Menunggu Konfirmasi</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Bayar:</strong></td>
                                    <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Group Link -->
            @if($registration->competition->whatsapp_group_link)
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-whatsapp me-2"></i>Grup WhatsApp Event
                    </h5>
                </div>
                <div class="card-body text-center">
                    <p class="mb-3">Bergabunglah dengan grup WhatsApp untuk mendapatkan update terbaru tentang kompetisi dan konfirmasi pembayaran:</p>
                    <a href="{{ $registration->competition->whatsapp_group_link }}" 
                       class="btn btn-success btn-lg" 
                       target="_blank">
                        <i class="bi bi-whatsapp me-2"></i>Gabung Grup WhatsApp
                    </a>
                    <p class="text-muted mt-3 small">
                        <i class="bi bi-info-circle me-1"></i>
                        Silakan konfirmasi pembayaran Anda di grup WhatsApp dengan mengirimkan screenshot invoice ini.
                    </p>
                </div>
            </div>
            @endif

            <!-- Next Steps -->
            <div class="card mt-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-check me-2"></i>Langkah Selanjutnya
                    </h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">Download invoice pembayaran dengan klik tombol di bawah</li>
                        <li class="mb-2">Bergabung dengan grup WhatsApp kompetisi</li>
                        <li class="mb-2">Kirimkan screenshot invoice ke grup untuk konfirmasi</li>
                        <li class="mb-2">Tunggu konfirmasi dari admin (biasanya 1x24 jam)</li>
                        <li>Setelah dikonfirmasi, Anda dapat mulai mengupload karya</li>
                    </ol>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('download.unified-invoice', $registration) }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-download me-2"></i>Download Invoice
                        </a>
                        @if($registration->competition->whatsapp_group_link)
                        <a href="{{ $registration->competition->whatsapp_group_link }}" 
                           class="btn btn-success btn-lg" 
                           target="_blank">
                            <i class="bi bi-whatsapp me-2"></i>Grup WhatsApp
                        </a>
                        @endif
                        <a href="{{ route('peserta.registrations.show', $registration) }}" class="btn btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Pendaftaran
                        </a>
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
    border-radius: 0.5rem;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.table td {
    padding: 0.5rem 0;
    border: none;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto download PDF invoice after 3 seconds
    setTimeout(() => {
        // Create hidden link and trigger download
        const downloadUrl = '{{ route("download.unified-invoice", $registration) }}';
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = 'invoice-{{ $payment->order_id }}.pdf';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Show notification
        if (typeof showNotification === 'function') {
            showNotification('Invoice berhasil diunduh!', 'success');
        }
    }, 3000);
});
</script>
@endpush
