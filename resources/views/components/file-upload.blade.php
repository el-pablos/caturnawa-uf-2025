@props([
    'name' => 'files',
    'id' => 'file-upload',
    'accept' => '*',
    'multiple' => false,
    'maxSize' => 10240, // KB
    'maxFiles' => 10,
    'required' => false,
    'existingFiles' => [],
    'showPreview' => true,
    'allowedTypes' => 'All file types',
])

<div class="file-upload-container" data-upload-id="{{ $id }}">
    <!-- Upload Area -->
    <div class="file-upload-area" id="{{ $id }}-area">
        <input type="file" 
               name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
               id="{{ $id }}" 
               class="file-upload-input d-none"
               accept="{{ $accept }}"
               {{ $multiple ? 'multiple' : '' }}
               {{ $required ? 'required' : '' }}
               data-max-size="{{ $maxSize }}"
               data-max-files="{{ $maxFiles }}">
        
        <div class="upload-prompt text-center py-5">
            <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #6c757d;"></i>
            <h5 class="mt-3">Drag & Drop Files Here</h5>
            <p class="text-muted mb-3">or</p>
            <button type="button" class="btn btn-primary" onclick="document.getElementById('{{ $id }}').click()">
                <i class="bi bi-folder2-open me-2"></i>Browse Files
            </button>
            <div class="mt-3 small text-muted">
                <p class="mb-1"><strong>Allowed:</strong> {{ $allowedTypes }}</p>
                <p class="mb-1"><strong>Max Size:</strong> {{ number_format($maxSize / 1024, 0) }} MB per file</p>
                @if($multiple)
                    <p class="mb-0"><strong>Max Files:</strong> {{ $maxFiles }} files</p>
                @endif
            </div>
        </div>
    </div>

    <!-- File Preview Area -->
    @if($showPreview)
        <div class="file-preview-area mt-3" id="{{ $id }}-preview">
            <!-- Existing files -->
            @if(!empty($existingFiles))
                <div class="existing-files mb-3">
                    <h6 class="mb-2">Existing Files</h6>
                    <div class="row g-2">
                        @foreach($existingFiles as $file)
                            <div class="col-md-6">
                                <div class="file-preview-item existing">
                                    <div class="d-flex align-items-center">
                                        <div class="file-icon me-3">
                                            <i class="bi {{ getFileIcon($file['mime_type'] ?? 'application/octet-stream') }}" style="font-size: 2rem;"></i>
                                        </div>
                                        <div class="file-info flex-grow-1">
                                            <div class="file-name fw-bold">{{ $file['original_name'] ?? $file['filename'] }}</div>
                                            <div class="file-size text-muted small">{{ formatFileSize($file['size'] ?? 0) }}</div>
                                        </div>
                                        <div class="file-actions">
                                            @if(isset($file['path']))
                                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeExistingFile(this, '{{ $file['filename'] }}')" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- New files preview -->
            <div class="new-files" id="{{ $id }}-new-files" style="display: none;">
                <h6 class="mb-2">New Files to Upload</h6>
                <div class="row g-2" id="{{ $id }}-files-list"></div>
            </div>
        </div>
    @endif

    <!-- Progress Bar -->
    <div class="upload-progress mt-3" id="{{ $id }}-progress" style="display: none;">
        <div class="d-flex justify-content-between mb-1">
            <span class="progress-label">Uploading...</span>
            <span class="progress-percentage">0%</span>
        </div>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
        </div>
    </div>
</div>

@push('styles')
<style>
.file-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

.file-upload-area.drag-over {
    border-color: #0d6efd;
    background-color: #cfe2ff;
    transform: scale(1.02);
}

.file-preview-item {
    padding: 12px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    background-color: #fff;
    transition: all 0.2s ease;
}

.file-preview-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.file-preview-item.existing {
    background-color: #f8f9fa;
}

.file-preview-item.uploading {
    opacity: 0.6;
}

.file-preview-item.error {
    border-color: #dc3545;
    background-color: #f8d7da;
}

.file-icon {
    color: #6c757d;
}

.file-name {
    font-size: 0.9rem;
    word-break: break-word;
}

