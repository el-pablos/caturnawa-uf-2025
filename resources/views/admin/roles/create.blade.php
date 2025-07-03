@extends('layouts.admin')

@section('title', 'Tambah Role')

@section('page-title', 'Tambah Role')

@section('header-actions')
    <div class="d-flex gap-2">
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
                    <i class="bi bi-plus-circle me-2"></i>Informasi Role
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Role <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        <div class="form-text">Contoh: Admin, Juri, Peserta</div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="guard_name" class="form-label">Guard Name <span class="text-danger">*</span></label>
                        <select class="form-select @error('guard_name') is-invalid @enderror" id="guard_name" name="guard_name" required>
                            <option value="web" {{ old('guard_name') == 'web' ? 'selected' : '' }}>Web</option>
                            <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>API</option>
                        </select>
                        @error('guard_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        <div class="form-text">Deskripsi singkat tentang role ini</div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if(isset($permissions) && count($permissions) > 0)
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-6 col-lg-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="permission_{{ $permission->id }}" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
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
                            <i class="bi bi-check-circle me-2"></i>Simpan Role
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
                    <i class="bi bi-info-circle me-2"></i>Panduan Role
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-lightbulb me-2"></i>Tips Membuat Role</h6>
                    <ul class="mb-0">
                        <li>Gunakan nama yang jelas dan mudah dipahami</li>
                        <li>Pilih guard yang sesuai dengan kebutuhan</li>
                        <li>Berikan deskripsi yang menjelaskan fungsi role</li>
                        <li>Pilih permissions yang sesuai dengan tanggung jawab</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Perhatian</h6>
                    <ul class="mb-0">
                        <li>Role yang sudah dibuat tidak dapat dihapus jika masih digunakan</li>
                        <li>Pastikan nama role unik</li>
                        <li>Guard "web" untuk akses website</li>
                        <li>Guard "api" untuk akses API</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-list me-2"></i>Role Default
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <span class="badge bg-danger me-2">Super Admin</span>
                        Akses penuh sistem
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-primary me-2">Admin</span>
                        Mengelola kompetisi dan peserta
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-success me-2">Juri</span>
                        Menilai karya peserta
                    </li>
                    <li class="mb-2">
                        <span class="badge bg-info me-2">Peserta</span>
                        Mengikuti kompetisi
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate guard name based on role name
    const nameInput = document.getElementById('name');
    const guardSelect = document.getElementById('guard_name');
    
    nameInput.addEventListener('input', function() {
        const name = this.value.toLowerCase();
        if (name.includes('api') || name.includes('service')) {
            guardSelect.value = 'api';
        } else {
            guardSelect.value = 'web';
        }
    });
    
    // Select all permissions checkbox
    const selectAllBtn = document.createElement('button');
    selectAllBtn.type = 'button';
    selectAllBtn.className = 'btn btn-sm btn-outline-primary mb-2';
    selectAllBtn.innerHTML = '<i class="bi bi-check-all me-1"></i>Pilih Semua';
    
    const permissionsLabel = document.querySelector('label[for="permissions"]');
    if (permissionsLabel) {
        permissionsLabel.parentNode.insertBefore(selectAllBtn, permissionsLabel.nextSibling);
        
        selectAllBtn.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.innerHTML = allChecked ? 
                '<i class="bi bi-check-all me-1"></i>Pilih Semua' : 
                '<i class="bi bi-x-square me-1"></i>Batal Pilih';
        });
    }
});
</script>
@endpush
