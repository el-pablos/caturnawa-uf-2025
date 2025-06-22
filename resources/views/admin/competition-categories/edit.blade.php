@extends('layouts.admin')

@section('title', 'Edit Kategori Kompetisi')
@section('page-title', 'Edit Kategori Kompetisi')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.competition-categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <a href="{{ route('admin.competition-categories.show', $competitionCategory) }}" class="btn btn-outline-info">
            <i class="bi bi-eye me-2"></i>Lihat Detail
        </a>
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-pencil me-2"></i>Form Edit Kategori
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.competition-categories.update', $competitionCategory) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $competitionCategory->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Urutan</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', $competitionCategory->sort_order) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $competitionCategory->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon (Bootstrap Icons)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i id="icon-preview" class="{{ old('icon', $competitionCategory->icon ?: 'bi-folder') }}"></i>
                                    </span>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $competitionCategory->icon) }}" 
                                           placeholder="bi-trophy, bi-code-slash, dll">
                                </div>
                                <small class="text-muted">
                                    Gunakan class Bootstrap Icons, contoh: bi-trophy, bi-code-slash
                                </small>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="color" class="form-label">Warna</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color', $competitionCategory->color) }}">
                                    <input type="text" class="form-control" id="color-text" 
                                           value="{{ old('color', $competitionCategory->color) }}" readonly>
                                </div>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', $competitionCategory->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktif
                            </label>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="mb-4">
                        <label class="form-label">Preview</label>
                        <div class="border rounded p-3 bg-light">
                            <div class="d-flex align-items-center">
                                <i id="preview-icon" class="{{ old('icon', $competitionCategory->icon ?: 'bi-folder') }} me-2" 
                                   style="color: {{ old('color', $competitionCategory->color) }}; font-size: 1.5rem;"></i>
                                <div>
                                    <strong id="preview-name">{{ old('name', $competitionCategory->name) }}</strong>
                                    <br><small class="text-muted" id="preview-description">{{ old('description', $competitionCategory->description ?: 'Deskripsi kategori') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Info -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Informasi Kategori</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small><strong>Slug:</strong> {{ $competitionCategory->slug }}</small><br>
                                <small><strong>Dibuat:</strong> {{ $competitionCategory->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <div class="col-md-6">
                                <small><strong>Kompetisi:</strong> {{ $competitionCategory->competitions()->count() }} kompetisi</small><br>
                                <small><strong>Diperbarui:</strong> {{ $competitionCategory->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.competition-categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Update Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const iconInput = document.getElementById('icon');
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('color-text');
    
    const previewName = document.getElementById('preview-name');
    const previewDescription = document.getElementById('preview-description');
    const previewIcon = document.getElementById('preview-icon');
    const iconPreview = document.getElementById('icon-preview');

    // Update preview on input change
    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'Nama Kategori';
    });

    descriptionInput.addEventListener('input', function() {
        previewDescription.textContent = this.value || 'Deskripsi kategori';
    });

    iconInput.addEventListener('input', function() {
        const iconClass = this.value || 'bi-folder';
        previewIcon.className = iconClass + ' me-2';
        previewIcon.style.color = colorInput.value;
        previewIcon.style.fontSize = '1.5rem';
        
        iconPreview.className = iconClass;
    });

    colorInput.addEventListener('input', function() {
        colorText.value = this.value;
        previewIcon.style.color = this.value;
    });

    // Initialize
    colorText.value = colorInput.value;
});
</script>
@endpush
