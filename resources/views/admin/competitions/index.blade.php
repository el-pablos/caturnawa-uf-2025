@extends('layouts.app')

@section('title', 'Manage Competitions')

@section('page-title', 'Manajemen Kompetisi')

@section('sidebar-menu')
    <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a class="nav-link active" href="{{ route('admin.competitions.index') }}">
        <i class="bi bi-trophy me-2"></i>Kompetisi
    </a>
    <a class="nav-link" href="{{ route('admin.registrations.index') }}">
        <i class="bi bi-person-check me-2"></i>Registrasi
    </a>
    <a class="nav-link" href="{{ route('admin.payments.index') }}">
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

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.competitions.create') }}" class="unas-btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kompetisi
        </a>
    </div>
@endsection

@section('content')



    <!-- Competitions Table -->
    <div class="unas-card">
        <div class="unas-card-header">
            <h5 class="mb-0 text-white">
                <i class="bi bi-trophy me-2"></i>Daftar Kompetisi
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="competitionsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Kompetisi</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Harga</th>
                            <th>Pendaftaran</th>
                            <th>Peserta</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($competitions ?? [] as $competition)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold">{{ $competition->name }}</div>
                                            <div class="text-muted small">{{ Str::limit($competition->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="unas-badge-primary">
                                        {{ ucfirst($competition->category) }}
                                    </span>
                                </td>
                                <td>
                                    @if($competition->status === 'active')
                                        <span class="unas-badge-success">Aktif</span>
                                    @elseif($competition->status === 'inactive')
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @elseif($competition->status === 'draft')
                                        <span class="unas-badge-warning">Draft</span>
                                    @else
                                        <span class="badge bg-info">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <div>Rp {{ number_format($competition->price, 0, ',', '.') }}</div>
                                    @if($competition->early_bird_price)
                                        <small class="text-muted">Early: Rp {{ number_format($competition->early_bird_price, 0, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        <div>Start: {{ $competition->registration_start ? $competition->registration_start->format('d M Y') : 'N/A' }}</div>
                                        <div>End: {{ $competition->registration_end ? $competition->registration_end->format('d M Y') : 'N/A' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $competition->registrations_count ?? 0 }}</span>
                                        @if($competition->max_participants)
                                            <span class="text-muted">/ {{ $competition->max_participants }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.competitions.show', $competition) }}"
                                           class="btn btn-outline-info" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.competitions.edit', $competition) }}"
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger"
                                                onclick="deleteCompetition({{ $competition->id }})" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-trophy fs-1 text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak Ada Kompetisi</h5>
                                    <p class="text-muted">Mulai dengan membuat kompetisi pertama Anda.</p>
                                    <a href="{{ route('admin.competitions.create') }}" class="unas-btn-primary">
                                        <i class="bi bi-plus-lg me-2"></i>Buat Kompetisi
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Hapus Kompetisi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kompetisi ini?</p>
                <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan dan akan mempengaruhi semua registrasi terkait.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#competitionsTable').DataTable({
        "responsive": true,
        "pageLength": 10,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [6] }
        ]
    });
});

function deleteCompetition(id) {
    $('#deleteForm').attr('action', '{{ route("admin.competitions.destroy", ":id") }}'.replace(':id', id));
    $('#deleteModal').modal('show');
}
</script>
@endpush
