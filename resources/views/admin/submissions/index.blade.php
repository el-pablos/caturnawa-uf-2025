@extends('layouts.app')

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
                <div class="unas-stats-number text-warning">{{ number_format($stats['pending']) }}</div>
                <div class="unas-stats-label">Menunggu Review</div>
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
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
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
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-file-earmark-text me-2"></i>Daftar Karya Peserta
        </h6>
    </div>
    <div class="card-body">
        @if($submissions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
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
    document.getElementById('rejectForm').action = `/admin/submissions/${submissionId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
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
</script>
@endpush
