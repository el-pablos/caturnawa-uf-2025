@extends('layouts.admin')

@section('title', 'Laporan Registrasi')

@section('page-title', 'Laporan Registrasi')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
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
            <i class="bi bi-funnel me-2"></i>Filter Laporan Registrasi
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.registrations') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="competition_id" class="form-label">Kompetisi</label>
                    <select class="form-select" id="competition_id" name="competition_id">
                        <option value="">Semua Kompetisi</option>
                        @foreach($competitions as $competition)
                            <option value="{{ $competition->id }}" 
                                {{ request('competition_id') == $competition->id ? 'selected' : '' }}>
                                {{ $competition->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                        <a href="{{ route('admin.reports.registrations') }}" class="btn btn-secondary">
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
                        <h4 class="mb-0">{{ $summary['total_registrations'] ?? 0 }}</h4>
                        <p class="mb-0">Total Registrasi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="200">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $summary['pending'] ?? 0 }}</h4>
                        <p class="mb-0">Pending</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="300">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $summary['confirmed'] ?? 0 }}</h4>
                        <p class="mb-0">Terkonfirmasi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="400">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $summary['cancelled'] ?? 0 }}</h4>
                        <p class="mb-0">Dibatalkan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-x-circle fs-1"></i>
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
            <i class="bi bi-table me-2"></i>Detail Laporan Registrasi
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="registrationsTable">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Kompetisi</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Status Pembayaran</th>
                        <th>Jumlah Bayar</th>
                        <th>Tanggal Daftar</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $registration->user->name }}</div>
                                <small class="text-muted">{{ $registration->user->phone ?? 'Tidak ada telepon' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $registration->competition->name }}</div>
                                <small class="text-muted">{{ $registration->competition->category }}</small>
                            </td>
                            <td>{{ $registration->user->email }}</td>
                            <td>
                                @switch($registration->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-success">Confirmed</span>
                                        @break
                                    @case('paid')
                                        <span class="badge bg-primary">Paid</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($registration->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                @if($registration->payment)
                                    @switch($registration->payment->transaction_status)
                                        @case('settlement')
                                        @case('capture')
                                            <span class="badge bg-success">Berhasil</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @break
                                        @case('deny')
                                        @case('cancel')
                                        @case('expire')
                                        @case('failure')
                                            <span class="badge bg-danger">Gagal</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($registration->payment->transaction_status) }}</span>
                                    @endswitch
                                @else
                                    <span class="badge bg-secondary">Belum Bayar</span>
                                @endif
                            </td>
                            <td>
                                @if($registration->payment)
                                    Rp {{ number_format($registration->payment->gross_amount, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.registrations.show', $registration->id) }}"
                                       class="btn btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">Tidak ada data registrasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($registrations->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $registrations->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#registrationsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[6, 'desc']], // Order by registration date
        columnDefs: [
            { orderable: false, targets: [7] } // Actions column
        ],
        language: {
            emptyTable: "Tidak ada data registrasi tersedia",
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
    
    const baseUrl = '{{ route("admin.reports.export", "registrations") }}';
    window.open(`${baseUrl}?${params.toString()}`, '_blank');
}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endpush