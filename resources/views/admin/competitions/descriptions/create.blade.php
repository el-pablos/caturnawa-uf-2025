@extends('layouts.admin')

@section('title', 'Tambah Deskripsi - ' . $competition->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Tambah Deskripsi</h1>
            <p class="text-muted">{{ $competition->name }}</p>
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
                        <i class="fas fa-plus me-2"></i>Form Tambah Deskripsi
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.competitions.descriptions.store', $competition) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="section" class="form-label">Bagian <span class="text-danger">*</span></label>
                                <select class="form-select @error('section') is-invalid @enderror" id="section" name="section" required>
                                    <option value="">Pilih Bagian</option>
                                    @foreach($sections as $key => $label)
                                        <option value="{{ $key }}" {{ old('section') === $key ? 'selected' : '' }}>
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
                                       id="order" name="order" value="{{ old('order', 0) }}" min="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Urutan tampilan (0 = paling atas)</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="15" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Mendukung HTML untuk formatting</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.competitions.descriptions.index', $competition) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan
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
                        <i class="fas fa-info-circle me-2"></i>Panduan
                    </h6>
                </div>
                <div class="card-body">
                    <h6>Bagian yang Tersedia:</h6>
                    <ul class="list-unstyled">
                        <li><strong>Deskripsi Utama:</strong> Penjelasan umum kompetisi</li>
                        <li><strong>Peraturan:</strong> Aturan dan ketentuan</li>
                        <li><strong>Hadiah:</strong> Informasi hadiah</li>
                        <li><strong>Persyaratan:</strong> Syarat peserta</li>
                        <li><strong>Timeline:</strong> Jadwal kegiatan</li>
                        <li><strong>FAQ:</strong> Pertanyaan umum</li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Tips HTML:</h6>
                    <ul class="small">
                        <li><code>&lt;h3&gt;</code> untuk judul besar</li>
                        <li><code>&lt;p&gt;</code> untuk paragraf</li>
                        <li><code>&lt;ul&gt;&lt;li&gt;</code> untuk list</li>
                        <li><code>&lt;strong&gt;</code> untuk teks tebal</li>
                        <li><code>&lt;em&gt;</code> untuk teks miring</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize rich text editor if available
document.addEventListener('DOMContentLoaded', function() {
    // You can integrate CKEditor, TinyMCE, or other rich text editors here
    const textarea = document.getElementById('content');
    
    // Simple auto-resize
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});
</script>
@endsection
