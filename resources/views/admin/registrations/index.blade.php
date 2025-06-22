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
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kompetisi</label>
                <select name="competition_id" class="form-control">
                    <option value="">Semua Kompetisi</option>
                    @foreach($competitions as $competition)
                        <option value="{{ $competition->id }}" {{ request('competition_id') == $competition->id ? 'selected' : '' }}>
                            {{ $competition->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama atau email peserta..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
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
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-clipboard-check me-2"></i>Daftar Registrasi
        </h6>
    </div>
    <div class="card-body">
        @if($registrations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
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
                        <label class="form-label">Format</label>
                        <select name="format" class="form-select" required>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Filter Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu</option>
                            <option value="confirmed">Dikonfirmasi</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Filter Kompetisi</label>
                        <select name="competition_id" class="form-select">
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
</script>
@endpush
