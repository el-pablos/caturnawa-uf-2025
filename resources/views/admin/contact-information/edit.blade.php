@extends('layouts.admin')

@section('title', 'Edit Contact Information')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Contact Information</h1>
        <a href="{{ route('admin.contact-information.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.contact-information.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3 text-primary">Contact Details</h5>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $contact->email) }}" 
                                   placeholder="contact@example.com" required maxlength="255">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp" class="form-label">WhatsApp Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" 
                                   id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $contact->whatsapp) }}" 
                                   placeholder="+62 812-3456-7890" required maxlength="20">
                            <small class="form-text text-muted">Include country code (e.g., +62 for Indonesia)</small>
                            @error('whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Physical Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="4" 
                                      placeholder="Enter complete address" required maxlength="500">{{ old('address', $contact->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3 text-primary">Social Media</h5>
                        
                        <div class="mb-3">
                            <label for="instagram" class="form-label">Instagram Username</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control @error('instagram') is-invalid @enderror" 
                                       id="instagram" name="instagram" value="{{ old('instagram', ltrim($contact->instagram ?? '', '@')) }}" 
                                       placeholder="username" maxlength="100">
                            </div>
                            <small class="form-text text-muted">Enter username without @ symbol</small>
                            @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tiktok" class="form-label">TikTok Username</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control @error('tiktok') is-invalid @enderror" 
                                       id="tiktok" name="tiktok" value="{{ old('tiktok', ltrim($contact->tiktok ?? '', '@')) }}" 
                                       placeholder="username" maxlength="100">
                            </div>
                            <small class="form-text text-muted">Enter username without @ symbol</small>
                            @error('tiktok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="youtube" class="form-label">YouTube Channel URL</label>
                            <input type="url" class="form-control @error('youtube') is-invalid @enderror" 
                                   id="youtube" name="youtube" value="{{ old('youtube', $contact->youtube) }}" 
                                   placeholder="https://youtube.com/@channel" maxlength="100">
                            @error('youtube')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                   {{ old('is_active', $contact->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (visible on public pages)
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Contact Information
                    </button>
                    <a href="{{ route('admin.contact-information.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

