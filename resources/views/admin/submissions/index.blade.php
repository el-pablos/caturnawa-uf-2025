@extends('layouts.admin')

@section('title', 'Kelola Karya Peserta')

@section('page-title', 'Kelola Karya Peserta')

@section('header-actions')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" onclick="exportSubmissions('excel')">
            <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
        </button>
        <button type="button" class="btn btn-danger" onclick="exportSubmissions('pdf')">
            <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
        </button>
    </div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-file-earmark-text fs-1 mb-2 text-primary"></i>
                <div class="unas-stats-number">{{ number_format($stats['total']) }}</div>
                <div class="unas-stats-label">Total Karya</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-clock fs-1 mb-2 text-warning"></i>
                <div class="unas-stats-number text-warning">{{ number_format($stats['submitted']) }}</div>
                <div class="unas-stats-label">Terkirim</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-check-circle fs-1 mb-2 text-success"></i>
                <div class="unas-stats-number text-success">{{ number_format($stats['approved']) }}</div>
                <div class="unas-stats-label">Disetujui</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-x-circle fs-1 mb-2 text-danger"></i>
                <div class="unas-stats-number text-danger">{{ number_format($stats['rejected']) }}</div>
                <div class="unas-stats-label">Ditolak</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filter Karya
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
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
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Terkirim</option>
                    <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Sudah Direview</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
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
                    <a href="{{ route('admin.submissions.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Submissions Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-file-earmark-text me-2"></i>Daftar Karya Peserta
        </h6>
        <div class="btn-group" id="bulkActions" style="display: none;">
            <button class="btn btn-success btn-sm" onclick="bulkApprove()">
                <i class="bi bi-check-circle me-1"></i>Setujui Terpilih
            </button>
            <button class="btn btn-warning btn-sm" onclick="bulkReject()">
                <i class="bi bi-x-circle me-1"></i>Tolak Terpilih
            </button>
            <button class="btn btn-danger btn-sm" onclick="bulkDelete()">
                <i class="bi bi-trash me-1"></i>Hapus Terpilih
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($submissions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Peserta</th>
                            <th>Kompetisi</th>
                            <th>Judul Karya</th>
                            <th>Status</th>
                            <th>Tanggal Submit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $submission)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input submission-checkbox" value="{{ $submission->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $submission->registration->user->avatar_url }}" class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                        <div>
                                            <strong>{{ $submission->registration->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $submission->registration->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $submission->registration->competition->name }}</span>
                                </td>
                                <td>
                                    <strong>{{ $submission->title ?? 'Belum ada judul' }}</strong>
                                    @if($submission->description)
                                        <br>
                                        <small class="text-muted">{{ Str::limit($submission->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($submission->status === 'pending')
                                        <span class="badge bg-warning">Menunggu Review</span>
                                    @elseif($submission->status === 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($submission->status === 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($submission->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $submission->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.submissions.show', $submission) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($submission->status === 'pending')
                                            <button class="btn btn-outline-success" onclick="approveSubmission({{ $submission->id }})">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="rejectSubmission({{ $submission->id }})">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-outline-danger" onclick="deleteSubmission({{ $submission->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $submissions->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-text fs-1 text-muted"></i>
                <p class="text-muted mt-2">Tidak ada karya peserta ditemukan</p>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-x-circle me-2"></i>Tolak Karya
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required
                                  placeholder="Jelaskan alasan penolakan karya ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-2"></i>Tolak Karya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function approveSubmission(submissionId) {
    confirmAction(
        'Setujui Karya',
        'Apakah Anda yakin ingin menyetujui karya ini?',
        function() {
            fetch(`/admin/submissions/${submissionId}/approve`, {
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

function rejectSubmission(submissionId) {
    const rejectForm = document.getElementById('rejectForm');
    const rejectModal = document.getElementById('rejectModal');

    if (rejectForm && rejectModal) {
        rejectForm.action = `/admin/submissions/${submissionId}/reject`;
        new bootstrap.Modal(rejectModal).show();
    }
}

function deleteSubmission(submissionId) {
    confirmAction(
        'Hapus Karya',
        'Apakah Anda yakin ingin menghapus karya ini? Tindakan ini tidak dapat dibatalkan.',
        function() {
            fetch(`/admin/submissions/${submissionId}`, {
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

function exportSubmissions(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);

    window.open(`/admin/submissions/export?${params.toString()}`, '_blank');
}

// Mass action functionality
const selectAllElement = document.getElementById('selectAll');
if (selectAllElement) {
    selectAllElement.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.submission-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });
}

const submissionCheckboxes = document.querySelectorAll('.submission-checkbox');
if (submissionCheckboxes.length > 0) {
    submissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });
}

function toggleBulkActions() {
    const checkedBoxes = document.querySelectorAll('.submission-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');

    if (bulkActions) {
        if (checkedBoxes.length > 0) {
            bulkActions.style.display = 'block';
        } else {
            bulkActions.style.display = 'none';
        }
    }
}

function getSelectedSubmissionIds() {
    const checkedBoxes = document.querySelectorAll('.submission-checkbox:checked');
    return Array.from(checkedBoxes).map(checkbox => checkbox.value);
}

function bulkApprove() {
    const submissionIds = getSelectedSubmissionIds();
    if (submissionIds.length === 0) return;

    confirmAction(
        'Setujui Karya',
        `Apakah Anda yakin ingin menyetujui ${submissionIds.length} karya terpilih?`,
        function() {
            bulkUpdateStatus(submissionIds, 'approve');
        }
    );
}

function bulkReject() {
    const submissionIds = getSelectedSubmissionIds();
    if (submissionIds.length === 0) return;

    confirmAction(
        'Tolak Karya',
        `Apakah Anda yakin ingin menolak ${submissionIds.length} karya terpilih?`,
        function() {
            bulkUpdateStatus(submissionIds, 'reject');
        }
    );
}

function bulkDelete() {
    const submissionIds = getSelectedSubmissionIds();
    if (submissionIds.length === 0) return;

    confirmAction(
        'Hapus Karya',
        `Apakah Anda yakin ingin menghapus ${submissionIds.length} karya terpilih? Tindakan ini tidak dapat dibatalkan.`,
        function() {
            bulkDeleteSubmissions(submissionIds);
        }
    );
}

function bulkUpdateStatus(submissionIds, action) {
    const actionText = action === 'approve' ? 'menyetujui' : 'menolak';

    Swal.fire({
        title: 'Memproses...',
        text: `Sedang ${actionText} karya`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    Promise.all(submissionIds.map(submissionId => {
        return fetch(`/admin/submissions/${submissionId}/${action}`, {
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
            showSuccess(`${submissionIds.length} karya berhasil ${action === 'approve' ? 'disetujui' : 'ditolak'}`);
            location.reload();
        } else {
            showError('Beberapa karya gagal diproses');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
}

function bulkDeleteSubmissions(submissionIds) {
    Swal.fire({
        title: 'Memproses...',
        text: 'Sedang menghapus karya',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    Promise.all(submissionIds.map(submissionId => {
        return fetch(`/admin/submissions/${submissionId}`, {
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
            showSuccess(`${submissionIds.length} karya berhasil dihapus`);
            location.reload();
        } else {
            showError('Beberapa karya gagal dihapus');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
}
</script>
@endpush
