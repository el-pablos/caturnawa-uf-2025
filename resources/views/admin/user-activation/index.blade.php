@extends('layouts.admin')

@section('title', 'Aktivasi Akun Peserta')

@section('breadcrumb')
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0">Aktivasi Akun Peserta</h1>
        <nav class="ms-auto">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Aktivasi Akun</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-people fs-1 mb-2 text-primary"></i>
                <div class="unas-stats-number">{{ number_format($stats['total']) }}</div>
                <div class="unas-stats-label">Total Peserta</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-clock fs-1 mb-2 text-warning"></i>
                <div class="unas-stats-number text-warning">{{ number_format($stats['pending']) }}</div>
                <div class="unas-stats-label">Menunggu Aktivasi</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-check-circle fs-1 mb-2 text-success"></i>
                <div class="unas-stats-number text-success">{{ number_format($stats['active']) }}</div>
                <div class="unas-stats-label">Sudah Aktif</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filter Peserta
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Aktivasi</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Sudah Aktif</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, email, atau institusi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.user-activation.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-people me-2"></i>Daftar Peserta
        </h6>
        <button class="btn btn-success btn-sm" onclick="bulkActivate()" id="bulkActivateBtn" style="display: none;">
            <i class="bi bi-check-circle me-1"></i>Aktivasi Terpilih
        </button>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Peserta</th>
                            <th>Institusi</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->avatar_url }}" class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->institution ?? 'Tidak ada' }}</td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                        @if($user->activated_at)
                                            <br><small class="text-muted">{{ $user->activated_at->format('d/m/Y H:i') }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-warning">Menunggu Aktivasi</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($user->is_active)
                                            <button class="btn btn-outline-warning" onclick="deactivateUser({{ $user->id }})">
                                                <i class="bi bi-x-circle"></i> Nonaktifkan
                                            </button>
                                        @else
                                            <button class="btn btn-outline-success" onclick="activateUser({{ $user->id }})">
                                                <i class="bi bi-check-circle"></i> Aktivasi
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
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} peserta
                </div>
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">Tidak ada peserta ditemukan</h5>
                <p class="text-muted">Belum ada peserta yang mendaftar atau sesuai dengan filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkActivateButton();
});

// Individual checkbox change
document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkActivateButton);
});

function toggleBulkActivateButton() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkBtn = document.getElementById('bulkActivateBtn');
    
    if (checkedBoxes.length > 0) {
        bulkBtn.style.display = 'block';
    } else {
        bulkBtn.style.display = 'none';
    }
}

function activateUser(userId) {
    confirmAction(
        'Aktivasi Akun',
        'Apakah Anda yakin ingin mengaktivasi akun ini?',
        function() {
            fetch(`/admin/user-activation/${userId}/activate`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccess(data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showError(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan sistem: ' + error.message);
            });
        }
    );
}

function deactivateUser(userId) {
    confirmAction(
        'Nonaktifkan Akun',
        'Apakah Anda yakin ingin menonaktifkan akun ini?',
        function() {
            fetch(`/admin/user-activation/${userId}/deactivate`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccess(data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showError(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan sistem: ' + error.message);
            });
        }
    );
}

function bulkActivate() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    const userIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (userIds.length === 0) {
        showError('Pilih minimal satu peserta untuk diaktivasi');
        return;
    }
    
    confirmAction(
        'Aktivasi Massal',
        `Apakah Anda yakin ingin mengaktivasi ${userIds.length} akun?`,
        function() {
            fetch('/admin/user-activation/bulk-activate', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ user_ids: userIds })
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
