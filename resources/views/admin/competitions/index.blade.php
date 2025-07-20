@extends('layouts.admin')

@section('title', 'Manage Competitions')

@section('page-title', 'Manajemen Kompetisi')

@section('sidebar-menu')
    <a class="nav-link" href="{{ route('admin.admin.dashboard') }}">
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
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="bi bi-trophy me-2"></i>Daftar Kompetisi
            </h6>
            <div class="btn-group" id="bulkActions" style="display: none;">
                <button class="btn btn-success btn-sm" onclick="bulkActivate()">
                    <i class="bi bi-check-circle me-1"></i>Aktivasi Terpilih
                </button>
                <button class="btn btn-warning btn-sm" onclick="bulkDeactivate()">
                    <i class="bi bi-pause-circle me-1"></i>Nonaktifkan Terpilih
                </button>
                <button class="btn btn-danger btn-sm" onclick="bulkDelete()">
                    <i class="bi bi-trash me-1"></i>Hapus Terpilih
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="competitionsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
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
                                    <input type="checkbox" class="form-check-input competition-checkbox" value="{{ $competition->id }}">
                                </td>
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
                                        {{ \App\Models\Competition::CATEGORIES[$competition->category] ?? $competition->category }}
                                    </span>
                                </td>
                                <td>
                                    @if($competition->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div>Rp {{ number_format($competition->registration_fee ?? 0, 0, ',', '.') }}</div>
                                    @if($competition->early_bird_fee)
                                        <small class="text-muted">Early: Rp {{ number_format($competition->early_bird_fee, 0, ',', '.') }}</small>
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

// Mass action functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.competition-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkActions();
});

document.querySelectorAll('.competition-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkActions);
});

function toggleBulkActions() {
    const checkedBoxes = document.querySelectorAll('.competition-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');

    if (checkedBoxes.length > 0) {
        bulkActions.style.display = 'block';
    } else {
        bulkActions.style.display = 'none';
    }
}

function getSelectedCompetitionIds() {
    const checkedBoxes = document.querySelectorAll('.competition-checkbox:checked');
    return Array.from(checkedBoxes).map(checkbox => checkbox.value);
}

function bulkActivate() {
    const competitionIds = getSelectedCompetitionIds();
    if (competitionIds.length === 0) return;

    if (confirm(`Apakah Anda yakin ingin mengaktivasi ${competitionIds.length} kompetisi terpilih?`)) {
        bulkUpdateStatus(competitionIds, true);
    }
}

function bulkDeactivate() {
    const competitionIds = getSelectedCompetitionIds();
    if (competitionIds.length === 0) return;

    if (confirm(`Apakah Anda yakin ingin menonaktifkan ${competitionIds.length} kompetisi terpilih?`)) {
        bulkUpdateStatus(competitionIds, false);
    }
}

function bulkDelete() {
    const competitionIds = getSelectedCompetitionIds();
    if (competitionIds.length === 0) return;

    if (confirm(`Apakah Anda yakin ingin menghapus ${competitionIds.length} kompetisi terpilih? Tindakan ini tidak dapat dibatalkan.`)) {
        bulkDeleteCompetitions(competitionIds);
    }
}

function bulkUpdateStatus(competitionIds, isActive) {
    Promise.all(competitionIds.map(competitionId => {
        return fetch(`/admin/competitions/${competitionId}/toggle-status`, {
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
            alert(`${competitionIds.length} kompetisi berhasil ${isActive ? 'diaktivasi' : 'dinonaktifkan'}`);
            location.reload();
        } else {
            alert('Beberapa kompetisi gagal diproses');
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan sistem');
    });
}

function bulkDeleteCompetitions(competitionIds) {
    Promise.all(competitionIds.map(competitionId => {
        return fetch(`/admin/competitions/${competitionId}`, {
            method: 'DELETE',
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
            alert(`${competitionIds.length} kompetisi berhasil dihapus`);
            location.reload();
        } else {
            alert('Beberapa kompetisi gagal dihapus');
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan sistem');
    });
}
</script>
@endpush
