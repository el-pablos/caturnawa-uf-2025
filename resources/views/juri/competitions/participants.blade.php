@extends('layouts.juri')

@section('title', 'Peserta Kompetisi')
@section('page-title', 'Peserta: ' . $competition->name)

@section('sidebar-menu')
    <a class="nav-link" href="{{ route('juri.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a class="nav-link" href="{{ route('juri.competitions.index') }}">
        <i class="bi bi-trophy me-2"></i>Kompetisi Saya
    </a>
    <a class="nav-link" href="{{ route('juri.scoring.index') }}">
        <i class="bi bi-star me-2"></i>Penilaian
    </a>
    <a class="nav-link" href="{{ route('juri.submissions.index') }}">
        <i class="bi bi-file-earmark-text me-2"></i>Review Submission
    </a>
@endsection

@section('content')
<!-- Competition Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trophy me-2"></i>{{ $competition->name }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('juri.competitions.show', $competition) }}" class="btn btn-light btn-sm">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                        <a href="{{ route('juri.competitions.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="text-muted mb-2">{{ $competition->description }}</p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <small class="text-muted">Kategori</small>
                                <div class="fw-semibold">{{ $competition->category }}</div>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">Total Peserta</small>
                                <div class="fw-semibold">{{ $participants->count() }} peserta</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <div class="h5 mb-0 text-success">{{ $participants->where('status', 'confirmed')->count() }}</div>
                                    <small class="text-muted">Terkonfirmasi</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <div class="h5 mb-0 text-warning">{{ $participants->where('status', 'pending')->count() }}</div>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Participants List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>Daftar Peserta
                    </h6>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                            <option value="">Semua Status</option>
                            <option value="confirmed">Terkonfirmasi</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                        <button class="btn btn-outline-primary btn-sm" onclick="exportParticipants()">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($participants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" id="participantsTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Peserta</th>
                                    <th>Institusi</th>
                                    <th>Kontak</th>
                                    <th>Status</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($participants as $index => $participant)
                                    <tr data-status="{{ $participant->status }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    @if($participant->user->avatar)
                                                        <img src="{{ $participant->user->avatar_url }}" 
                                                             class="rounded-circle" width="40" height="40" alt="Avatar">
                                                    @else
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <span class="text-white fw-bold">
                                                                {{ substr($participant->user->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $participant->display_name }}</div>
                                                    <small class="text-muted">{{ $participant->user->email }}</small>
                                                    @if($participant->team_name)
                                                        <div class="badge bg-info mt-1">Tim: {{ $participant->team_name }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $participant->institution ?: $participant->user->institution }}</div>
                                            <small class="text-muted">{{ $participant->registration_number }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $participant->phone ?: $participant->user->phone }}</div>
                                            @if($participant->emergency_contact)
                                                <small class="text-muted">
                                                    Emergency: {{ $participant->emergency_contact }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($participant->status === 'confirmed')
                                                <span class="badge bg-success">Terkonfirmasi</span>
                                            @elseif($participant->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($participant->status === 'cancelled')
                                                <span class="badge bg-danger">Dibatalkan</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($participant->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $participant->registered_at ? $participant->registered_at->format('d M Y') : 'N/A' }}</div>
                                            <small class="text-muted">{{ $participant->registered_at ? $participant->registered_at->format('H:i') : '' }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('peserta.registrations.show', $participant) }}" 
                                                   class="btn btn-outline-primary" target="_blank">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($participant->submission)
                                                    <a href="{{ route('juri.scoring.submission', $participant->submission) }}" 
                                                       class="btn btn-outline-success">
                                                        <i class="bi bi-star"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Peserta</h5>
                        <p class="text-muted">Belum ada peserta yang mendaftar untuk kompetisi ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm img {
    object-fit: cover;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('#participantsTable tbody tr');

    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value;
        
        tableRows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            
            if (selectedStatus === '' || rowStatus === selectedStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

function exportParticipants() {
    // Simple CSV export
    const table = document.getElementById('participantsTable');
    const rows = table.querySelectorAll('tr:not([style*="display: none"])');
    let csv = [];
    
    // Header
    const headers = ['No', 'Nama', 'Email', 'Institusi', 'Telepon', 'Status', 'Tanggal Daftar'];
    csv.push(headers.join(','));
    
    // Data rows
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.querySelectorAll('td');
        const rowData = [
            cells[0].textContent.trim(),
            cells[1].querySelector('.fw-semibold').textContent.trim(),
            cells[1].querySelector('.text-muted').textContent.trim(),
            cells[2].querySelector('.fw-semibold').textContent.trim(),
            cells[3].textContent.trim().split('\n')[0],
            cells[4].querySelector('.badge').textContent.trim(),
            cells[5].textContent.trim().split('\n')[0]
        ];
        csv.push(rowData.join(','));
    }
    
    // Download
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'participants_{{ $competition->name }}.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
@endpush
