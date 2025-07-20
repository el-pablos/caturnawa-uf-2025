@extends('layouts.admin')

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
                <label for="filter-role" class="form-label fw-semibold">Role</label>
                <select name="role" id="filter-role" class="unas-form-control">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-status" class="form-label fw-semibold">Status</label>
                <select name="status" id="filter-status" class="unas-form-control">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filter-search" class="form-label fw-semibold">Cari</label>
                <input type="text" name="search" id="filter-search" class="unas-form-control" placeholder="Nama, email, atau telepon..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label for="filter-submit" class="form-label">&nbsp;</label>
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
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-people me-2"></i>Daftar Pengguna
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
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <label for="selectAll" class="visually-hidden">Pilih Semua</label>
                                <input type="checkbox" id="selectAll" class="form-check-input" aria-label="Pilih semua pengguna">
                            </th>
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
                                    <label for="user-checkbox-{{ $user->id }}" class="visually-hidden">Pilih {{ $user->name }}</label>
                                    <input type="checkbox" id="user-checkbox-{{ $user->id }}" class="form-check-input user-checkbox" value="{{ $user->id }}" aria-label="Pilih pengguna {{ $user->name }}">
                                </td>
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
                                        <button class="btn btn-outline-primary" onclick="showUserDetails({{ $user->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button class="btn btn-outline-info" onclick="toggleStatus({{ $user->id }}, {{ $user->is_active ? 'false' : 'true' }})">
                                                <i class="bi bi-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                            @if(!$user->hasRole('superadmin') || \App\Models\User::role('superadmin')->count() > 1)
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
function showUserDetails(userId) {
    // Show loading
    Swal.fire({
        title: 'Memuat data...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch user details
    fetch(`/admin/users/${userId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const user = data.user;

            // Create roles badges
            let rolesBadges = '';
            user.roles.forEach(role => {
                const badgeColor = getRoleBadgeColor(role.name);
                rolesBadges += `<span class="badge bg-${badgeColor} me-1">${role.name}</span>`;
            });

            // Create status badge
            const statusBadge = user.is_active
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-danger">Nonaktif</span>';

            // Format dates
            const joinDate = new Date(user.created_at).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const lastLogin = user.updated_at ? new Date(user.updated_at).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'Belum pernah login';

            // Show user details popup
            Swal.fire({
                title: `<div class="d-flex align-items-center">
                    <img src="${user.avatar_url}" class="rounded-circle me-3" width="50" height="50" alt="Avatar">
                    <div>
                        <h5 class="mb-0">${user.name}</h5>
                        <small class="text-muted">${user.email}</small>
                    </div>
                </div>`,
                html: `
                    <div class="text-start">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <i class="bi bi-person-badge text-primary me-2"></i>Role
                                        </h6>
                                        <div>${rolesBadges}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <i class="bi bi-check-circle text-success me-2"></i>Status
                                        </h6>
                                        <div>${statusBadge}</div>
                                    </div>
                                </div>
                            </div>
                            ${user.phone ? `
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <i class="bi bi-telephone text-info me-2"></i>Nomor Telepon
                                        </h6>
                                        <p class="mb-0">${user.phone}</p>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <i class="bi bi-calendar-plus text-warning me-2"></i>Bergabung
                                        </h6>
                                        <p class="mb-0">${joinDate}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">
                                            <i class="bi bi-clock text-secondary me-2"></i>Terakhir Update
                                        </h6>
                                        <p class="mb-0">${lastLogin}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `,
                width: '600px',
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'user-details-popup',
                    title: 'user-details-title'
                }
            });
        } else {
            showError('Gagal memuat data pengguna');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
}

function getRoleBadgeColor(roleName) {
    const colorMap = {
        'Super Admin': 'primary',
        'Admin': 'info',
        'Juri': 'warning',
        'Peserta': 'secondary'
    };
    return colorMap[roleName] || 'secondary';
}

function toggleStatus(userId, newStatus) {
    const action = newStatus ? 'mengaktifkan' : 'menonaktifkan';

    confirmAction(
        'Ubah Status Pengguna',
        `Apakah Anda yakin ingin ${action} pengguna ini?`,
        function() {
            // Show loading state
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang mengubah status pengguna',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get CSRF token with fallback
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                             document.querySelector('input[name="_token"]')?.value ||
                             '';

            if (!csrfToken) {
                showError('CSRF token tidak ditemukan. Silakan refresh halaman.');
                return;
            }

            fetch(`/admin/users/${userId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);

                if (data.success) {
                    showSuccess(`Pengguna berhasil ${action === 'mengaktifkan' ? 'diaktifkan' : 'dinonaktifkan'}`);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showError(data.message || 'Terjadi kesalahan dalam mengubah status pengguna');
                }
            })
            .catch(error => {
                console.error('Toggle status error:', error);
                showError(`Terjadi kesalahan sistem: ${error.message}`);
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
                    showSuccess('Pengguna berhasil dihapus');
                    location.reload();
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

// Mass action functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkActions();
});

document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkActions);
});

function toggleBulkActions() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');

    if (checkedBoxes.length > 0) {
        bulkActions.style.display = 'block';
    } else {
        bulkActions.style.display = 'none';
    }
}

function getSelectedUserIds() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    return Array.from(checkedBoxes).map(checkbox => checkbox.value);
}

function bulkActivate() {
    const userIds = getSelectedUserIds();
    if (userIds.length === 0) return;

    confirmAction(
        'Aktivasi Pengguna',
        `Apakah Anda yakin ingin mengaktivasi ${userIds.length} pengguna terpilih?`,
        function() {
            bulkUpdateStatus(userIds, true);
        }
    );
}

function bulkDeactivate() {
    const userIds = getSelectedUserIds();
    if (userIds.length === 0) return;

    confirmAction(
        'Nonaktifkan Pengguna',
        `Apakah Anda yakin ingin menonaktifkan ${userIds.length} pengguna terpilih?`,
        function() {
            bulkUpdateStatus(userIds, false);
        }
    );
}

function bulkDelete() {
    const userIds = getSelectedUserIds();
    if (userIds.length === 0) return;

    confirmAction(
        'Hapus Pengguna',
        `Apakah Anda yakin ingin menghapus ${userIds.length} pengguna terpilih? Tindakan ini tidak dapat dibatalkan.`,
        function() {
            bulkDeleteUsers(userIds);
        }
    );
}

function bulkUpdateStatus(userIds, isActive) {
    const action = isActive ? 'mengaktivasi' : 'menonaktifkan';

    Swal.fire({
        title: 'Memproses...',
        text: `Sedang ${action} pengguna`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    Promise.all(userIds.map(userId => {
        return fetch(`/admin/users/${userId}/toggle-status`, {
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
            showSuccess(`${userIds.length} pengguna berhasil ${isActive ? 'diaktivasi' : 'dinonaktifkan'}`);
            location.reload();
        } else {
            showError('Beberapa pengguna gagal diproses');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
}

function bulkDeleteUsers(userIds) {
    Swal.fire({
        title: 'Memproses...',
        text: 'Sedang menghapus pengguna',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    Promise.all(userIds.map(userId => {
        return fetch(`/admin/users/${userId}`, {
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
            showSuccess(`${userIds.length} pengguna berhasil dihapus`);
            location.reload();
        } else {
            showError('Beberapa pengguna gagal dihapus');
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan sistem');
    });
}
</script>

<style>
.user-details-popup {
    border-radius: 15px !important;
}

.user-details-title {
    padding: 1.5rem 1.5rem 0 1.5rem !important;
}

.user-details-popup .swal2-html-container {
    padding: 1rem 1.5rem 1.5rem 1.5rem !important;
}

.user-details-popup .card {
    transition: transform 0.2s ease;
}

.user-details-popup .card:hover {
    transform: translateY(-2px);
}
</style>
@endpush
