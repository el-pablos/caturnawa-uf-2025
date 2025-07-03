@extends('layouts.admin')

@section('title', 'Detail Role')

@section('page-title', 'Detail Role')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
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
                    <i class="bi bi-shield-check me-2"></i>{{ $role->name }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="bi bi-tag me-2"></i>Nama Role</h6>
                        <p class="text-muted">{{ $role->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-shield me-2"></i>Guard Name</h6>
                        <p class="text-muted">{{ $role->guard_name }}</p>
                    </div>
                </div>
                
                @if($role->description)
                <div class="mb-4">
                    <h6><i class="bi bi-file-text me-2"></i>Deskripsi</h6>
                    <p class="text-muted">{{ $role->description }}</p>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-clock me-2"></i>Dibuat</h6>
                        <p class="text-muted">{{ $role->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-clock-history me-2"></i>Terakhir Update</h6>
                        <p class="text-muted">{{ $role->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($role->permissions->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="bi bi-key me-2"></i>Permissions ({{ $role->permissions->count() }})
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($role->permissions as $permission)
                    <div class="col-md-6 col-lg-4 mb-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <span>{{ $permission->name }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        @if($role->users->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-people me-2"></i>Pengguna dengan Role Ini ({{ $role->users->count() }})
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($role->users()->latest()->take(10)->get() as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 32px; height: 32px;">
                                                <span class="text-white fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'inactive' ? 'secondary' : 'warning') }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($role->users->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.users.index', ['role' => $role->name]) }}" class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i>Lihat Semua Pengguna ({{ $role->users->count() }})
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Edit Role
                    </a>
                    
                    @if($role->users->count() > 0)
                        <a href="{{ route('admin.users.index', ['role' => $role->name]) }}" class="btn btn-info">
                            <i class="bi bi-people me-2"></i>Lihat Pengguna
                        </a>
                    @endif
                    
                    @if(!in_array($role->name, ['Super Admin', 'Admin', 'Juri', 'Peserta']))
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i>Hapus Role
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Statistik
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $role->users->count() }}</h4>
                            <small class="text-muted">Total Pengguna</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">{{ $role->permissions->count() }}</h4>
                        <small class="text-muted">Permissions</small>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Pengguna Aktif</small>
                        <small>{{ $role->users->where('status', 'active')->count() }}/{{ $role->users->count() }}</small>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             style="width: {{ $role->users->count() > 0 ? ($role->users->where('status', 'active')->count() / $role->users->count()) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if(!in_array($role->name, ['Super Admin', 'Admin', 'Juri', 'Peserta']))
        <div class="card mt-3">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>Zona Bahaya
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Role ini dapat dihapus karena bukan role sistem default.</p>
                <div class="alert alert-warning">
                    <small>
                        <i class="bi bi-info-circle me-1"></i>
                        Menghapus role akan menghapus semua permissions yang terkait dan menghapus role dari semua pengguna.
                    </small>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@if(!in_array($role->name, ['Super Admin', 'Admin', 'Juri', 'Peserta']))
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus role <strong>{{ $role->name }}</strong>?</p>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Tindakan ini tidak dapat dibatalkan dan akan:
                    <ul class="mb-0 mt-2">
                        <li>Menghapus role dari {{ $role->users->count() }} pengguna</li>
                        <li>Menghapus {{ $role->permissions->count() }} permissions terkait</li>
                        <li>Tidak dapat dikembalikan</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
