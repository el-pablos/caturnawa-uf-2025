@extends('layouts.peserta')

@section('title', 'Upload Dokumen - ' . $registration->competition->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Upload Dokumen</h1>
            <p class="text-muted">{{ $registration->competition->name }} - {{ $registration->registration_number }}</p>
        </div>
        <a href="{{ route('peserta.registrations.show', $registration) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Alerts -->
    <div id="alert-container"></div>

    <!-- Document Upload Cards -->
    <div class="row">
        @foreach(\App\Models\RegistrationDocument::DOCUMENT_TYPES as $type => $name)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-file-earmark me-2"></i>{{ $name }}
                        </h6>
                        <span class="badge bg-secondary" id="status-{{ $type }}">Belum Upload</span>
                    </div>
                    <div class="card-body">
                        <!-- Upload Area -->
                        <div class="upload-area border-2 border-dashed rounded p-4 text-center mb-3" 
                             id="upload-area-{{ $type }}"
                             style="border-color: #dee2e6; cursor: pointer;">
                            <i class="bi bi-cloud-upload text-muted" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-1">Klik untuk upload atau drag & drop</p>
                            <small class="text-muted">Format: JPEG, JPG, PNG, PDF (Max: 5MB)</small>
                            <input type="file" class="d-none" id="file-input-{{ $type }}" 
                                   accept=".jpg,.jpeg,.png,.pdf" data-type="{{ $type }}">
                        </div>

                        <!-- Document Preview -->
                        <div class="document-preview d-none" id="preview-{{ $type }}">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-check text-success me-2"></i>
                                    <div>
                                        <div class="fw-semibold" id="filename-{{ $type }}"></div>
                                        <small class="text-muted" id="filesize-{{ $type }}"></small>
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="viewDocument('{{ $type }}')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteDocument('{{ $type }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Verification Status -->
                            <div class="mt-2" id="verification-{{ $type }}"></div>
                        </div>

                        <!-- Upload Progress -->
                        <div class="progress d-none" id="progress-{{ $type }}">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Instructions -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>Petunjuk Upload Dokumen
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Dokumen yang Diperlukan:</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success me-2"></i><strong>Foto Diri:</strong> Foto formal terbaru</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i><strong>Kartu Identitas:</strong> KTP/SIM/Kartu Pelajar</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i><strong>Bukti Follow:</strong> Screenshot follow akun resmi</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i><strong>Twibbon:</strong> Foto dengan twibbon resmi</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Ketentuan File:</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-file-earmark text-primary me-2"></i>Format: JPEG, JPG, PNG, PDF</li>
                        <li><i class="bi bi-hdd text-primary me-2"></i>Ukuran maksimal: 5MB per file</li>
                        <li><i class="bi bi-shield-check text-primary me-2"></i>File akan diverifikasi oleh admin</li>
                        <li><i class="bi bi-arrow-clockwise text-primary me-2"></i>Dapat diupload ulang jika diperlukan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Viewer Modal -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalTitle">Lihat Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="documentViewer"></div>
            </div>
        </div>
    </div>
</div>

<script>
let registrationId = {{ $registration->id }};
let documents = {};

// Load existing documents
document.addEventListener('DOMContentLoaded', function() {
    loadDocuments();
    initializeUploadAreas();
});

function loadDocuments() {
    fetch(`/api/registrations/${registrationId}/documents`, {
        headers: {
            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]')?.content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            data.data.documents.forEach(doc => {
                documents[doc.document_type] = doc;
                updateDocumentDisplay(doc.document_type, doc);
            });
        }
    })
    .catch(error => {
        console.error('Error loading documents:', error);
    });
}

function initializeUploadAreas() {
    @foreach(array_keys(\App\Models\RegistrationDocument::DOCUMENT_TYPES) as $type)
        // Click to upload
        document.getElementById('upload-area-{{ $type }}').addEventListener('click', function() {
            document.getElementById('file-input-{{ $type }}').click();
        });

        // File input change
        document.getElementById('file-input-{{ $type }}').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                uploadDocument('{{ $type }}', e.target.files[0]);
            }
        });

        // Drag and drop
        const uploadArea = document.getElementById('upload-area-{{ $type }}');
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = '#007bff';
            this.style.backgroundColor = '#f8f9fa';
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.borderColor = '#dee2e6';
            this.style.backgroundColor = 'transparent';
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = '#dee2e6';
            this.style.backgroundColor = 'transparent';
            
            if (e.dataTransfer.files.length > 0) {
                uploadDocument('{{ $type }}', e.dataTransfer.files[0]);
            }
        });
    @endforeach
}

