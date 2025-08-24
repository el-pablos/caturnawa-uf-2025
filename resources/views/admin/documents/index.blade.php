@extends('layouts.admin')

@section('title', 'Kelola Dokumen Registrasi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Kelola Dokumen Registrasi</h1>
            <p class="text-muted">Verifikasi dan kelola dokumen registrasi peserta</p>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-success" onclick="bulkVerify()">
                <i class="bi bi-check-circle-fill me-1"></i>Verifikasi Terpilih
            </button>
            <button type="button" class="btn btn-outline-primary" onclick="exportDocuments()">
                <i class="bi bi-download me-1"></i>Export
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Dokumen
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-documents">
                                {{ $statistics['total'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-earmark-text fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Terverifikasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="verified-documents">
                                {{ $statistics['verified'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Verifikasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pending-documents">
                                {{ $statistics['pending'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Perlu Revisi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="rejected-documents">
                                {{ $statistics['rejected'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-x-circle-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Dokumen</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label for="competition-filter" class="form-label">Kompetisi</label>
                    <select class="form-select" id="competition-filter">
                        <option value="">Semua Kompetisi</option>
                        @foreach($competitions as $competition)
                            <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="document-type-filter" class="form-label">Jenis Dokumen</label>
                    <select class="form-select" id="document-type-filter">
                        <option value="">Semua Jenis</option>
                        @foreach(\App\Models\RegistrationDocument::DOCUMENT_TYPES as $type => $name)
                            <option value="{{ $type }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status-filter" class="form-label">Status Verifikasi</label>
                    <select class="form-select" id="status-filter">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Verifikasi</option>
                        <option value="verified">Terverifikasi</option>
                        <option value="rejected">Perlu Revisi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search-filter" class="form-label">Cari Peserta</label>
                    <input type="text" class="form-control" id="search-filter" placeholder="Nama atau nomor registrasi">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary" onclick="applyFilters()">
                        <i class="bi bi-funnel me-1"></i>Terapkan Filter
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Dokumen Registrasi</h6>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="select-all">
                <label class="form-check-label" for="select-all">
                    Pilih Semua
                </label>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="documentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">
                                <input type="checkbox" id="header-checkbox">
                            </th>
                            <th>Peserta</th>
                            <th>Kompetisi</th>
                            <th>Jenis Dokumen</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Document Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Dokumen</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Peserta:</strong></td>
                                <td id="modal-participant"></td>
                            </tr>
                            <tr>
                                <td><strong>Kompetisi:</strong></td>
                                <td id="modal-competition"></td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Dokumen:</strong></td>
                                <td id="modal-document-type"></td>
                            </tr>
                            <tr>
                                <td><strong>Nama File:</strong></td>
                                <td id="modal-filename"></td>
                            </tr>
                            <tr>
                                <td><strong>Ukuran:</strong></td>
                                <td id="modal-filesize"></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Upload:</strong></td>
                                <td id="modal-upload-date"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Preview Dokumen</h6>
                        <div id="document-preview" class="border rounded p-3 text-center" style="min-height: 200px;">
                            <!-- Document preview will be loaded here -->
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Catatan Verifikasi</h6>
                        <textarea class="form-control" id="verification-notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-danger" onclick="rejectDocument()">
                    <i class="bi bi-x-circle me-1"></i>Tolak
                </button>
                <button type="button" class="btn btn-success" onclick="verifyDocument()">
                    <i class="bi bi-check-circle me-1"></i>Verifikasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Document Viewer Modal -->
<div class="modal fade" id="documentViewerModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewer-title">Lihat Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="document-viewer"></div>
            </div>
        </div>
    </div>
</div>

<script>
let documentsTable;
let currentDocumentId = null;

$(document).ready(function() {
    initializeDataTable();
    initializeEventHandlers();
});

function initializeDataTable() {
    documentsTable = $('#documentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.documents.datatable") }}',
            data: function(d) {
                d.competition_id = $('#competition-filter').val();
                d.document_type = $('#document-type-filter').val();
                d.status = $('#status-filter').val();
                d.search_term = $('#search-filter').val();
            }
        },
        columns: [
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `<input type="checkbox" class="document-checkbox" value="${data}">`;
                }
            },
            {
                data: 'registration.user.name',
                render: function(data, type, row) {
                    return `
                        <div>
                            <strong>${data}</strong><br>
                            <small class="text-muted">${row.registration.registration_number}</small>
                        </div>
                    `;
                }
            },
            { data: 'registration.competition.name' },
            { data: 'document_type_name' },
            {
                data: 'original_name',
                render: function(data, type, row) {
                    return `
                        <div>
                            <i class="bi bi-file-earmark me-1"></i>${data}<br>
                            <small class="text-muted">${row.file_size}</small>
                        </div>
                    `;
                }
            },
            {
                data: 'is_verified',
                render: function(data, type, row) {
                    if (data) {
                        return `<span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Terverifikasi
                        </span>`;
                    } else if (row.verification_notes) {
                        return `<span class="badge bg-danger">
                            <i class="bi bi-x-circle me-1"></i>Perlu Revisi
                        </span>`;
                    } else {
                        return `<span class="badge bg-warning">
                            <i class="bi bi-clock me-1"></i>Menunggu
                        </span>`;
                    }
                }
            },
            {
                data: 'created_at',
                render: function(data) {
                    return new Date(data).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="viewDocument(${data})" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-outline-info" onclick="openVerificationModal(${data})" title="Verifikasi">
                                <i class="bi bi-check-square"></i>
                            </button>
                            <a href="${row.file_url}" target="_blank" class="btn btn-outline-success" title="Download">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    `;
                }
            }
        ],
        order: [[6, 'desc']],
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });
}

function initializeEventHandlers() {
    // Select all checkbox
    $('#select-all, #header-checkbox').change(function() {
        $('.document-checkbox').prop('checked', this.checked);
    });

    // Individual checkbox change
    $(document).on('change', '.document-checkbox', function() {
        const totalCheckboxes = $('.document-checkbox').length;
        const checkedCheckboxes = $('.document-checkbox:checked').length;
        
        $('#select-all, #header-checkbox').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
}

function applyFilters() {
    documentsTable.ajax.reload();
}

function resetFilters() {
    $('#competition-filter').val('');
    $('#document-type-filter').val('');
    $('#status-filter').val('');
    $('#search-filter').val('');
    documentsTable.ajax.reload();
}

function openVerificationModal(documentId) {
    currentDocumentId = documentId;
    
    // Fetch document details
    fetch(`/admin/documents/${documentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const doc = data.data;
                
                // Populate modal fields
                $('#modal-participant').text(doc.registration.user.name);
                $('#modal-competition').text(doc.registration.competition.name);
                $('#modal-document-type').text(doc.document_type_name);
                $('#modal-filename').text(doc.original_name);
                $('#modal-filesize').text(doc.file_size);
                $('#modal-upload-date').text(new Date(doc.created_at).toLocaleDateString('id-ID'));
                $('#verification-notes').val(doc.verification_notes || '');
                
                // Load document preview
                loadDocumentPreview(doc);
                
                // Show modal
                new bootstrap.Modal(document.getElementById('verificationModal')).show();
            }
        })
        .catch(error => {
            console.error('Error loading document:', error);
            alert('Gagal memuat data dokumen');
        });
}

function loadDocumentPreview(doc) {
    const preview = document.getElementById('document-preview');
    
    if (doc.is_image) {
        preview.innerHTML = `<img src="${doc.file_url}" class="img-fluid" style="max-height: 200px;" alt="${doc.original_name}">`;
    } else {
        preview.innerHTML = `
            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                <i class="bi bi-file-earmark-pdf" style="font-size: 3rem; color: #dc3545;"></i>
                <p class="mt-2 mb-0">${doc.original_name}</p>
                <small class="text-muted">Klik tombol download untuk melihat file</small>
            </div>
        `;
    }
}

function verifyDocument() {
    if (!currentDocumentId) return;
    
    const notes = $('#verification-notes').val();
    
    fetch(`/admin/documents/${currentDocumentId}/verify`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            verification_notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('verificationModal')).hide();
            documentsTable.ajax.reload();
            updateStatistics();
            
            // Show success message
            showAlert('success', 'Dokumen berhasil diverifikasi');
        } else {
            showAlert('danger', data.message || 'Gagal memverifikasi dokumen');
        }
    })
    .catch(error => {
        console.error('Error verifying document:', error);
        showAlert('danger', 'Terjadi kesalahan saat memverifikasi dokumen');
    });
}

function rejectDocument() {
    if (!currentDocumentId) return;
    
    const notes = $('#verification-notes').val();
    
    if (!notes.trim()) {
        alert('Harap berikan catatan untuk penolakan dokumen');
        return;
    }
    
    fetch(`/admin/documents/${currentDocumentId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            verification_notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('verificationModal')).hide();
            documentsTable.ajax.reload();
            updateStatistics();
            
            // Show success message
            showAlert('warning', 'Dokumen ditolak dan peserta akan diberitahu');
        } else {
            showAlert('danger', data.message || 'Gagal menolak dokumen');
        }
    })
    .catch(error => {
        console.error('Error rejecting document:', error);
        showAlert('danger', 'Terjadi kesalahan saat menolak dokumen');
    });
}

function viewDocument(documentId) {
    // Fetch document details for viewing
    fetch(`/admin/documents/${documentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const doc = data.data;
                
                document.getElementById('viewer-title').textContent = `${doc.document_type_name} - ${doc.registration.user.name}`;
                
                const viewer = document.getElementById('document-viewer');
                if (doc.is_image) {
                    viewer.innerHTML = `<img src="${doc.file_url}" class="img-fluid" alt="${doc.original_name}">`;
                } else {
                    viewer.innerHTML = `
                        <div class="text-center">
                            <i class="bi bi-file-earmark-pdf" style="font-size: 4rem; color: #dc3545;"></i>
                            <p class="mt-3">${doc.original_name}</p>
                            <a href="${doc.file_url}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-download me-2"></i>Download & Lihat
                            </a>
                        </div>
                    `;
                }
                
                new bootstrap.Modal(document.getElementById('documentViewerModal')).show();
            }
        })
        .catch(error => {
            console.error('Error loading document:', error);
            alert('Gagal memuat dokumen');
        });
}

function bulkVerify() {
    const selectedIds = $('.document-checkbox:checked').map(function() {
        return this.value;
    }).get();
    
    if (selectedIds.length === 0) {
        alert('Pilih dokumen yang akan diverifikasi');
        return;
    }
    
    if (!confirm(`Verifikasi ${selectedIds.length} dokumen terpilih?`)) {
        return;
    }
    
    fetch('/admin/documents/bulk-verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            document_ids: selectedIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            documentsTable.ajax.reload();
            updateStatistics();
            $('.document-checkbox').prop('checked', false);
            $('#select-all, #header-checkbox').prop('checked', false);
            
            showAlert('success', `${data.verified_count} dokumen berhasil diverifikasi`);
        } else {
            showAlert('danger', data.message || 'Gagal memverifikasi dokumen');
        }
    })
    .catch(error => {
        console.error('Error bulk verifying:', error);
        showAlert('danger', 'Terjadi kesalahan saat memverifikasi dokumen');
    });
}

function exportDocuments() {
    const params = new URLSearchParams({
        competition_id: $('#competition-filter').val(),
        document_type: $('#document-type-filter').val(),
        status: $('#status-filter').val(),
        search_term: $('#search-filter').val()
    });
    
    window.open(`/admin/documents/export?${params.toString()}`, '_blank');
}

function updateStatistics() {
    fetch('/admin/documents/statistics')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.data;
                document.getElementById('total-documents').textContent = stats.total;
                document.getElementById('verified-documents').textContent = stats.verified;
                document.getElementById('pending-documents').textContent = stats.pending;
                document.getElementById('rejected-documents').textContent = stats.rejected;
            }
        })
        .catch(error => {
            console.error('Error updating statistics:', error);
        });
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert at the top of the container
    $('.container-fluid').prepend(alertHtml);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        $('.alert').first().alert('close');
    }, 5000);
}
</script>
@endsection
