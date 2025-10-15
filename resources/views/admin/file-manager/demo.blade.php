@extends('layouts.admin')

@section('title', 'File Management Demo')

@section('page-title', 'File Management UI Demo')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Info Card -->
        <div class="alert alert-info">
            <h5 class="alert-heading">
                <i class="bi bi-info-circle me-2"></i>File Management UI Components
            </h5>
            <p class="mb-0">
                This page demonstrates the enhanced file upload and management components with drag-and-drop, 
                file preview, and modern UX features.
            </p>
        </div>

        <!-- File Upload Component Demo -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-cloud-upload me-2"></i>File Upload Component
                </h5>
            </div>
            <div class="card-body">
                <h6 class="mb-3">Single File Upload</h6>
                <x-file-upload 
                    name="single_file"
                    id="single-upload"
                    accept=".pdf,.doc,.docx"
                    :multiple="false"
                    :maxSize="5120"
                    :required="false"
                    allowedTypes="PDF, DOC, DOCX"
                />

                <hr class="my-4">

                <h6 class="mb-3">Multiple Files Upload</h6>
                <x-file-upload 
                    name="multiple_files"
                    id="multiple-upload"
                    accept="image/*,.pdf,.doc,.docx,.zip"
                    :multiple="true"
                    :maxSize="10240"
                    :maxFiles="5"
                    :required="false"
                    allowedTypes="Images, PDF, DOC, DOCX, ZIP"
                />

                <hr class="my-4">

                <h6 class="mb-3">With Existing Files</h6>
                <x-file-upload 
                    name="files_with_existing"
                    id="existing-upload"
                    accept="*"
                    :multiple="true"
                    :maxSize="10240"
                    :existingFiles="[
                        [
                            'filename' => 'document-1.pdf',
                            'original_name' => 'Project Proposal.pdf',
                            'path' => 'submissions/1/document-1.pdf',
                            'size' => 2048000,
                            'mime_type' => 'application/pdf',
                        ],
                        [
                            'filename' => 'image-1.jpg',
                            'original_name' => 'Screenshot.jpg',
                            'path' => 'submissions/1/image-1.jpg',
                            'size' => 1024000,
                            'mime_type' => 'image/jpeg',
                        ],
                    ]"
                    allowedTypes="All file types"
                />
            </div>
        </div>

        <!-- File Manager Component Demo -->
        <div class="mb-4">
            <x-file-manager 
                :files="[
                    [
                        'filename' => 'report-2024.pdf',
                        'original_name' => 'Annual Report 2024.pdf',
                        'path' => 'documents/report-2024.pdf',
                        'size' => 3145728,
                        'mime_type' => 'application/pdf',
                        'uploaded_at' => '2024-10-15 10:30:00',
                    ],
                    [
                        'filename' => 'presentation.pptx',
                        'original_name' => 'Company Presentation.pptx',
                        'path' => 'documents/presentation.pptx',
                        'size' => 5242880,
                        'mime_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'uploaded_at' => '2024-10-14 15:45:00',
                    ],
                    [
                        'filename' => 'logo.png',
                        'original_name' => 'Company Logo.png',
                        'path' => 'images/logo.png',
                        'size' => 512000,
                        'mime_type' => 'image/png',
                        'uploaded_at' => '2024-10-13 09:20:00',
                    ],
                    [
                        'filename' => 'data.xlsx',
                        'original_name' => 'Sales Data Q3.xlsx',
                        'path' => 'documents/data.xlsx',
                        'size' => 1048576,
                        'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'uploaded_at' => '2024-10-12 14:10:00',
                    ],
                    [
                        'filename' => 'video.mp4',
                        'original_name' => 'Product Demo.mp4',
                        'path' => 'videos/video.mp4',
                        'size' => 10485760,
                        'mime_type' => 'video/mp4',
                        'uploaded_at' => '2024-10-11 11:30:00',
                    ],
                    [
                        'filename' => 'archive.zip',
                        'original_name' => 'Project Files.zip',
                        'path' => 'archives/archive.zip',
                        'size' => 20971520,
                        'mime_type' => 'application/zip',
                        'uploaded_at' => '2024-10-10 16:00:00',
                    ],
                ]"
                uploadUrl="/api/files/upload"
                deleteUrl="/api/files/delete"
                :canUpload="true"
                :canDelete="true"
                :maxSize="10240"
                title="Project Files"
            />
        </div>

        <!-- Features List -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-check2-circle me-2"></i>Features Implemented
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">File Upload Component</h6>
                        <ul>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Drag and drop file upload</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Click to browse files</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>File size validation</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>File type validation</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Maximum files limit</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Real-time file preview</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>File icon based on type</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Remove files before upload</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Display existing files</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Progress indicator</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">File Manager Component</h6>
                        <ul>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Grid view for files</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>List view alternative</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Image preview thumbnails</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>File type icons</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>View/Download/Delete actions</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>File metadata display</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Upload modal integration</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Responsive design</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Hover effects</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Empty state handling</li>
                        </ul>
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold">Supported File Types</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-pdf text-danger me-2" style="font-size: 1.5rem;"></i>
                            <span>PDF Documents</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-word text-primary me-2" style="font-size: 1.5rem;"></i>
                            <span>Word Documents</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-excel text-success me-2" style="font-size: 1.5rem;"></i>
                            <span>Excel Spreadsheets</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-ppt text-warning me-2" style="font-size: 1.5rem;"></i>
                            <span>PowerPoint</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-image text-primary me-2" style="font-size: 1.5rem;"></i>
                            <span>Images (JPG, PNG, GIF)</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-play text-success me-2" style="font-size: 1.5rem;"></i>
                            <span>Videos (MP4, AVI, MOV)</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-music text-info me-2" style="font-size: 1.5rem;"></i>
                            <span>Audio Files</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-zip text-warning me-2" style="font-size: 1.5rem;"></i>
                            <span>Archives (ZIP, RAR)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

