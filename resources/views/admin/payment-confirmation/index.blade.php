@extends('layouts.admin')

@section('title', 'Konfirmasi Pembayaran')

@section('breadcrumb')
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0">Konfirmasi Pembayaran</h1>
        <nav class="ms-auto">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Konfirmasi Pembayaran</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-credit-card fs-1 mb-2 text-primary"></i>
                <div class="unas-stats-number">{{ number_format($stats['total']) }}</div>
                <div class="unas-stats-label">Total Pembayaran</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-clock fs-1 mb-2 text-warning"></i>
                <div class="unas-stats-number text-warning">{{ number_format($stats['pending']) }}</div>
                <div class="unas-stats-label">Menunggu Konfirmasi</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-check-circle fs-1 mb-2 text-success"></i>
                <div class="unas-stats-number text-success">{{ number_format($stats['confirmed']) }}</div>
                <div class="unas-stats-label">Sudah Dikonfirmasi</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-x-circle fs-1 mb-2 text-danger"></i>
                <div class="unas-stats-number text-danger">{{ number_format($stats['failed']) }}</div>
                <div class="unas-stats-label">Gagal/Ditolak</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filter Pembayaran
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kompetisi</label>
                <select name="competition_id" class="form-select">
                    <option value="">Semua Kompetisi</option>
                    @foreach($competitions as $competition)
                        <option value="{{ $competition->id }}" {{ request('competition_id') == $competition->id ? 'selected' : '' }}>
                            {{ $competition->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Sudah Dikonfirmasi</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal/Ditolak</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama peserta atau email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('admin.payment-confirmation.index') }}" class="btn btn-outline-secondary">
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
        <h6 class="mb-0">
            <i class="bi bi-credit-card me-2"></i>Daftar Pembayaran
        </h6>
        <button class="btn btn-success btn-sm" onclick="bulkConfirm()" id="bulkConfirmBtn" style="display: none;">
            <i class="bi bi-check-circle me-1"></i>Konfirmasi Terpilih
        </button>
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
                            <th>Peserta</th>
                            <th>Kompetisi</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>
                                    @if($payment->status === 'success' && !$payment->is_confirmed)
                                        <input type="checkbox" class="form-check-input payment-checkbox" value="{{ $payment->id }}">
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $payment->registration->user->avatar_url }}" class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                        <div>
                                            <strong>{{ $payment->registration->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $payment->registration->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $payment->registration->competition->name }}</td>
                                <td>
                                    <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($payment->is_confirmed)
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                        @if($payment->confirmed_at)
                                            <br><small class="text-muted">{{ $payment->confirmed_at->format('d/m/Y H:i') }}</small>
                                        @endif
                                    @elseif($payment->status === 'success')
                                        <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.payment-confirmation.show', $payment) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($payment->status === 'success' && !$payment->is_confirmed)
                                            <button class="btn btn-outline-success" onclick="confirmPayment({{ $payment->id }})">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="rejectPayment({{ $payment->id }})">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $payments->firstItem() }} - {{ $payments->lastItem() }} dari {{ $payments->total() }} pembayaran
                </div>
                {{ $payments->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-credit-card fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">Tidak ada pembayaran ditemukan</h5>
                <p class="text-muted">Belum ada pembayaran yang sesuai dengan filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="confirmForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Catatan Konfirmasi (Opsional)</label>
                        <textarea name="confirmation_notes" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Konfirmasi Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.payment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkConfirmButton();
});

// Individual checkbox change
document.querySelectorAll('.payment-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkConfirmButton);
});

function toggleBulkConfirmButton() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    const bulkBtn = document.getElementById('bulkConfirmBtn');
    
    if (checkedBoxes.length > 0) {
        bulkBtn.style.display = 'block';
    } else {
        bulkBtn.style.display = 'none';
    }
}

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

function bulkConfirm() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    const paymentIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (paymentIds.length === 0) {
        showError('Pilih minimal satu pembayaran untuk dikonfirmasi');
        return;
    }
    
    const notes = prompt(`Konfirmasi ${paymentIds.length} pembayaran?\n\nCatatan (opsional):`);
    if (notes === null) return; // User cancelled
    
    fetch('/admin/payment-confirmation/bulk-confirm', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            payment_ids: paymentIds,
            bulk_notes: notes 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
            location.reload();
        } else {
            showError(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
}
</script>
@endpush
