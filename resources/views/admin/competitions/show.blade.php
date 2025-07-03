@extends('layouts.admin')

@section('title', 'Detail Kompetisi')

@section('page-title', 'Detail Kompetisi')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.competitions.edit', $competition) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.competitions.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-trophy me-2"></i>{{ $competition->name }}
                </h5>
            </div>
            <div class="card-body">
                @if($competition->image)
                    <div class="mb-4 text-center">
                        <img src="{{ asset('storage/' . $competition->image) }}" 
                             alt="{{ $competition->name }}" 
                             class="img-fluid rounded" 
                             style="max-height: 300px;">
                    </div>
                @endif
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="bi bi-tag me-2"></i>Kategori</h6>
                        <p class="text-muted">{{ ucfirst($competition->category) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-flag me-2"></i>Status</h6>
                        <div class="d-flex align-items-center">
                            <span id="status-badge" class="badge bg-{{ $competition->is_active ? 'success' : 'secondary' }} fs-6 me-2">
                                {{ $competition->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleCompetitionStatus({{ $competition->id }})">
                                <i class="bi bi-toggle-{{ $competition->is_active ? 'on' : 'off' }} me-1"></i>
                                Toggle
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6><i class="bi bi-file-text me-2"></i>Deskripsi</h6>
                    <div class="text-muted">
                        {!! nl2br(e($competition->description)) !!}
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="bi bi-cash me-2"></i>Harga Pendaftaran</h6>
                        <p class="text-muted">Rp {{ number_format($competition->price, 0, ',', '.') }}</p>
                        
                        @if($competition->early_bird_price)
                            <h6><i class="bi bi-lightning me-2"></i>Harga Early Bird</h6>
                            <p class="text-muted">Rp {{ number_format($competition->early_bird_price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($competition->max_participants)
                            <h6><i class="bi bi-people me-2"></i>Maksimal Peserta</h6>
                            <p class="text-muted">{{ number_format($competition->max_participants) }} peserta</p>
                        @endif
                        
                        <h6><i class="bi bi-person-check me-2"></i>Peserta Terdaftar</h6>
                        <p class="text-muted">{{ $competition->registrations_count ?? 0 }} peserta</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar-event me-2"></i>Periode Pendaftaran</h6>
                        <p class="text-muted">
                            <strong>Mulai:</strong> {{ $competition->registration_start?->format('d M Y H:i') ?? 'Belum ditentukan' }}<br>
                            <strong>Berakhir:</strong> {{ $competition->registration_end?->format('d M Y H:i') ?? 'Belum ditentukan' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-clock me-2"></i>Informasi Waktu</h6>
                        <p class="text-muted">
                            <strong>Dibuat:</strong> {{ $competition->created_at->format('d M Y H:i') }}<br>
                            <strong>Diupdate:</strong> {{ $competition->updated_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($competition->registrations_count > 0)
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-people me-2"></i>Peserta Terdaftar ({{ $competition->registrations_count }})
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Institusi</th>
                                <th>Status</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($competition->registrations()->latest()->take(10)->get() as $registration)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($registration->user->avatar)
                                            <img src="{{ asset('storage/' . $registration->user->avatar) }}" 
                                                 alt="{{ $registration->user->name }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 32px; height: 32px;">
                                                <span class="text-white fw-bold">{{ substr($registration->user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        {{ $registration->user->name }}
                                    </div>
                                </td>
                                <td>{{ $registration->user->email }}</td>
                                <td>{{ $registration->user->institution ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $registration->status === 'confirmed' ? 'success' : ($registration->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                </td>
                                <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.registrations.show', $registration) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada peserta yang terdaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($competition->registrations_count > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.competitions.registrations', $competition) }}" class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i>Lihat Semua Peserta ({{ $competition->registrations_count }})
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.competitions.edit', $competition) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Kompetisi
                    </a>
                    
                    @if($competition->registrations_count > 0)
                        <a href="{{ route('admin.competitions.registrations', $competition) }}" class="btn btn-info">
                            <i class="bi bi-people me-2"></i>Lihat Peserta
                        </a>
                        
                        <a href="{{ route('admin.competitions.export', $competition) }}" class="btn btn-success">
                            <i class="bi bi-download me-2"></i>Export Data
                        </a>
                    @endif
                    
                    <button type="button" class="btn btn-{{ $competition->is_active ? 'warning' : 'success' }} w-100" onclick="toggleCompetitionStatus({{ $competition->id }})">
                        <i class="bi bi-{{ $competition->is_active ? 'pause-circle' : 'play-circle' }} me-2"></i>
                        {{ $competition->is_active ? 'Nonaktifkan Kompetisi' : 'Aktifkan Kompetisi' }}
                    </button>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-2"></i>Hapus Kompetisi
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Statistik
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $competition->registrations_count ?? 0 }}</h4>
                            <small class="text-muted">Total Peserta</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">{{ $competition->registrations()->where('status', 'confirmed')->count() }}</h4>
                        <small class="text-muted">Terkonfirmasi</small>
                    </div>
                </div>
                
                @if($competition->max_participants)
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Kapasitas</small>
                            <small>{{ $competition->registrations_count ?? 0 }}/{{ $competition->max_participants }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" 
                                 style="width: {{ min(100, (($competition->registrations_count ?? 0) / $competition->max_participants) * 100) }}%">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kompetisi <strong>{{ $competition->name }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait termasuk registrasi peserta.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.competitions.destroy', $competition) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-toggle-on me-2"></i>Ubah Status Kompetisi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-question-circle-fill text-warning" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-3">
                    Apakah Anda yakin ingin <span id="toggle-action-text">mengubah</span> status kompetisi ini?
                </p>
                <div class="alert alert-info">
                    <h6 class="alert-heading">Informasi:</h6>
                    <ul class="mb-0">
                        <li><strong>Aktif:</strong> Kompetisi dapat dilihat dan diikuti peserta</li>
                        <li><strong>Nonaktif:</strong> Kompetisi disembunyikan dari peserta</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-primary" id="confirmToggleBtn">
                    <i class="bi bi-check-lg me-2"></i>Ya, Ubah Status
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentCompetitionId = null;
let currentStatus = null;

function toggleCompetitionStatus(competitionId) {
    currentCompetitionId = competitionId;

    // Get current status from badge
    const statusBadge = document.getElementById('status-badge');
    const isActive = statusBadge.classList.contains('bg-success');
    currentStatus = isActive;

    // Update modal text
    const actionText = isActive ? 'menonaktifkan' : 'mengaktifkan';
    document.getElementById('toggle-action-text').textContent = actionText;

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('toggleStatusModal'));
    modal.show();
}

document.getElementById('confirmToggleBtn').addEventListener('click', function() {
    if (!currentCompetitionId) return;

    // Show loading state
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengubah...';
    this.disabled = true;

    // Make AJAX request
    fetch(`/admin/competitions/${currentCompetitionId}/toggle-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status badge
            const statusBadge = document.getElementById('status-badge');
            const newStatus = data.status;

            if (newStatus) {
                statusBadge.className = 'badge bg-success fs-6 me-2';
                statusBadge.textContent = 'Aktif';
            } else {
                statusBadge.className = 'badge bg-secondary fs-6 me-2';
                statusBadge.textContent = 'Nonaktif';
            }

            // Update toggle button in status section
            const toggleBtn = statusBadge.nextElementSibling;
            const toggleIcon = toggleBtn.querySelector('i');
            toggleIcon.className = `bi bi-toggle-${newStatus ? 'on' : 'off'} me-1`;

            // Update main action button
            const mainActionBtn = document.querySelector('.btn.w-100[onclick*="toggleCompetitionStatus"]');
            if (mainActionBtn) {
                const icon = mainActionBtn.querySelector('i');
                icon.className = `bi bi-${newStatus ? 'pause-circle' : 'play-circle'} me-2`;
                mainActionBtn.className = `btn btn-${newStatus ? 'warning' : 'success'} w-100`;
                mainActionBtn.innerHTML = `<i class="bi bi-${newStatus ? 'pause-circle' : 'play-circle'} me-2"></i>${newStatus ? 'Nonaktifkan Kompetisi' : 'Aktifkan Kompetisi'}`;
            }

            // Show success toast
            showToast('success', data.message);

            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('toggleStatusModal')).hide();
        } else {
            showToast('error', data.message || 'Terjadi kesalahan saat mengubah status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Terjadi kesalahan saat mengubah status');
    })
    .finally(() => {
        // Reset button
        const btn = document.getElementById('confirmToggleBtn');
        btn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Ya, Ubah Status';
        btn.disabled = false;
    });
});

function showToast(type, message) {
    const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const toastHtml = `
        <div class="toast align-items-center text-white ${toastClass} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    // Create toast container if not exists
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }

    // Add toast
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();

    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}
</script>
@endpush
