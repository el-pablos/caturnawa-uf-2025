@extends('layouts.admin')

@section('title', 'Edit Terms & Conditions')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Terms & Conditions</h1>
        <a href="{{ route('admin.terms-and-conditions.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.terms-and-conditions.update', $termsAndCondition) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $termsAndCondition->title) }}" 
                           placeholder="e.g., Acceptance of Terms, Registration Requirements" required maxlength="255">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">Select type...</option>
                        <option value="general" {{ old('type', $termsAndCondition->type) === 'general' ? 'selected' : '' }}>General</option>
                        <option value="competition" {{ old('type', $termsAndCondition->type) === 'competition' ? 'selected' : '' }}>Competition</option>
                        <option value="privacy" {{ old('type', $termsAndCondition->type) === 'privacy' ? 'selected' : '' }}>Privacy</option>
                        <option value="payment" {{ old('type', $termsAndCondition->type) === 'payment' ? 'selected' : '' }}>Payment</option>
                    </select>
                    <small class="form-text text-muted">
                        <strong>General:</strong> Platform usage terms | 
                        <strong>Competition:</strong> Competition rules | 
                        <strong>Privacy:</strong> Data protection | 
                        <strong>Payment:</strong> Financial terms
                    </small>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" name="content" rows="15" 
                              placeholder="Enter the full terms and conditions content..." required>{{ old('content', $termsAndCondition->content) }}</textarea>
                    <small class="form-text text-muted">
                        You can use line breaks for formatting. HTML is not supported for security reasons.
                    </small>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="order" class="form-label">Display Order <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                           id="order" name="order" value="{{ old('order', $termsAndCondition->order) }}" 
                           min="0" required>
                    <small class="form-text text-muted">Lower numbers appear first on the terms page</small>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                           {{ old('is_active', $termsAndCondition->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active (visible on public terms page)
                    </label>
                </div>

                <hr class="my-4">

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> Changes to terms and conditions will be immediately visible to all users.
                    Make sure to review your changes carefully before saving.
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="bi bi-clock-history me-1"></i>
                        Last updated: {{ $termsAndCondition->updated_at->format('F d, Y H:i:s') }}
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Terms & Conditions
                        </button>
                        <a href="{{ route('admin.terms-and-conditions.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

