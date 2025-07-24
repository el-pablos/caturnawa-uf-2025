@extends('layouts.admin')

@section('title', 'Laporan Kompetisi')

@section('page-title', 'Laporan Kompetisi')

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
            <i class="bi bi-funnel me-2"></i>Filter Laporan Kompetisi
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.competitions') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="date_from" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="date_to" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.reports.competitions') }}" class="btn btn-secondary">
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
    <div class="col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="100">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $summary['total_competitions'] ?? 0 }}</h4>
                        <p class="mb-0">Total Kompetisi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-trophy fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="200">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $summary['total_registrations'] ?? 0 }}</h4>
                        <p class="mb-0">Total Pendaftaran</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="300">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $summary['total_confirmed'] ?? 0 }}</h4>
                        <p class="mb-0">Terkonfirmasi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card" data-aos="fade-up" data-aos-delay="400">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-table me-2"></i>Detail Laporan Kompetisi
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="competitionsTable">
                <thead>
                    <tr>
                        <th>Nama Kompetisi</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Pendaftaran</th>
                        <th>Terkonfirmasi</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Dibuat</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($competitions as $competition)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $competition->name }}</div>
                                <small class="text-muted">{{ Str::limit($competition->description, 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $competition->category ?? 'Tidak Ada' }}</span>
                            </td>
                            <td>
                                @if($competition->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $competition->registrations_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $competition->confirmed_registrations_count }}</span>
                            </td>
                            <td>{{ $competition->registration_start ? $competition->registration_start->format('d/m/Y') : '-' }}</td>
                            <td>{{ $competition->registration_end ? $competition->registration_end->format('d/m/Y') : '-' }}</td>
                            <td>{{ $competition->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.competitions.show', $competition->id) }}"
                                       class="btn btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">Tidak ada data kompetisi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($competitions->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $competitions->appends(request()->query())->links() }}
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
    $('#competitionsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [8] } // Actions column
        ],
        language: {
            emptyTable: "Tidak ada data kompetisi tersedia",
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
    
    const baseUrl = '{{ route("admin.reports.export", "competitions") }}';
    window.open(`${baseUrl}?${params.toString()}`, '_blank');
}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endpush