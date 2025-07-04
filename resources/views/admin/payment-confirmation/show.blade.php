@extends('layouts.admin')

@section('title', 'Detail Konfirmasi Pembayaran')

@section('breadcrumb')
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0">Detail Konfirmasi Pembayaran</h1>
        <nav class="ms-auto">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.payment-confirmation.index') }}">Konfirmasi Pembayaran</a></li>
                <li class="breadcrumb-item active">Detail #{{ $payment->id }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Payment Information -->
    <div class="col-lg-8">
        <div class="card">
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
                                <td class="fw-semibold">ID Pembayaran:</td>
                                <td>#{{ $payment->id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Order ID:</td>
                                <td>{{ $payment->order_id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Transaction ID:</td>
                                <td>{{ $payment->transaction_id ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status:</td>
                                <td>
                                    @if($payment->status === 'success')
                                        <span class="badge bg-success">Berhasil</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($payment->status === 'failed')
                                        <span class="badge bg-danger">Gagal</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Konfirmasi:</td>
                                <td>
                                    @if($payment->is_confirmed)
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                    @else
                                        <span class="badge bg-warning">Belum Dikonfirmasi</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Jumlah:</td>
                                <td class="fw-bold text-success">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Metode Pembayaran:</td>
                                <td>{{ $payment->payment_type ? ucfirst(str_replace('_', ' ', $payment->payment_type)) : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tanggal Pembayaran:</td>
                                <td>{{ $payment->created_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            @if($payment->settlement_time)
                            <tr>
                                <td class="fw-semibold">Waktu Settlement:</td>
                                <td>{{ $payment->settlement_time->format('d M Y H:i:s') }}</td>
                            </tr>
                            @endif
                            @if($payment->confirmed_at)
                            <tr>
                                <td class="fw-semibold">Dikonfirmasi:</td>
                                <td>{{ $payment->confirmed_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>Informasi Registrasi
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">ID Registrasi:</td>
                                <td>#{{ $payment->registration->id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Nama Peserta:</td>
                                <td>{{ $payment->registration->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Email:</td>
                                <td>{{ $payment->registration->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Kompetisi:</td>
                                <td>{{ $payment->registration->competition->name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Kategori:</td>
                                <td>{{ $payment->registration->competition->category }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status Registrasi:</td>
                                <td>
                                    @if($payment->registration->status === 'confirmed')
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                    @elseif($payment->registration->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payment->registration->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @if($payment->registration->team_name)
                            <tr>
                                <td class="fw-semibold">Nama Tim:</td>
                                <td>{{ $payment->registration->team_name }}</td>
                            </tr>
                            @endif
                            @if($payment->registration->institution)
                            <tr>
                                <td class="fw-semibold">Institusi:</td>
                                <td>{{ $payment->registration->institution }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if($payment->confirmation_notes || $payment->rejection_reason)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-chat-text me-2"></i>Catatan
                </h5>
            </div>
            <div class="card-body">
                @if($payment->confirmation_notes)
                    <div class="alert alert-success">
                        <strong>Catatan Konfirmasi:</strong><br>
                        {{ $payment->confirmation_notes }}
                    </div>
                @endif
                @if($payment->rejection_reason)
                    <div class="alert alert-danger">
                        <strong>Alasan Penolakan:</strong><br>
                        {{ $payment->rejection_reason }}
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Actions Sidebar -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>Aksi
                </h5>
            </div>
            <div class="card-body">
                @if($payment->status === 'success' && !$payment->is_confirmed)
                    <button type="button" class="btn btn-success w-100 mb-2" onclick="confirmPayment({{ $payment->id }})">
                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Pembayaran
                    </button>
                    <button type="button" class="btn btn-danger w-100 mb-2" onclick="rejectPayment({{ $payment->id }})">
                        <i class="bi bi-x-circle me-2"></i>Tolak Pembayaran
                    </button>
                @elseif($payment->is_confirmed)
                    <div class="alert alert-success text-center">
                        <i class="bi bi-check-circle-fill fs-1"></i>
                        <p class="mt-2 mb-0">Pembayaran telah dikonfirmasi</p>
                    </div>
                @endif
                
                <a href="{{ route('admin.payment-confirmation.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Detail Teknis
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="fw-semibold">Status Code:</td>
                        <td>{{ $payment->status_code ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Transaction Status:</td>
                        <td>{{ $payment->transaction_status ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Fraud Status:</td>
                        <td>{{ $payment->fraud_status ?: '-' }}</td>
                    </tr>
                    @if($payment->bank)
                    <tr>
                        <td class="fw-semibold">Bank:</td>
                        <td>{{ strtoupper($payment->bank) }}</td>
                    </tr>
                    @endif
                    @if($payment->va_number)
                    <tr>
                        <td class="fw-semibold">VA Number:</td>
                        <td>{{ $payment->va_number }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="confirmForm">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?</p>
                    <div class="mb-3">
                        <label for="confirmation_notes" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="confirmation_notes" name="confirmation_notes" rows="3" placeholder="Tambahkan catatan konfirmasi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menolak pembayaran ini?</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmPayment(paymentId) {
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
    
    document.getElementById('confirmForm').onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(`/admin/payment-confirmation/${paymentId}/confirm`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                modal.hide();
                location.reload();
            } else {
                showError(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            showError('Terjadi kesalahan sistem');
        });
    };
}

function rejectPayment(paymentId) {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
    
    document.getElementById('rejectForm').onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(`/admin/payment-confirmation/${paymentId}/reject`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                modal.hide();
                location.reload();
            } else {
                showError(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            showError('Terjadi kesalahan sistem');
        });
    };
}
</script>
@endpush
