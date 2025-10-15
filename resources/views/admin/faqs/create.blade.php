@extends('layouts.admin')

@section('title', 'Create FAQ')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New FAQ</h1>
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('question') is-invalid @enderror" 
                           id="question" name="question" value="{{ old('question') }}" 
                           placeholder="Enter FAQ question" required maxlength="500">
                    @error('question')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="answer" class="form-label">Answer <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('answer') is-invalid @enderror" 
                              id="answer" name="answer" rows="5" 
                              placeholder="Enter FAQ answer" required maxlength="2000">{{ old('answer') }}</textarea>
                    @error('answer')
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
                        <i class="bi bi-save"></i> Create FAQ
                    </button>
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

