@extends('layouts.admin')

@section('title', 'Tambah Kompetisi')

@section('page-title', 'Tambah Kompetisi')



@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.competitions.index') }}" class="btn btn-secondary">
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
                    <i class="bi bi-plus-circle me-2"></i>Informasi Kompetisi
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.competitions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Kompetisi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="programming" {{ old('category') == 'programming' ? 'selected' : '' }}>Programming</option>
                                <option value="design" {{ old('category') == 'design' ? 'selected' : '' }}>Design</option>
                                <option value="business" {{ old('category') == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="essay" {{ old('category') == 'essay' ? 'selected' : '' }}>Essay</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Harga Pendaftaran <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" min="0" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="early_bird_price" class="form-label">Harga Early Bird</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('early_bird_price') is-invalid @enderror" 
                                       id="early_bird_price" name="early_bird_price" value="{{ old('early_bird_price') }}" min="0">
                            </div>
                            @error('early_bird_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="registration_start" class="form-label">Mulai Pendaftaran <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('registration_start') is-invalid @enderror" 
                                   id="registration_start" name="registration_start" value="{{ old('registration_start') }}" required>
                            @error('registration_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="registration_end" class="form-label">Akhir Pendaftaran <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('registration_end') is-invalid @enderror" 
                                   id="registration_end" name="registration_end" value="{{ old('registration_end') }}" required>
                            @error('registration_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="max_participants" class="form-label">Maksimal Peserta</label>
                            <input type="number" class="form-control @error('max_participants') is-invalid @enderror" 
                                   id="max_participants" name="max_participants" value="{{ old('max_participants') }}" min="1">
                            <div class="form-text">Kosongkan jika tidak ada batasan</div>
                            @error('max_participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Kompetisi</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.competitions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Kompetisi
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
                    <i class="bi bi-info-circle me-2"></i>Panduan Pengolahan Kompetisi
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-lightbulb me-2"></i>Tips Pengelolaan Kompetisi</h6>
                    <ul class="mb-0">
                        <li>Pastikan nama kompetisi jelas dan menarik</li>
                        <li>Tentukan kategori yang sesuai dengan target peserta</li>
                        <li>Atur periode pendaftaran dengan waktu yang cukup</li>
                        <li>Tentukan harga yang kompetitif</li>
                        <li>Gunakan gambar yang menarik untuk promosi</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Perhatian</h6>
                    <ul class="mb-0">
                        <li>Status "Draft" tidak akan terlihat oleh peserta</li>
                        <li>Pastikan tanggal pendaftaran sudah benar</li>
                        <li>Harga early bird harus lebih rendah dari harga normal</li>
                        <li>Maksimal peserta dapat dibatasi sesuai kebutuhan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validate early bird price
    const priceInput = document.getElementById('price');
    const earlyBirdInput = document.getElementById('early_bird_price');
    
    function validateEarlyBird() {
        const price = parseFloat(priceInput.value) || 0;
        const earlyBird = parseFloat(earlyBirdInput.value) || 0;
        
        if (earlyBird > 0 && earlyBird >= price) {
            earlyBirdInput.setCustomValidity('Harga early bird harus lebih rendah dari harga normal');
        } else {
            earlyBirdInput.setCustomValidity('');
        }
    }
    
    priceInput.addEventListener('input', validateEarlyBird);
    earlyBirdInput.addEventListener('input', validateEarlyBird);
    
    // Validate registration dates
    const startInput = document.getElementById('registration_start');
    const endInput = document.getElementById('registration_end');
    
    function validateDates() {
        const start = new Date(startInput.value);
        const end = new Date(endInput.value);
        
        if (start && end && end <= start) {
            endInput.setCustomValidity('Tanggal akhir harus setelah tanggal mulai');
        } else {
            endInput.setCustomValidity('');
        }
    }
    
    startInput.addEventListener('change', validateDates);
    endInput.addEventListener('change', validateDates);
});
</script>
@endpush
