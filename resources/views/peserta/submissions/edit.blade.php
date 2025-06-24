@extends('layouts.peserta')

@section('title', 'Edit Karya')
@section('page-title', 'Edit Karya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Edit Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Karya: {{ $submission->title }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('peserta.submissions.update', $submission) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Karya <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $submission->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Karya <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="6" required>{{ old('description', $submission->description) }}</textarea>
                            <div class="form-text">Jelaskan karya Anda secara detail, termasuk problem yang diselesaikan dan solusi yang ditawarkan.</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="files" class="form-label">Tambah File Karya</label>
                            <input type="file" class="form-control @error('files.*') is-invalid @enderror" 
                                   id="files" name="files[]" multiple>
                            <div class="form-text">
                                Format yang diterima: PDF, DOC, DOCX, ZIP, RAR (Maksimal 10MB per file). Anda dapat memilih beberapa file sekaligus.
                            </div>
                            @error('files.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="preview_image" class="form-label">Gambar Preview</label>
                            <input type="file" class="form-control @error('preview_image') is-invalid @enderror" 
                                   id="preview_image" name="preview_image" accept="image/*">
                            <div class="form-text">Upload gambar yang merepresentasikan karya Anda (JPG, PNG, GIF - Maksimal 2MB)</div>
                            @error('preview_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="video_url" class="form-label">Link Video Demo</label>
                            <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                                   id="video_url" name="video_url" value="{{ old('video_url', $submission->video_url) }}" 
                                   placeholder="https://youtube.com/watch?v=...">
                            <div class="form-text">Link video demo karya (YouTube, Vimeo, dll)</div>
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="repository_url" class="form-label">Link Repository</label>
                            <input type="url" class="form-control @error('repository_url') is-invalid @enderror" 
                                   id="repository_url" name="repository_url" value="{{ old('repository_url', $submission->repository_url) }}" 
                                   placeholder="https://github.com/username/repo">
                            <div class="form-text">Link repository code (GitHub, GitLab, dll)</div>
                            @error('repository_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Update Karya
                            </button>
                            <a href="{{ route('peserta.submissions.show', $submission) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Current Files -->
            @if($submission->files && count($submission->files) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-files me-2"></i>File Saat Ini
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($submission->files as $file)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div>
                            <strong>{{ $file['original_name'] }}</strong>
                            <br><small class="text-muted">{{ number_format($file['size'] / 1024, 2) }} KB</small>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ asset('storage/' . $file['path']) }}" class="btn btn-outline-primary" target="_blank">
                                <i class="bi bi-download"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger" onclick="deleteFile('{{ $file['filename'] }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Current Preview -->
            @if($submission->preview_image)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-image me-2"></i>Preview Saat Ini
                    </h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('storage/' . $submission->preview_image) }}" 
                         alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                </div>
            </div>
            @endif
            
            <!-- Submission Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informasi Submission
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Kompetisi:</strong><br>
                        {{ $submission->registration->competition->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Status:</strong><br>
                        @if($submission->status === 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @elseif($submission->status === 'submitted')
                            <span class="badge bg-primary">Submitted</span>
                        @elseif($submission->status === 'under_review')
                            <span class="badge bg-warning">Under Review</span>
                        @elseif($submission->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($submission->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <strong>Dibuat:</strong><br>
                        {{ $submission->created_at->format('d M Y H:i') }}
                    </div>
                    @if($submission->submitted_at)
                    <div class="mb-2">
                        <strong>Disubmit:</strong><br>
                        {{ $submission->submitted_at->format('d M Y H:i') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Define base URL for file deletion
const deleteFileBaseUrl = '{{ url("/peserta/submissions/" . $submission->id . "/file") }}';

document.addEventListener('DOMContentLoaded', function() {
    // File size validation
    const fileInput = document.getElementById('files');
    const previewInput = document.getElementById('preview_image');
    
    function validateFileSize(input, maxSize, unit = 'MB') {
        input.addEventListener('change', function() {
            const files = this.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileSize = file.size / (1024 * 1024); // Convert to MB
                if (fileSize > maxSize) {
                    alert(`File "${file.name}" terlalu besar! Maksimal ${maxSize}${unit}`);
                    this.value = '';
                    return;
                }
            }
        });
    }
    
    validateFileSize(fileInput, 10);
    validateFileSize(previewInput, 2);
    
    // Description word count
    const descriptionTextarea = document.getElementById('description');
    const wordCountDiv = document.createElement('div');
    wordCountDiv.className = 'form-text';
    descriptionTextarea.parentNode.appendChild(wordCountDiv);
    
    function updateWordCount() {
        const words = descriptionTextarea.value.trim().split(/\s+/).filter(word => word.length > 0);
        wordCountDiv.textContent = `${words.length} kata`;
        
        if (words.length < 50) {
            wordCountDiv.className = 'form-text text-warning';
            wordCountDiv.textContent += ' (minimal 50 kata)';
        } else {
            wordCountDiv.className = 'form-text text-success';
        }
    }
    
    descriptionTextarea.addEventListener('input', updateWordCount);
    updateWordCount();
    
    // Preview image
    previewInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('image-preview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = 'image-preview';
                    preview.className = 'mt-2';
                    previewInput.parentNode.appendChild(preview);
                }
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                    <small class="text-muted d-block">Preview gambar baru</small>
                `;
            };
            reader.readAsDataURL(file);
        }
    });
});

function deleteFile(filename) {
    if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
        const deleteUrl = `${deleteFileBaseUrl}/${filename}`;
        console.log('Delete URL:', deleteUrl); // Debug log

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'File berhasil dihapus');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('error', 'Gagal menghapus file: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat menghapus file');
        });
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'check-circle' : 'x-circle';

    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="bi bi-${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);

    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) alert.remove();
    }, 5000);
}
</script>
@endpush