function uploadDocument(type, file) {
    const formData = new FormData();
    formData.append('document_type', type);
    formData.append('file', file);

    // Show progress
    const progressBar = document.getElementById('progress-' + type);
    progressBar.classList.remove('d-none');
    
    fetch(`/api/registrations/${registrationId}/documents`, {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]')?.content,
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        progressBar.classList.add('d-none');
        
        if (data.success) {
            documents[type] = data.data;
            updateDocumentDisplay(type, data.data);
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        progressBar.classList.add('d-none');
        console.error('Upload error:', error);
        showAlert('danger', 'Terjadi kesalahan saat mengupload dokumen');
    });
}

function updateDocumentDisplay(type, doc) {
    const uploadArea = document.getElementById('upload-area-' + type);
    const preview = document.getElementById('preview-' + type);
    const status = document.getElementById('status-' + type);
    
    // Hide upload area, show preview
    uploadArea.classList.add('d-none');
    preview.classList.remove('d-none');
    
    // Update file info
    document.getElementById('filename-' + type).textContent = doc.original_name;
    document.getElementById('filesize-' + type).textContent = doc.file_size;
    
    // Update status
    if (doc.is_verified) {
        status.textContent = 'Terverifikasi';
        status.className = 'badge bg-success';
    } else {
        status.textContent = 'Menunggu Verifikasi';
        status.className = 'badge bg-warning';
    }
    
    // Update verification info
    const verification = document.getElementById('verification-' + type);
    if (doc.is_verified) {
        verification.innerHTML = `
            <div class="alert alert-success py-2 mb-0">
                <i class="bi bi-check-circle me-2"></i>Dokumen telah diverifikasi
                ${doc.verified_by ? 'oleh ' + doc.verified_by.name : ''}
            </div>
        `;
    } else if (doc.verification_notes) {
        verification.innerHTML = `
            <div class="alert alert-warning py-2 mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>${doc.verification_notes}
            </div>
        `;
    } else {
        verification.innerHTML = `
            <div class="alert alert-info py-2 mb-0">
                <i class="bi bi-clock me-2"></i>Dokumen sedang dalam proses verifikasi
            </div>
        `;
    }
}

function viewDocument(type) {
    const doc = documents[type];
    if (!doc) return;
    
    const modal = new bootstrap.Modal(document.getElementById('documentModal'));
    document.getElementById('documentModalTitle').textContent = doc.document_type_name;
    
    const viewer = document.getElementById('documentViewer');
    if (doc.is_image) {
        viewer.innerHTML = `<img src="${doc.file_url}" class="img-fluid" alt="${doc.original_name}">`;
    } else {
        viewer.innerHTML = `
            <div class="text-center">
                <i class="bi bi-file-earmark-pdf" style="font-size: 4rem;"></i>
                <p class="mt-2">${doc.original_name}</p>
                <a href="${doc.file_url}" target="_blank" class="btn btn-primary">
                    <i class="bi bi-download me-2"></i>Download
                </a>
            </div>
        `;
    }
    
    modal.show();
}

function deleteDocument(type) {
    const doc = documents[type];
    if (!doc) return;
    
    if (!confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
        return;
    }
    
    fetch(`/api/registrations/${registrationId}/documents/${doc.id}`, {
        method: 'DELETE',
        headers: {
            'Authorization': 'Bearer ' + document.querySelector('meta[name="api-token"]')?.content,
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            delete documents[type];
            
            // Show upload area, hide preview
            document.getElementById('upload-area-' + type).classList.remove('d-none');
            document.getElementById('preview-' + type).classList.add('d-none');
            
            // Reset status
            const status = document.getElementById('status-' + type);
            status.textContent = 'Belum Upload';
            status.className = 'badge bg-secondary';
            
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        showAlert('danger', 'Terjadi kesalahan saat menghapus dokumen');
    });
}

function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alert);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection
