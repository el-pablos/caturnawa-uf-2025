@extends('layouts.admin')

@section('title', 'Create Sponsor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Sponsor</h1>
        <a href="{{ route('admin.sponsors.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.sponsors.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Sponsor Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" 
                           placeholder="Enter sponsor name" required maxlength="255">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label">Logo</label>
                    <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                           id="logo" name="logo" accept="image/*">
                    <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF, SVG. Max size: 2MB</small>
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="website" class="form-label">Website URL</label>
                    <input type="url" class="form-control @error('website') is-invalid @enderror" 
                           id="website" name="website" value="{{ old('website') }}" 
                           placeholder="https://example.com" maxlength="255">
                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Sponsor Type <span class="text-danger">*</span></label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">Select type...</option>
                        <option value="platinum" {{ old('type') === 'platinum' ? 'selected' : '' }}>Platinum</option>
                        <option value="gold" {{ old('type') === 'gold' ? 'selected' : '' }}>Gold</option>
                        <option value="silver" {{ old('type') === 'silver' ? 'selected' : '' }}>Silver</option>
                        <option value="bronze" {{ old('type') === 'bronze' ? 'selected' : '' }}>Bronze</option>
                        <option value="media_partner" {{ old('type') === 'media_partner' ? 'selected' : '' }}>Media Partner</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="order" class="form-label">Display Order <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                           id="order" name="order" value="{{ old('order', 0) }}" 
                           min="0" required>
                    <small class="form-text text-muted">Lower numbers appear first</small>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active (visible on public page)
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Create Sponsor
                    </button>
                    <a href="{{ route('admin.sponsors.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

