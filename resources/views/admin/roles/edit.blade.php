@extends('layouts.admin')

@section('title', 'Edit Role')

@section('page-title', 'Edit Role')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-info">
            <i class="bi bi-eye me-2"></i>Lihat Detail
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
                    <i class="bi bi-pencil-square me-2"></i>Edit Informasi Role
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Role <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $role->name) }}" 
                               {{ in_array($role->name, ['Super Admin', 'Admin', 'Juri', 'Peserta']) ? 'readonly' : 'required' }}>
                        @if(in_array($role->name, ['Super Admin', 'Admin', 'Juri', 'Peserta']))
                            <div class="form-text text-warning">
                                <i class="bi bi-lock me-1"></i>Nama role sistem tidak dapat diubah
                            </div>
                        @else
                            <div class="form-text">Contoh: Admin, Juri, Peserta</div>
                        @endif
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="guard_name" class="form-label">Guard Name <span class="text-danger">*</span></label>
                        <select class="form-select @error('guard_name') is-invalid @enderror" id="guard_name" name="guard_name" 
                                {{ in_array($role->name, ['Super Admin', 'Admin', 'Juri', 'Peserta']) ? 'disabled' : 'required' }}>
                            <option value="web" {{ old('guard_name', $role->guard_name) == 'web' ? 'selected' : '' }}>Web</option>
                            <option value="api" {{ old('guard_name', $role->guard_name) == 'api' ? 'selected' : '' }}>API</option>
                        </select>
                        @if(in_array($role->name, ['Super Admin', 'Admin', 'Juri', 'Peserta']))
                            <input type="hidden" name="guard_name" value="{{ $role->guard_name }}">
                            <div class="form-text text-warning">
                                <i class="bi bi-lock me-1"></i>Guard name role sistem tidak dapat diubah
                            </div>
                        @endif
                        @error('guard_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $role->description) }}</textarea>
                        <div class="form-text">Deskripsi singkat tentang role ini</div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if(isset($permissions) && count($permissions) > 0)
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="mb-2">
                            <button type="button" id="selectAllBtn" class="btn btn-sm btn-outline-primary me-2">
                                <i class="bi bi-check-all me-1"></i>Pilih Semua
                            </button>
                            <button type="button" id="deselectAllBtn" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-x-square me-1"></i>Batal Pilih
                            </button>
                        </div>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-6 col-lg-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="permission_{{ $permission->id }}" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Role
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>ID:</strong> {{ $role->id }}
                </div>
                <div class="mb-3">
                    <strong>Dibuat:</strong> {{ $role->created_at->format('d M Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Terakhir Update:</strong> {{ $role->updated_at->format('d M Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Total Pengguna:</strong> {{ $role->users->count() }}
                </div>
                <div class="mb-3">
                    <strong>Total Permissions:</strong> {{ $role->permissions->count() }}
                </div>
            </div>
        </div>
        
        @if(in_array($role->name, ['Super Admin', 'Admin', 'Juri', 'Peserta']))
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-shield-lock me-2"></i>Role Sistem
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Role Sistem Terlindungi</strong>
                    <ul class="mb-0 mt-2">
                        <li>Nama dan guard name tidak dapat diubah</li>
                        <li>Role tidak dapat dihapus</li>
                        <li>Hanya permissions yang dapat dimodifikasi</li>
                        <li>Diperlukan untuk fungsi sistem</li>
                    </ul>
                </div>
            </div>
        </div>
        @else
        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>Tips Edit Role
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Pastikan nama role tetap unik</li>
                    <li>Pilih permissions yang sesuai dengan tanggung jawab</li>
                    <li>Perubahan akan mempengaruhi semua pengguna dengan role ini</li>
                    <li>Backup data sebelum melakukan perubahan besar</li>
                </ul>
            </div>
        </div>
        @endif
        
        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-people me-2"></i>Pengguna Terdampak
                </h6>
            </div>
            <div class="card-body">
                @if($role->users->count() > 0)
                    <p class="text-muted mb-2">Perubahan akan mempengaruhi {{ $role->users->count() }} pengguna:</p>
                    <ul class="list-unstyled">
                        @foreach($role->users()->take(5)->get() as $user)
                        <li class="mb-1">
                            <i class="bi bi-person me-1"></i>{{ $user->name }}
                        </li>
                        @endforeach
                        @if($role->users->count() > 5)
                        <li class="text-muted">
                            <i class="bi bi-three-dots me-1"></i>dan {{ $role->users->count() - 5 }} lainnya
                        </li>
                        @endif
                    </ul>
                @else
                    <p class="text-muted mb-0">Belum ada pengguna dengan role ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    
    if (selectAllBtn && deselectAllBtn && checkboxes.length > 0) {
        selectAllBtn.addEventListener('click', function() {
            checkboxes.forEach(cb => cb.checked = true);
            updateButtonStates();
        });
        
        deselectAllBtn.addEventListener('click', function() {
            checkboxes.forEach(cb => cb.checked = false);
            updateButtonStates();
        });
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateButtonStates);
        });
        
        function updateButtonStates() {
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            const totalCount = checkboxes.length;
            
            selectAllBtn.disabled = checkedCount === totalCount;
            deselectAllBtn.disabled = checkedCount === 0;
            
            // Update button text
            selectAllBtn.innerHTML = checkedCount === totalCount ? 
                '<i class="bi bi-check-all me-1"></i>Semua Terpilih' : 
                '<i class="bi bi-check-all me-1"></i>Pilih Semua';
                
            deselectAllBtn.innerHTML = checkedCount === 0 ? 
                '<i class="bi bi-x-square me-1"></i>Tidak Ada Terpilih' : 
                '<i class="bi bi-x-square me-1"></i>Batal Pilih';
        }
        
        // Initial state
        updateButtonStates();
    }
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const checkedPermissions = document.querySelectorAll('input[name="permissions[]"]:checked');
        if (checkedPermissions.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu permission untuk role ini.');
            return false;
        }
    });
});
</script>
@endpush
