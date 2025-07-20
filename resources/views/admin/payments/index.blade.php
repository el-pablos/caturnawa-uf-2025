@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')
@section('page-title', 'Kelola Pembayaran')

@section('sidebar-menu')
    <a class="nav-link" href="{{ route('admin.admin.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a class="nav-link" href="{{ route('admin.competitions.index') }}">
        <i class="bi bi-trophy me-2"></i>Kompetisi
    </a>
    <a class="nav-link" href="{{ route('admin.registrations.index') }}">
        <i class="bi bi-person-check me-2"></i>Registrasi
    </a>
    <a class="nav-link active" href="{{ route('admin.payments.index') }}">
        <i class="bi bi-credit-card me-2"></i>Pembayaran
    </a>
    <a class="nav-link" href="{{ route('admin.users.index') }}">
        <i class="bi bi-people me-2"></i>Pengguna
    </a>
    <a class="nav-link" href="{{ route('admin.reports.index') }}">
        <i class="bi bi-graph-up me-2"></i>Laporan
    </a>
    <a class="nav-link" href="{{ route('admin.settings.index') }}">
        <i class="bi bi-gear me-2"></i>Pengaturan
    </a>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-credit-card fs-1 mb-2"></i>
                <div class="stats-number">{{ number_format($stats['total']) }}</div>
                <div class="small">Total Pembayaran</div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-warning">
            <div class="card-body text-center">
                <i class="bi bi-clock fs-1 mb-2 text-warning"></i>
                <div class="stats-number text-warning">{{ number_format($stats['pending']) }}</div>
                <div class="small">Menunggu</div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="bi bi-check-circle fs-1 mb-2 text-success"></i>
                <div class="stats-number text-success">{{ number_format($stats['paid']) }}</div>
                <div class="small">Berhasil</div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-danger">
            <div class="card-body text-center">
                <i class="bi bi-x-circle fs-1 mb-2 text-danger"></i>
                <div class="stats-number text-danger">{{ number_format($stats['failed']) }}</div>
                <div class="small">Gagal</div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-secondary">
            <div class="card-body text-center">
                <i class="bi bi-slash-circle fs-1 mb-2 text-secondary"></i>
                <div class="stats-number text-secondary">{{ number_format($stats['cancelled']) }}</div>
                <div class="small">Dibatalkan</div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-info">
            <div class="card-body text-center">
                <i class="bi bi-cash-stack fs-1 mb-2 text-info"></i>
                <div class="stats-number text-info">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
                <div class="small">Total Pendapatan</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Berhasil</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Metode Pembayaran</label>
                <select name="payment_method" class="form-select">
                    <option value="">Semua Metode</option>
                    <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="credit_card" {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                    <option value="e_wallet" {{ request('payment_method') === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Order ID, Transaction ID, atau nama..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Payments Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Daftar Pembayaran</h5>
        <div class="btn-group" id="bulkActions" style="display: none;">
            <button class="btn btn-success btn-sm" onclick="bulkVerify()">
                <i class="bi bi-check-circle me-1"></i>Verifikasi Terpilih
            </button>
            <button class="btn btn-primary btn-sm" onclick="bulkConfirm()">
                <i class="bi bi-check2-circle me-1"></i>Konfirmasi Terpilih
            </button>
            <button class="btn btn-danger btn-sm" onclick="bulkReject()">
                <i class="bi bi-x-circle me-1"></i>Tolak Terpilih
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Order ID</th>
                            <th>Peserta</th>
                            <th>Kompetisi</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input payment-checkbox" value="{{ $payment->id }}">
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $payment->order_id }}</strong>
                                        @if($payment->transaction_id)
                                            <br>
                                            <small class="text-muted">{{ $payment->transaction_id }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $payment->registration->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payment->registration->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $payment->registration->competition->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payment->registration->competition->category }}</small>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-success">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($payment->payment_method === 'bank_transfer')
                                        <span class="badge bg-primary">Transfer Bank</span>
                                    @elseif($payment->payment_method === 'credit_card')
                                        <span class="badge bg-info">Kartu Kredit</span>
                                    @elseif($payment->payment_method === 'e_wallet')
                                        <span class="badge bg-warning">E-Wallet</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payment->payment_method) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $payment->status_class }}">
                                        {{ $payment->status_label }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($payment->isAwaitingConfirmation())
                                            <button class="btn btn-outline-success btn-sm" onclick="confirmPayment({{ $payment->id }})" title="Konfirmasi Pembayaran">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="rejectPayment({{ $payment->id }})" title="Tolak Pembayaran">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @elseif($payment->isConfirmed())
                                            <span class="badge bg-success">Terkonfirmasi</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $payments->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-credit-card fs-1 text-muted"></i>
                <p class="text-muted mt-2">Tidak ada pembayaran ditemukan</p>
            </div>
        @endif
    </div>
</div>

<!-- Reject Payment Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-x-circle me-2"></i>Tolak Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>
                    <h5 class="mt-3">Konfirmasi Penolakan Pembayaran</h5>
                    <p class="text-muted">Tindakan ini akan menolak pembayaran dan tidak dapat dibatalkan.</p>
                </div>

                <div class="alert alert-warning">
                    <h6 class="alert-heading">
                        <i class="bi bi-info-circle me-2"></i>Informasi Penting
                    </h6>
                    <ul class="mb-0">
                        <li>Pembayaran yang ditolak tidak dapat diproses ulang</li>
                        <li>Peserta akan menerima notifikasi penolakan</li>
                        <li>Alasan penolakan akan dicatat dalam sistem</li>
                    </ul>
                </div>

                <form id="rejectForm">
                    <div class="mb-3">
                        <label class="form-label">
                            <strong>Alasan Penolakan <span class="text-danger">*</span></strong>
                        </label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required
                                  placeholder="Jelaskan alasan penolakan pembayaran..."></textarea>
                        <div class="form-text">Berikan alasan yang jelas dan spesifik untuk penolakan ini.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <strong>Tindakan Selanjutnya</strong>
                        </label>
                        <select class="form-select" name="action">
                            <option value="notify_only">Hanya kirim notifikasi</option>
                            <option value="allow_retry">Izinkan pembayaran ulang</option>
                            <option value="cancel_registration">Batalkan registrasi</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" onclick="submitReject()">
                    <i class="bi bi-check-lg me-2"></i>Konfirmasi Penolakan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Refund Payment Modal -->
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Refund Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="refundForm">
                    <div class="mb-3">
                        <label class="form-label">Jumlah Refund <span class="text-danger">*</span></label>
                        <input type="number" name="refund_amount" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan Refund <span class="text-danger">*</span></label>
                        <textarea name="refund_reason" class="form-control" rows="3" required 
                                  placeholder="Masukkan alasan refund..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" onclick="submitRefund()">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Proses Refund
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPaymentId = null;

function confirmPayment(paymentId) {
    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        text: 'Apakah Anda yakin ingin mengkonfirmasi pembayaran ini? QR code akan dibuat untuk peserta.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Konfirmasi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang mengkonfirmasi pembayaran dan membuat QR code',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/payments/${paymentId}/confirm`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mengkonfirmasi pembayaran',
                    icon: 'error'
                });
            });
        }
    });
}

function verifyPayment(paymentId) {
    confirmAction(
        'Verifikasi Pembayaran',
        'Apakah Anda yakin ingin memverifikasi pembayaran ini?',
        function() {
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
                    showSuccess('Pembayaran berhasil diverifikasi');
                    location.reload();
                } else {
                    showError(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                showError('Terjadi kesalahan sistem');
            });
        }
    );
}

function rejectPayment(paymentId) {
    currentPaymentId = paymentId;
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        const modal = new bootstrap.Modal(rejectModal);
        modal.show();
    }
}

function submitReject() {
    const form = document.getElementById('rejectForm');
    if (!form) return;

    const formData = new FormData(form);
    
    fetch(`/admin/payments/${currentPaymentId}/reject`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Pembayaran berhasil ditolak');
            location.reload();
        } else {
            showError(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
    
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        const modal = bootstrap.Modal.getInstance(rejectModal);
        if (modal) modal.hide();
    }
}

function refundPayment(paymentId) {
    currentPaymentId = paymentId;
    const refundModal = document.getElementById('refundModal');
    if (refundModal) {
        const modal = new bootstrap.Modal(refundModal);
        modal.show();
    }
}

function submitRefund() {
    const form = document.getElementById('refundForm');
    if (!form) return;

    const formData = new FormData(form);
    
    fetch(`/admin/payments/${currentPaymentId}/refund`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Refund berhasil diproses');
            location.reload();
        } else {
            showError(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
    
    const refundModal = document.getElementById('refundModal');
    if (refundModal) {
        const modal = bootstrap.Modal.getInstance(refundModal);
        if (modal) modal.hide();
    }
}

// Mass action functionality
const selectAllElement = document.getElementById('selectAll');
if (selectAllElement) {
    selectAllElement.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.payment-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });
}

const paymentCheckboxes = document.querySelectorAll('.payment-checkbox');
if (paymentCheckboxes.length > 0) {
    paymentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });
}

function toggleBulkActions() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');

    if (bulkActions) {
        if (checkedBoxes.length > 0) {
            bulkActions.style.display = 'block';
        } else {
            bulkActions.style.display = 'none';
        }
    }
}

function getSelectedPaymentIds() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    return Array.from(checkedBoxes).map(checkbox => checkbox.value);
}

function bulkVerify() {
    const paymentIds = getSelectedPaymentIds();
    if (paymentIds.length === 0) return;

    confirmAction(
        'Verifikasi Pembayaran',
        `Apakah Anda yakin ingin memverifikasi ${paymentIds.length} pembayaran terpilih?`,
        function() {
            bulkUpdateStatus(paymentIds, 'verify');
        }
    );
}

function bulkConfirm() {
    const paymentIds = getSelectedPaymentIds();
    if (paymentIds.length === 0) return;

    confirmAction(
        'Konfirmasi Pembayaran',
        `Apakah Anda yakin ingin mengkonfirmasi ${paymentIds.length} pembayaran terpilih?`,
        function() {
            bulkUpdateStatus(paymentIds, 'confirm');
        }
    );
}

function bulkReject() {
    const paymentIds = getSelectedPaymentIds();
    if (paymentIds.length === 0) return;

    confirmAction(
        'Tolak Pembayaran',
        `Apakah Anda yakin ingin menolak ${paymentIds.length} pembayaran terpilih?`,
        function() {
            const reason = prompt('Masukkan alasan penolakan:');
            if (reason) {
                bulkRejectPayments(paymentIds, reason);
            }
        }
    );
}

function bulkUpdateStatus(paymentIds, action) {
    const actionText = action === 'verify' ? 'memverifikasi' : 'mengkonfirmasi';

    Swal.fire({
        title: 'Memproses...',
        text: `Sedang ${actionText} pembayaran`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    Promise.all(paymentIds.map(paymentId => {
        return fetch(`/admin/payments/${paymentId}/${action}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        });
    }))
    .then(responses => {
        const allSuccessful = responses.every(response => response.ok);
        if (allSuccessful) {
            showSuccess(`${paymentIds.length} pembayaran berhasil ${action === 'verify' ? 'diverifikasi' : 'dikonfirmasi'}`);
            location.reload();
        } else {
            showError('Beberapa pembayaran gagal diproses');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
}

function bulkRejectPayments(paymentIds, reason) {
    Swal.fire({
        title: 'Memproses...',
        text: 'Sedang menolak pembayaran',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    Promise.all(paymentIds.map(paymentId => {
        const formData = new FormData();
        formData.append('rejection_reason', reason);

        return fetch(`/admin/payments/${paymentId}/reject`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        });
    }))
    .then(responses => {
        const allSuccessful = responses.every(response => response.ok);
        if (allSuccessful) {
            showSuccess(`${paymentIds.length} pembayaran berhasil ditolak`);
            location.reload();
        } else {
            showError('Beberapa pembayaran gagal ditolak');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
}
</script>
@endpush
