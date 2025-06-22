@extends('layouts.app')

@section('title', 'Kelola Role')

@section('page-title', 'Kelola Role')

@section('sidebar-menu')
    <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a class="nav-link" href="{{ route('admin.competitions.index') }}">
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
    <a class="nav-link active" href="{{ route('admin.roles.index') }}">
        <i class="bi bi-person-vcard me-2"></i>Kelola Role
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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Role
        </button>
    </div>
@endsection

@section('content')
<!-- Roles Table -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">
            <i class="bi bi-person-vcard me-2"></i>Daftar Role & Permissions
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="rolesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Role</th>
                        <th>Permissions</th>
                        <th>Jumlah User</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            </td>
                            <td>
                                @if($role->permissions->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($role->permissions->take(3) as $permission)
                                            <span class="badge bg-secondary">{{ $permission->name }}</span>
                                        @endforeach
                                        @if($role->permissions->count() > 3)
                                            <span class="badge bg-info">+{{ $role->permissions->count() - 3 }} lainnya</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">Tidak ada permission</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $role->users_count ?? 0 }} user</span>
                            </td>
                            <td>{{ $role->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-info" 
                                            onclick="viewRole({{ $role->id }})" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" 
                                            onclick="editRole({{ $role->id }})" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @if(!in_array($role->name, ['Super Admin', 'Admin', 'Juri', 'Peserta']))
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="deleteRole({{ $role->id }})" title="Hapus">
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
    </div>
</div>

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Role Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Role <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            @foreach($permissions->groupBy(function($permission) {
                                return explode('.', $permission->name)[0];
                            }) as $group => $groupPermissions)
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-primary">{{ ucfirst($group) }}</h6>
                                    @foreach($groupPermissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="permissions[]" value="{{ $permission->id }}" 
                                                   id="permission_{{ $permission->id }}">
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Simpan Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2"></i>Edit Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Role <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row" id="editPermissionsContainer">
                            <!-- Permissions will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle me-2"></i>Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Role Modal -->
<div class="modal fade" id="viewRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Detail Role
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewRoleContent">
                <!-- Role details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Hapus Role
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus role ini?</p>
                <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteRoleForm" method="POST" style="display: inline;">
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
    $('#rolesTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [5] }
        ]
    });
});

function viewRole(roleId) {
    fetch(`/admin/roles/${roleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('viewRoleContent').innerHTML = data.html;
                new bootstrap.Modal(document.getElementById('viewRoleModal')).show();
            }
        })
        .catch(error => console.error('Error:', error));
}

function editRole(roleId) {
    fetch(`/admin/roles/${roleId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('edit_name').value = data.role.name;
                document.getElementById('editRoleForm').action = `/admin/roles/${roleId}`;
                document.getElementById('editPermissionsContainer').innerHTML = data.permissionsHtml;
                new bootstrap.Modal(document.getElementById('editRoleModal')).show();
            }
        })
        .catch(error => console.error('Error:', error));
}

function deleteRole(roleId) {
    document.getElementById('deleteRoleForm').action = `/admin/roles/${roleId}`;
    new bootstrap.Modal(document.getElementById('deleteRoleModal')).show();
}
</script>
@endpush
