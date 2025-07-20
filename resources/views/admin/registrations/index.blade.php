@extends('layouts.admin')

@section('title', 'Kelola Registrasi')
@section('page-title', 'Kelola Registrasi')



@section('header-actions')
    <div class="d-flex gap-2">
        <button class="unas-btn-secondary" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="bi bi-download me-2"></i>Export
        </button>
    </div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="unas-stats-number">{{ number_format($stats['total']) }}</div>
                    <div class="unas-stats-label">Total Registrasi</div>
                </div>
                <div class="fs-1 text-primary opacity-75">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="unas-stats-number text-warning">{{ number_format($stats['pending']) }}</div>
                    <div class="unas-stats-label">Menunggu</div>
                </div>
                <div class="fs-1 text-warning opacity-75">
                    <i class="bi bi-clock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="unas-stats-number text-success">{{ number_format($stats['confirmed']) }}</div>
                    <div class="unas-stats-label">Dikonfirmasi</div>
                </div>
                <div class="fs-1 text-success opacity-75">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="unas-stats-number text-danger">{{ number_format($stats['cancelled']) }}</div>
                    <div class="unas-stats-label">Dibatalkan</div>
                </div>
                <div class="fs-1 text-danger opacity-75">
                    <i class="bi bi-x-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filter Registrasi
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="filter-status" class="form-label fw-semibold">Status</label>
                <select name="status" id="filter-status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-competition" class="form-label fw-semibold">Kompetisi</label>
                <select name="competition_id" id="filter-competition" class="form-control">
                    <option value="">Semua Kompetisi</option>
                    @foreach($competitions as $competition)
                        <option value="{{ $competition->id }}" {{ request('competition_id') == $competition->id ? 'selected' : '' }}>
                            {{ $competition->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="filter-search" class="form-label fw-semibold">Cari</label>
                <input type="text" name="search" id="filter-search" class="form-control" placeholder="Nama atau email peserta..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label for="filter-submit" class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" id="filter-submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Registrations Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-clipboard-check me-2"></i>Daftar Registrasi
        </h6>
        <div class="btn-group" id="bulkActions" style="display: none;">
            <button class="btn btn-success btn-sm" onclick="bulkConfirm()">
                <i class="bi bi-check-circle me-1"></i>Konfirmasi Terpilih
            </button>
            <button class="btn btn-warning btn-sm" onclick="bulkCancel()">
                <i class="bi bi-x-circle me-1"></i>Batalkan Terpilih
            </button>
            <button class="btn btn-danger btn-sm" onclick="bulkDelete()">
                <i class="bi bi-trash me-1"></i>Hapus Terpilih
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($registrations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <label for="selectAll" class="visually-hidden">Pilih Semua</label>
                                <input type="checkbox" id="selectAll" class="form-check-input" aria-label="Pilih semua registrasi">
                            </th>
                            <th>ID</th>
                            <th>Peserta</th>
                            <th>Kompetisi</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $registration)
                            <tr>
                                <td>
                                    <label for="registration-checkbox-{{ $registration->id }}" class="visually-hidden">Pilih registrasi #{{ $registration->id }}</label>
                                    <input type="checkbox" id="registration-checkbox-{{ $registration->id }}" class="form-check-input registration-checkbox" value="{{ $registration->id }}" aria-label="Pilih registrasi #{{ $registration->id }}">
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">#{{ $registration->id }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $registration->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $registration->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $registration->competition->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $registration->competition->category }}</small>
                                    </div>
                                </td>
                                <td>{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($registration->status === 'pending')
                                        <span class="unas-badge-warning">Menunggu</span>
                                    @elseif($registration->status === 'paid')
                                        <span class="unas-badge-info">Dibayar</span>
                                    @elseif($registration->status === 'confirmed')
                                        <span class="unas-badge-success">Dikonfirmasi</span>
                                    @elseif($registration->status === 'cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($registration->payment)
                                        @if($registration->payment->status === 'paid')
                                            <span class="unas-badge-success">Lunas</span>
                                        @elseif($registration->payment->status === 'pending')
                                            <span class="unas-badge-warning">Menunggu</span>
                                        @else
                                            <span class="badge bg-danger">Gagal</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Belum Bayar</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.registrations.show', $registration) }}" class="btn btn-outline-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($registration->status === 'pending')
                                            <button class="btn btn-outline-success" onclick="confirmRegistration({{ $registration->id }})" title="Konfirmasi">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="cancelRegistration({{ $registration->id }})" title="Batalkan">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        @elseif($registration->status === 'cancelled')
                                            <button class="btn btn-outline-warning" onclick="reEnableRegistration({{ $registration->id }})" title="Aktifkan Kembali">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        @endif

                                        @if($registration->status !== 'confirmed' || !$registration->payment?->isSuccess())
                                            <button class="btn btn-outline-danger" onclick="deleteRegistration({{ $registration->id }})" title="Hapus Permanen">
                                                <i class="bi bi-trash"></i>
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
            <div class="d-flex justify-content-center">
                {{ $registrations->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-2">Tidak ada registrasi ditemukan</p>
            </div>
        @endif
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Registrasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="mb-3">
                        <label for="export-format" class="form-label">Format</label>
                        <select name="format" id="export-format" class="form-select" required>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="export-status" class="form-label">Filter Status</label>
                        <select name="status" id="export-status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu</option>
                            <option value="confirmed">Dikonfirmasi</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="export-competition" class="form-label">Filter Kompetisi</label>
                        <select name="competition_id" id="export-competition" class="form-select">
                            <option value="">Semua Kompetisi</option>
                            @foreach($competitions as $competition)
                                <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="exportData()">
                    <i class="bi bi-download me-2"></i>Export
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmRegistration(id) {
    confirmAction(
        'Konfirmasi Registrasi',
        'Apakah Anda yakin ingin mengkonfirmasi registrasi ini?',
        function() {
            fetch(`/admin/registrations/${id}/confirm`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Registrasi berhasil dikonfirmasi');
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

function cancelRegistration(id) {
    confirmAction(
        'Batalkan Registrasi',
        'Apakah Anda yakin ingin membatalkan registrasi ini?',
        function() {
            fetch(`/admin/registrations/${id}/cancel`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Registrasi berhasil dibatalkan');
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

function exportData() {
    const form = document.getElementById('exportForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    window.open(`{{ route('admin.registrations.export.excel') }}?${params.toString()}`, '_blank');
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
    modal.hide();
}

function reEnableRegistration(id) {
    confirmAction(
        'Aktifkan Kembali Registrasi',
        'Apakah Anda yakin ingin mengaktifkan kembali registrasi ini? Peserta akan dapat mendaftar ulang.',
        function() {
            fetch(`/admin/registrations/${id}/re-enable`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
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
    );
}

function deleteRegistration(id) {
    confirmAction(
        'Hapus Registrasi',
        'Apakah Anda yakin ingin menghapus registrasi ini secara permanen? Tindakan ini tidak dapat dibatalkan.',
        function() {
            fetch(`/admin/registrations/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
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
    );
}

// Mass action functionality
const selectAllElement = document.getElementById('selectAll');
if (selectAllElement) {
    selectAllElement.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.registration-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });
}

const registrationCheckboxes = document.querySelectorAll('.registration-checkbox');
if (registrationCheckboxes.length > 0) {
    registrationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });
}

function toggleBulkActions() {
    const checkedBoxes = document.querySelectorAll('.registration-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');

    if (bulkActions) {
        if (checkedBoxes.length > 0) {
            bulkActions.style.display = 'block';
        } else {
            bulkActions.style.display = 'none';
        }
    }
}

function getSelectedRegistrationIds() {
    const checkedBoxes = document.querySelectorAll('.registration-checkbox:checked');
    return Array.from(checkedBoxes).map(checkbox => checkbox.value);
}

function bulkConfirm() {
    const registrationIds = getSelectedRegistrationIds();
    if (registrationIds.length === 0) return;

    confirmAction(
        'Konfirmasi Registrasi',
        `Apakah Anda yakin ingin mengkonfirmasi ${registrationIds.length} registrasi terpilih?`,
        function() {
            bulkUpdateStatus(registrationIds, 'confirm');
        }
    );
}

function bulkCancel() {
    const registrationIds = getSelectedRegistrationIds();
    if (registrationIds.length === 0) return;

    confirmAction(
        'Batalkan Registrasi',
        `Apakah Anda yakin ingin membatalkan ${registrationIds.length} registrasi terpilih?`,
        function() {
            bulkUpdateStatus(registrationIds, 'cancel');
        }
    );
}

function bulkDelete() {
    const registrationIds = getSelectedRegistrationIds();
    if (registrationIds.length === 0) return;

    confirmAction(
        'Hapus Registrasi',
        `Apakah Anda yakin ingin menghapus ${registrationIds.length} registrasi terpilih? Tindakan ini tidak dapat dibatalkan.`,
        function() {
            bulkDeleteRegistrations(registrationIds);
        }
    );
}

function bulkUpdateStatus(registrationIds, action) {
    const actionText = action === 'confirm' ? 'mengkonfirmasi' : 'membatalkan';

    Swal.fire({
        title: 'Memproses...',
        text: `Sedang ${actionText} registrasi`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    Promise.all(registrationIds.map(registrationId => {
        const url = action === 'confirm'
            ? `/admin/registrations/${registrationId}/confirm`
            : `/admin/registrations/${registrationId}/cancel`;

        return fetch(url, {
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
            showSuccess(`${registrationIds.length} registrasi berhasil ${action === 'confirm' ? 'dikonfirmasi' : 'dibatalkan'}`);
            location.reload();
        } else {
            showError('Beberapa registrasi gagal diproses');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
}

function bulkDeleteRegistrations(registrationIds) {
    Swal.fire({
        title: 'Memproses...',
        text: 'Sedang menghapus registrasi',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    Promise.all(registrationIds.map(registrationId => {
        return fetch(`/admin/registrations/${registrationId}`, {
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
            showSuccess(`${registrationIds.length} registrasi berhasil dihapus`);
            location.reload();
        } else {
            showError('Beberapa registrasi gagal dihapus');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
}
</script>
@endpush
