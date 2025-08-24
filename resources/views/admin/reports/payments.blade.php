@extends('layouts.admin')

@section('title', 'Laporan Pembayaran')

@section('page-title', 'Laporan Pembayaran')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <button type="button" class="btn btn-success" onclick="exportReport('excel')">
            <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
        </button>
        <button type="button" class="btn btn-danger" onclick="exportReport('pdf')">
            <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
        </button>
    </div>
@endsection

@section('content')
<!-- Filter Section -->
<div class="card mb-4" data-aos="fade-up">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filter Laporan Pembayaran
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.payments') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Status Transaksi</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Settlement</option>
                        <option value="capture" {{ request('status') == 'capture' ? 'selected' : '' }}>Capture</option>
                        <option value="deny" {{ request('status') == 'deny' ? 'selected' : '' }}>Deny</option>
                        <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                        <option value="expire" {{ request('status') == 'expire' ? 'selected' : '' }}>Expire</option>
                        <option value="failure" {{ request('status') == 'failure' ? 'selected' : '' }}>Failure</option>
                    </select>
                </div>
                
                <div class="col-md-2 mb-3">
                    <label for="payment_method" class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="payment_method" name="payment_method">
                        <option value="">Semua Metode</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="gopay" {{ request('payment_method') == 'gopay' ? 'selected' : '' }}>GoPay</option>
                        <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="date_from" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                
                <div class="col-md-2 mb-3">
                    <label for="date_to" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.reports.payments') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="100">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $summary['total_payments'] ?? 0 }}</h4>
                        <p class="mb-0">Total Transaksi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-credit-card fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="200">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">Rp {{ number_format($summary['total_amount'] ?? 0, 0, ',', '.') }}</h4>
                        <p class="mb-0">Total Nominal</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-wallet fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="300">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">Rp {{ number_format($summary['paid_amount'] ?? 0, 0, ',', '.') }}</h4>
                        <p class="mb-0">Sudah Terbayar</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="400">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">Rp {{ number_format($summary['pending_amount'] ?? 0, 0, ',', '.') }}</h4>
                        <p class="mb-0">Menunggu Pembayaran</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card" data-aos="fade-up" data-aos-delay="500">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-table me-2"></i>Detail Laporan Pembayaran
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="paymentsTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Peserta</th>
                        <th>Kompetisi</th>
                        <th>Metode Pembayaran</th>
                        <th>Status</th>
                        <th>Nominal</th>
                        <th>Tanggal Bayar</th>
                        <th>Tanggal Dibuat</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $payment->order_id }}</div>
                                <small class="text-muted">{{ $payment->transaction_id }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $payment->registration->user->name }}</div>
                                <small class="text-muted">{{ $payment->registration->user->email }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $payment->registration->competition->name }}</div>
                                <small class="text-muted">{{ $payment->registration->competition->category }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $payment->payment_type ?? 'N/A')) }}</span>
                            </td>
                            <td>
                                @switch($payment->transaction_status)
                                    @case('settlement')
                                    @case('capture')
                                        <span class="badge bg-success">Berhasil</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @break
                                    @case('deny')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @break
                                    @case('cancel')
                                        <span class="badge bg-secondary">Dibatalkan</span>
                                        @break
                                    @case('expire')
                                        <span class="badge bg-danger">Kadaluarsa</span>
                                        @break
                                    @case('failure')
                                        <span class="badge bg-danger">Gagal</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($payment->transaction_status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="fw-semibold">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</div>
                                @if($payment->admin_fee > 0)
                                    <small class="text-muted">Fee: Rp {{ number_format($payment->admin_fee, 0, ',', '.') }}</small>
                                @endif
                            </td>
                            <td>{{ $payment->transaction_time ? \Carbon\Carbon::parse($payment->transaction_time)->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}"
                                       class="btn btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($payment->transaction_status === 'pending')
                                        <button type="button" class="btn btn-outline-primary" 
                                                onclick="refreshPaymentStatus({{ $payment->id }})" 
                                                title="Refresh Status">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">Tidak ada data pembayaran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $payments->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#paymentsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[7, 'desc']], // Order by created date
        columnDefs: [
            { orderable: false, targets: [8] } // Actions column
        ],
        language: {
            emptyTable: "Tidak ada data pembayaran tersedia",
            zeroRecords: "Tidak ada data yang cocok dengan pencarian",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
            infoFiltered: "(disaring dari _MAX_ total entri)",
            lengthMenu: "Tampilkan _MENU_ entri",
            loadingRecords: "Memuat...",
            processing: "Memproses...",
            search: "Cari:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
});

function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('format', format);
    
    const baseUrl = '{{ route("admin.reports.export", "payments") }}';
    window.open(`${baseUrl}?${params.toString()}`, '_blank');
}

function refreshPaymentStatus(paymentId) {
    if (confirm('Refresh status pembayaran ini?')) {
        fetch(`/admin/payments/${paymentId}/refresh`, {
            method: 'POST',
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
                alert(data.message || 'Terjadi kesalahan saat refresh status.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat refresh status.');
        });
    }
}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush