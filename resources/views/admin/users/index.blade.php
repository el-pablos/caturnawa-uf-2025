@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')



@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.create') }}" class="unas-btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Pengguna
        </a>
    </div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-people fs-1 mb-2 text-primary"></i>
                <div class="unas-stats-number">{{ number_format($stats['total']) }}</div>
                <div class="unas-stats-label">Total</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-check-circle fs-1 mb-2 text-success"></i>
                <div class="unas-stats-number text-success">{{ number_format($stats['active']) }}</div>
                <div class="unas-stats-label">Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-x-circle fs-1 mb-2 text-danger"></i>
                <div class="unas-stats-number text-danger">{{ number_format($stats['inactive']) }}</div>
                <div class="unas-stats-label">Nonaktif</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-shield-check fs-1 mb-2 text-primary"></i>
                <div class="unas-stats-number text-primary">{{ number_format($stats['super_admin']) }}</div>
                <div class="unas-stats-label">Super Admin</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-person-badge fs-1 mb-2 text-info"></i>
                <div class="unas-stats-number text-info">{{ number_format($stats['juri']) }}</div>
                <div class="unas-stats-label">Juri</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-person fs-1 mb-2 text-warning"></i>
                <div class="unas-stats-number text-warning">{{ number_format($stats['peserta']) }}</div>
                <div class="unas-stats-label">Peserta</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filter Pengguna
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Role</label>
                <select name="role" class="unas-form-control">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="unas-form-control">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Cari</label>
                <input type="text" name="search" class="unas-form-control" placeholder="Nama, email, atau telepon..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="unas-btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="unas-btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-people me-2"></i>Daftar Pengguna
        </h6>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Bergabung</th>
                            <th>Terakhir Login</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->avatar_url }}" class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                            @if($user->phone)
                                                <br>
                                                <small class="text-muted">{{ $user->phone }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button class="btn btn-outline-info" onclick="toggleStatus({{ $user->id }}, {{ $user->is_active ? 'false' : 'true' }})">
                                                <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                            @if(!$user->hasRole('Super Admin') || \App\Models\User::role('Super Admin')->count() > 1)
                                                <button class="btn btn-outline-danger" onclick="deleteUser({{ $user->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
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
                {{ $users->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people fs-1 text-muted"></i>
                <p class="text-muted mt-2">Tidak ada pengguna ditemukan</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(userId, newStatus) {
    const action = newStatus ? 'mengaktifkan' : 'menonaktifkan';
    
    confirmAction(
        'Ubah Status Pengguna',
        `Apakah Anda yakin ingin ${action} pengguna ini?`,
        function() {
            fetch(`/admin/users/${userId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(`Pengguna berhasil ${action === 'mengaktifkan' ? 'diaktifkan' : 'dinonaktifkan'}`);
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

function deleteUser(userId) {
    confirmAction(
        'Hapus Pengguna',
        'Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.',
        function() {
            fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Pengguna berhasil dihapus');
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