.file-size {
    font-size: 0.8rem;
}

.upload-progress {
    padding: 12px;
    background-color: #f8f9fa;
    border-radius: 6px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeFileUpload('{{ $id }}');
});

function initializeFileUpload(uploadId) {
    const uploadArea = document.getElementById(uploadId + '-area');
    const fileInput = document.getElementById(uploadId);
    const previewArea = document.getElementById(uploadId + '-preview');
    const filesList = document.getElementById(uploadId + '-files-list');
    const newFilesContainer = document.getElementById(uploadId + '-new-files');
    
    if (!uploadArea || !fileInput) return;

    // Click to upload
    uploadArea.addEventListener('click', function(e) {
        if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'I') {
            fileInput.click();
        }
    });

    // Drag and drop events
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('drag-over');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('drag-over');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        handleFiles(files, fileInput, filesList, newFilesContainer);
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        handleFiles(this.files, fileInput, filesList, newFilesContainer);
    });
}

function handleFiles(files, fileInput, filesList, newFilesContainer) {
    if (!files || files.length === 0) return;

    const maxSize = parseInt(fileInput.dataset.maxSize) * 1024; // Convert KB to bytes
    const maxFiles = parseInt(fileInput.dataset.maxFiles);
    
    // Clear previous preview
    if (filesList) {
        filesList.innerHTML = '';
    }

    // Show new files container
    if (newFilesContainer) {
        newFilesContainer.style.display = 'block';
    }

    // Validate and preview files
    Array.from(files).slice(0, maxFiles).forEach((file, index) => {
        const fileItem = createFilePreview(file, index, maxSize);
        if (filesList) {
            filesList.appendChild(fileItem);
        }
    });

    if (files.length > maxFiles) {
        alert(`Maximum ${maxFiles} files allowed. Only first ${maxFiles} files will be uploaded.`);
    }
}

function createFilePreview(file, index, maxSize) {
    const col = document.createElement('div');
    col.className = 'col-md-6';

    const isValid = file.size <= maxSize;
    const itemClass = isValid ? 'file-preview-item' : 'file-preview-item error';

    col.innerHTML = `
        <div class="${itemClass}">
            <div class="d-flex align-items-center">
                <div class="file-icon me-3">
                    <i class="bi ${getFileIconByType(file.type)}" style="font-size: 2rem;"></i>
                </div>
                <div class="file-info flex-grow-1">
                    <div class="file-name fw-bold">${escapeHtml(file.name)}</div>
                    <div class="file-size text-muted small">${formatFileSize(file.size)}</div>
                    ${!isValid ? '<div class="text-danger small">File too large!</div>' : ''}
                </div>
                <div class="file-actions">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFilePreview(this)" title="Remove">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

    return col;
}

function removeFilePreview(button) {
    const col = button.closest('.col-md-6');
    if (col) {
        col.remove();
    }
}

function removeExistingFile(button, filename) {
    if (confirm('Are you sure you want to delete this file?')) {
        const col = button.closest('.col-md-6');
        if (col) {
            col.remove();
            // You can add AJAX call here to delete from server
        }
    }
}

function getFileIconByType(mimeType) {
    if (mimeType.startsWith('image/')) return 'bi-file-image text-primary';
    if (mimeType.startsWith('video/')) return 'bi-file-play text-success';
    if (mimeType.startsWith('audio/')) return 'bi-file-music text-info';
    if (mimeType.includes('pdf')) return 'bi-file-pdf text-danger';
    if (mimeType.includes('word') || mimeType.includes('document')) return 'bi-file-word text-primary';
    if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'bi-file-excel text-success';
    if (mimeType.includes('powerpoint') || mimeType.includes('presentation')) return 'bi-file-ppt text-warning';
    if (mimeType.includes('zip') || mimeType.includes('rar') || mimeType.includes('compressed')) return 'bi-file-zip text-warning';
    return 'bi-file-earmark text-secondary';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
</script>
@endpush

@php
function getFileIcon($mimeType) {
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

function formatFileSize($bytes) {
    if ($bytes == 0) return '0 Bytes';
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}
@endphp

