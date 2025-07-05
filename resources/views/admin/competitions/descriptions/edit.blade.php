@extends('layouts.admin')

@section('title', 'Edit Deskripsi - ' . $competition->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Deskripsi</h1>
            <p class="text-muted">{{ $competition->name }} - {{ $description->title }}</p>
        </div>
        <a href="{{ route('admin.competitions.descriptions.index', $competition) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Form Edit Deskripsi
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.competitions.descriptions.update', [$competition, $description]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="section" class="form-label">Bagian <span class="text-danger">*</span></label>
                                <select class="form-select @error('section') is-invalid @enderror" id="section" name="section" required>
                                    <option value="">Pilih Bagian</option>
                                    @foreach($sections as $key => $label)
                                        <option value="{{ $key }}" {{ old('section', $description->section) === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('section')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="order" class="form-label">Urutan</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                       id="order" name="order" value="{{ old('order', $description->order) }}" min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Urutan tampilan (0 = paling atas)</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $description->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="15" required>{{ old('content', $description->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Mendukung HTML untuk formatting</small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', $description->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                            <small class="form-text text-muted">Centang untuk menampilkan deskripsi ini</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.competitions.descriptions.index', $competition) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>{{ $description->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @if($description->creator)
                        <tr>
                            <td><strong>Oleh:</strong></td>
                            <td>{{ $description->creator->name }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Diperbarui:</strong></td>
                            <td>{{ $description->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @if($description->updater)
                        <tr>
                            <td><strong>Oleh:</strong></td>
                            <td>{{ $description->updater->name }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Preview
                    </h6>
                </div>
                <div class="card-body">
                    <div id="preview" class="border p-3" style="max-height: 300px; overflow-y: auto;">
                        {!! $description->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('content');
    const preview = document.getElementById('preview');
    
    // Auto-resize textarea
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
        
        // Update preview
        preview.innerHTML = this.value;
    });
});
</script>
@endsection
