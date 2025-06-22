@extends('layouts.admin')

@section('title', 'Edit Kompetisi')

@section('page-title', 'Edit Kompetisi')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.competitions.show', $competition) }}" class="btn btn-info">
            <i class="bi bi-eye me-2"></i>Lihat Detail
        </a>
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
                    <i class="bi bi-pencil-square me-2"></i>Edit Informasi Kompetisi
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.competitions.update', $competition) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Kompetisi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $competition->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="programming" {{ old('category', $competition->category) == 'programming' ? 'selected' : '' }}>Programming</option>
                                <option value="design" {{ old('category', $competition->category) == 'design' ? 'selected' : '' }}>Design</option>
                                <option value="business" {{ old('category', $competition->category) == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="essay" {{ old('category', $competition->category) == 'essay' ? 'selected' : '' }}>Essay</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description', $competition->description) }}</textarea>
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
                                       id="price" name="price" value="{{ old('price', $competition->price) }}" min="0" required>
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
                                       id="early_bird_price" name="early_bird_price" value="{{ old('early_bird_price', $competition->early_bird_price) }}" min="0">
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
                                   id="registration_start" name="registration_start" 
                                   value="{{ old('registration_start', $competition->registration_start?->format('Y-m-d\TH:i')) }}" required>
                            @error('registration_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="registration_end" class="form-label">Akhir Pendaftaran <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('registration_end') is-invalid @enderror" 
                                   id="registration_end" name="registration_end" 
                                   value="{{ old('registration_end', $competition->registration_end?->format('Y-m-d\TH:i')) }}" required>
                            @error('registration_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="max_participants" class="form-label">Maksimal Peserta</label>
                            <input type="number" class="form-control @error('max_participants') is-invalid @enderror" 
                                   id="max_participants" name="max_participants" value="{{ old('max_participants', $competition->max_participants) }}" min="1">
                            <div class="form-text">Kosongkan jika tidak ada batasan</div>
                            @error('max_participants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" {{ old('status', $competition->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $competition->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $competition->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
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
                        
                        @if($competition->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $competition->image) }}" 
                                     alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                                <small class="text-muted d-block">Gambar saat ini</small>
                            </div>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.competitions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Kompetisi
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
                    <i class="bi bi-info-circle me-2"></i>Informasi Kompetisi
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>ID:</strong> {{ $competition->id }}
                </div>
                <div class="mb-3">
                    <strong>Dibuat:</strong> {{ $competition->created_at->format('d M Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Terakhir Update:</strong> {{ $competition->updated_at->format('d M Y H:i') }}
                </div>
                <div class="mb-3">
                    <strong>Total Peserta:</strong> {{ $competition->registrations_count ?? 0 }}
                </div>
                <div class="mb-3">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $competition->status === 'active' ? 'success' : ($competition->status === 'draft' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($competition->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>Perhatian
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Perubahan harga akan mempengaruhi pendaftaran baru</li>
                    <li>Mengubah tanggal dapat mempengaruhi peserta yang sudah daftar</li>
                    <li>Status "Draft" tidak akan terlihat oleh peserta</li>
                    <li>Pastikan semua informasi sudah benar sebelum mengaktifkan</li>
                </ul>
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
