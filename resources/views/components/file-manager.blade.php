@props([
    'files' => [],
    'uploadUrl' => '#',
    'deleteUrl' => '#',
    'downloadUrl' => '#',
    'canUpload' => true,
    'canDelete' => true,
    'maxSize' => 10240, // KB
    'accept' => '*',
    'title' => 'File Manager',
])

<div class="file-manager-container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-folder2-open me-2"></i>{{ $title }}
                </h5>
                @if($canUpload)
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="bi bi-cloud-upload me-1"></i>Upload Files
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if(count($files) > 0)
                <!-- File Grid View -->
                <div class="file-grid">
                    <div class="row g-3">
                        @foreach($files as $file)
                            <div class="col-md-4 col-lg-3">
                                <div class="file-card">
                                    <!-- File Preview -->
                                    <div class="file-preview">
                                        @if(isset($file['mime_type']) && str_starts_with($file['mime_type'], 'image/'))
                                            <img src="{{ asset('storage/' . $file['path']) }}" alt="{{ $file['original_name'] }}" class="img-fluid">
                                        @else
                                            <div class="file-icon-large">
                                                <i class="bi {{ getFileIconLarge($file['mime_type'] ?? 'application/octet-stream') }}"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- File Info -->
                                    <div class="file-info-card">
                                        <div class="file-name" title="{{ $file['original_name'] ?? $file['filename'] }}">
                                            {{ Str::limit($file['original_name'] ?? $file['filename'], 20) }}
                                        </div>
                                        <div class="file-meta text-muted small">
                                            <span>{{ formatFileSizeHelper($file['size'] ?? 0) }}</span>
                                            @if(isset($file['uploaded_at']))
                                                <span class="ms-2">{{ \Carbon\Carbon::parse($file['uploaded_at'])->format('M d, Y') }}</span>
                                            @endif
                                        </div>

                                        <!-- File Actions -->
                                        <div class="file-actions mt-2">
                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ asset('storage/' . $file['path']) }}" download class="btn btn-outline-success" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                @if($canDelete)
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteFile('{{ $file['filename'] }}')" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- File List View (Alternative) -->
                <div class="file-list mt-4" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Type</th>
                                    <th>Uploaded</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($files as $file)
                                    <tr>
                                        <td>
                                            <i class="bi {{ getFileIconLarge($file['mime_type'] ?? 'application/octet-stream') }} me-2"></i>
                                            {{ $file['original_name'] ?? $file['filename'] }}
                                        </td>
                                        <td>{{ formatFileSizeHelper($file['size'] ?? 0) }}</td>
                                        <td>{{ strtoupper(pathinfo($file['filename'], PATHINFO_EXTENSION)) }}</td>
                                        <td>
                                            @if(isset($file['uploaded_at']))
                                                {{ \Carbon\Carbon::parse($file['uploaded_at'])->format('M d, Y H:i') }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ asset('storage/' . $file['path']) }}" download class="btn btn-outline-success" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                @if($canDelete)
                                                    <button type="button" class="btn btn-outline-danger" onclick="deleteFile('{{ $file['filename'] }}')" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-folder2-open" style="font-size: 4rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">No files uploaded yet</h5>
                    @if($canUpload)
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="bi bi-cloud-upload me-2"></i>Upload Your First File
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Upload Modal -->
@if($canUpload)
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Files
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <x-file-upload 
                        name="files"
                        id="file-manager-upload"
                        :multiple="true"
                        :maxSize="$maxSize"
                        :accept="$accept"
                        allowedTypes="PDF, DOC, DOCX, Images, Videos, ZIP"
                    />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="uploadFiles()">
                        <i class="bi bi-cloud-upload me-2"></i>Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@push('styles')
<style>
.file-grid {
    min-height: 200px;
}

.file-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    background-color: #fff;
}

.file-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.file-preview {
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    overflow: hidden;
}

.file-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-icon-large {
    font-size: 4rem;
    color: #6c757d;
}

.file-info-card {
    padding: 12px;
}

.file-name {
    font-weight: 600;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.file-meta {
    margin-top: 4px;
}
</style>
@endpush

@push('scripts')
<script>
function deleteFile(filename) {
    if (confirm('Are you sure you want to delete this file?')) {
        // Add AJAX call to delete file
        console.log('Deleting file:', filename);
        // You can implement the actual delete logic here
    }
}

function uploadFiles() {
    const fileInput = document.getElementById('file-manager-upload');
    const files = fileInput.files;
    
    if (files.length === 0) {
        alert('Please select files to upload');
        return;
    }

    // Add your upload logic here
    console.log('Uploading files:', files);
    
    // Close modal after upload
    const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
    modal.hide();
    
    // Reload page or update file list
    // location.reload();
}
</script>
@endpush

@php
function getFileIconLarge($mimeType) {
    if (str_starts_with($mimeType, 'image/')) return 'bi-file-image text-primary';
    if (str_starts_with($mimeType, 'video/')) return 'bi-file-play text-success';
    if (str_starts_with($mimeType, 'audio/')) return 'bi-file-music text-info';
    if (str_contains($mimeType, 'pdf')) return 'bi-file-pdf text-danger';
    if (str_contains($mimeType, 'word') || str_contains($mimeType, 'document')) return 'bi-file-word text-primary';
    if (str_contains($mimeType, 'excel') || str_contains($mimeType, 'spreadsheet')) return 'bi-file-excel text-success';
    if (str_contains($mimeType, 'powerpoint') || str_contains($mimeType, 'presentation')) return 'bi-file-ppt text-warning';
    if (str_contains($mimeType, 'zip') || str_contains($mimeType, 'rar') || str_contains($mimeType, 'compressed')) return 'bi-file-zip text-warning';
    return 'bi-file-earmark text-secondary';
}

function formatFileSizeHelper($bytes) {
    if ($bytes == 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}
@endphp

