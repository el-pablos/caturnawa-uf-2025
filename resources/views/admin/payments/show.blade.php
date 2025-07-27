@extends('layouts.admin')

@section('title', 'Detail Pembayaran')
@section('page-title', 'Detail Pembayaran')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        @if($payment->isSuccess())
        <a href="{{ route('download.unified-invoice', $payment->registration) }}" class="btn btn-primary">
            <i class="bi bi-receipt me-2"></i>Download Invoice
        </a>
        @endif
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Payment Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-credit-card me-2"></i>Informasi Pembayaran
                </h5>
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
                                <td class="fw-semibold">Status:</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status_class }}">
                                        {{ $payment->status_label }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Jumlah:</td>
                                <td class="fw-bold text-success">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Metode Pembayaran:</td>
                                <td>{{ $payment->payment_method }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Tanggal Dibuat:</td>
                                <td>{{ $payment->created_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            @if($payment->paid_at)
                            <tr>
                                <td class="fw-semibold">Tanggal Dibayar:</td>
                                <td>{{ $payment->paid_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            @endif
                            @if($payment->expired_at)
                            <tr>
                                <td class="fw-semibold">Kedaluwarsa:</td>
                                <td>{{ $payment->expired_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            @endif
                            @if($payment->transaction_id)
                            <tr>
                                <td class="fw-semibold">Transaction ID:</td>
                                <td><code>{{ $payment->transaction_id }}</code></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-check me-2"></i>Detail Pendaftaran
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Peserta</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="fw-semibold">Nama:</td>
                                <td>{{ $payment->registration->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Email:</td>
                                <td>{{ $payment->registration->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Telepon:</td>
                                <td>{{ $payment->registration->phone ?: $payment->registration->user->phone }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Institusi:</td>
                                <td>{{ $payment->registration->institution ?: '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Kompetisi</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="fw-semibold">Kompetisi:</td>
                                <td>{{ $payment->registration->competition->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Kategori:</td>
                                <td>{{ $payment->registration->competition->category }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">No. Registrasi:</td>
                                <td><code>{{ $payment->registration->registration_number }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status Registrasi:</td>
                                <td>
                                    <span class="badge bg-{{ $payment->registration->status === 'confirmed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payment->registration->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Admin Guide -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>Panduan Admin
                </h6>
            </div>
            <div class="card-body">
                @if($payment->isPending())
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Status: Menunggu Pembayaran</strong>
                    </div>
                    <div class="small text-muted">
                        <h6 class="fw-semibold mb-2">Yang harus dilakukan:</h6>
                        <ul class="mb-0 ps-3">
                            <li>Tunggu peserta melakukan pembayaran</li>
                            <li>Sistem akan otomatis memproses pembayaran</li>
                            <li>Tidak perlu tindakan manual dari admin</li>
                        </ul>
                    </div>
                @elseif($payment->isSuccess())
                    <div class="alert alert-success mb-3">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Pembayaran Berhasil</strong>
                    </div>
                    <div class="small text-muted">
                        <h6 class="fw-semibold mb-2">Status saat ini:</h6>
                        <ul class="mb-3 ps-3">
                            <li>✅ Pembayaran telah dikonfirmasi otomatis</li>
                            <li>✅ Registrasi telah diaktifkan</li>
                        </ul>
                        <h6 class="fw-semibold mb-2">Yang bisa dilakukan:</h6>
                        <ul class="mb-0 ps-3">
                            <li>Lihat detail registrasi peserta</li>
                            <li>Download struk pembayaran</li>
                            <li>Pantau status submission peserta</li>
                        </ul>
                    </div>
                @elseif($payment->status === 'failed')
                    <div class="alert alert-danger mb-3">
                        <i class="bi bi-x-circle me-2"></i>
                        <strong>Pembayaran Gagal</strong>
                    </div>
                    <div class="small text-muted">
                        <h6 class="fw-semibold mb-2">Yang harus dilakukan:</h6>
                        <ul class="mb-0 ps-3">
                            <li>Hubungi peserta untuk melakukan pembayaran ulang</li>
                            <li>Berikan panduan pembayaran yang benar</li>
                            <li>Pastikan peserta menggunakan metode pembayaran yang valid</li>
                        </ul>
                    </div>
                @else
                    <div class="alert alert-warning mb-3">
                        <i class="bi bi-clock me-2"></i>
                        <strong>Status: {{ $payment->status_label }}</strong>
                    </div>
                    <div class="small text-muted">
                        <h6 class="fw-semibold mb-2">Yang harus dilakukan:</h6>
                        <ul class="mb-0 ps-3">
                            <li>Pantau status pembayaran secara berkala</li>
                            <li>Sistem akan otomatis memproses jika pembayaran berhasil</li>
                            <li>Hubungi peserta jika ada kendala</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Timeline -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Timeline Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Pembayaran Dibuat</h6>
                            <small class="text-muted">{{ $payment->created_at->format('d M Y H:i:s') }}</small>
                        </div>
                    </div>
                    
                    @if($payment->paid_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Pembayaran Berhasil</h6>
                            <small class="text-muted">{{ $payment->paid_at->format('d M Y H:i:s') }}</small>
                        </div>
                    </div>
                    @endif
                    
                    @if($payment->registration->confirmed_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Registrasi Dikonfirmasi</h6>
                            <small class="text-muted">{{ $payment->registration->confirmed_at->format('d M Y H:i:s') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}
</style>
@endpush

@push('scripts')
<script>
function verifyPayment(paymentId) {
    if (confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?')) {
        fetch(`/admin/payments/${paymentId}/verify`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memverifikasi pembayaran');
        });
    }
}

function rejectPayment(paymentId) {
    const reason = prompt('Masukkan alasan penolakan:');
    if (reason) {
        fetch(`/admin/payments/${paymentId}/reject`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menolak pembayaran');
        });
    }
}
</script>
@endpush
