@extends('layouts.peserta')

@section('title', 'Submit Karya')

@section('page-title', 'Submit Karya')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('peserta.submissions.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-cloud-upload me-2"></i>Submit Karya untuk {{ $registration->competition->name }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('peserta.submissions.store', $registration) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Karya <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        <div class="form-text">Berikan judul yang menarik dan menggambarkan karya Anda</div>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Karya <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        <div class="form-text">Jelaskan konsep, tujuan, dan keunikan karya Anda (minimal 100 kata)</div>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if($registration->competition->isSpcCompetition())
                        <!-- SPC-specific fields according to PDF requirements -->
                        <div class="mb-3">
                            <label for="file_karya" class="form-label">File Karya (Format PDF) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file_karya') is-invalid @enderror"
                                   id="file_karya" name="file_karya" accept=".pdf" required>
                            <div class="form-text">Upload file karya tulis ilmiah dalam format PDF (Maksimal 50MB)</div>
                            @error('file_karya')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="metodologi" class="form-label">Metodologi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('metodologi') is-invalid @enderror" 
                                      id="metodologi" name="metodologi" rows="3" required>{{ old('metodologi') }}</textarea>
                            <div class="form-text">Jelaskan metodologi penelitian yang digunakan dalam karya tulis ilmiah Anda</div>
                            @error('metodologi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="surat_orisinalitas" class="form-label">Surat Pernyataan Orisinalitas Karya (Format PDF) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('surat_orisinalitas') is-invalid @enderror"
                                   id="surat_orisinalitas" name="surat_orisinalitas" accept=".pdf" required>
                            <div class="form-text">Upload scan surat pernyataan orisinalitas karya dalam format PDF (Maksimal 10MB)</div>
                            @error('surat_orisinalitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="surat_pengalihan_hak_cipta" class="form-label">Surat Pernyataan Pengalihan Hak Cipta (Format PDF) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('surat_pengalihan_hak_cipta') is-invalid @enderror"
                                   id="surat_pengalihan_hak_cipta" name="surat_pengalihan_hak_cipta" accept=".pdf" required>
                            <div class="form-text">Upload scan surat pernyataan pengalihan hak cipta dalam format PDF (Maksimal 10MB)</div>
                            @error('surat_pengalihan_hak_cipta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @elseif($registration->competition->isEdcCompetition() || $registration->competition->isKdbiCompetition())
                        <!-- EDC/KDBI-specific fields for debate competitions -->
                        <div class="mb-3">
                            <label for="files" class="form-label">File Submission <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('files.*') is-invalid @enderror"
                                   id="files" name="files[]" multiple required>
                            <div class="form-text">
                                Upload submission files (PDF, DOC, DOCX, ZIP - Maksimal 10MB per file).
                            </div>
                            @error('files.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <!-- Generic submission form for other competitions -->
                        <div class="mb-3">
                            <label for="files" class="form-label">File Karya <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('files.*') is-invalid @enderror"
                                   id="files" name="files[]" multiple required>
                            <div class="form-text">
                                Format yang diterima: PDF, DOC, DOCX, ZIP, RAR (Maksimal 10MB per file). Anda dapat memilih beberapa file sekaligus.
                            </div>
                            @error('files.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input @error('agreement') is-invalid @enderror" 
                                   type="checkbox" id="agreement" name="agreement" required>
                            <label class="form-check-label" for="agreement">
                                Saya menyatakan bahwa karya ini adalah hasil karya sendiri/tim dan tidak melanggar hak cipta pihak lain. 
                                Saya juga menyetujui <a href="#" target="_blank">syarat dan ketentuan</a> yang berlaku.
                            </label>
                            @error('agreement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('peserta.submissions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-upload me-2"></i>Submit Karya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Kompetisi
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Kompetisi:</strong><br>
                    <span class="text-muted">{{ $registration->competition->name }}</span>
                </div>
                <div class="mb-3">
                    <strong>Kategori:</strong><br>
                    <span class="badge bg-primary">{{ ucfirst($registration->competition->category) }}</span>
                </div>
                <div class="mb-3">
                    <strong>Deadline:</strong><br>
                    <span class="text-muted">{{ $registration->competition->submission_deadline?->format('d M Y H:i') ?? 'Belum ditentukan' }}</span>
                </div>
                @if($registration->competition->submission_deadline)
                    <div class="mb-3">
                        <strong>Sisa Waktu:</strong><br>
                        @php
                            $deadline = $registration->competition->submission_deadline;
                            $now = now();
                            $diff = $deadline->diff($now);
                        @endphp
                        @if($deadline > $now)
                            <span class="text-success">
                                {{ $diff->days }} hari {{ $diff->h }} jam {{ $diff->i }} menit
                            </span>
                        @else
                            <span class="text-danger">Deadline telah berakhir</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>Panduan Submit
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Pastikan file karya sesuai dengan ketentuan kompetisi</li>
                    <li>Deskripsi harus jelas dan menggambarkan karya dengan baik</li>
                    <li>File maksimal 10MB, gunakan format yang diizinkan</li>
                    <li>Gambar preview akan ditampilkan di galeri karya</li>
                    <li>Link video dan repository bersifat opsional tapi sangat direkomendasikan</li>
                    <li>Setelah submit, karya dapat diedit hingga deadline</li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>Tips Sukses
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><strong>Judul Menarik:</strong> Buat judul yang catchy dan menggambarkan karya</li>
                    <li><strong>Deskripsi Lengkap:</strong> Jelaskan problem, solution, dan impact</li>
                    <li><strong>File Terorganisir:</strong> Pastikan file mudah dibaca dan dipahami</li>
                    <li><strong>Preview Menarik:</strong> Gunakan gambar yang eye-catching</li>
                    <li><strong>Demo Video:</strong> Tunjukkan cara kerja karya Anda</li>
                    <li><strong>Submit Early:</strong> Jangan tunggu hingga deadline</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File size validation
    const fileInput = document.getElementById('files');
    const fileKaryaInput = document.getElementById('file_karya');
    const suratOrisinalitasInput = document.getElementById('surat_orisinalitas');
    const suratPengalihanInput = document.getElementById('surat_pengalihan_hak_cipta');
    const previewInput = document.getElementById('preview_image');

    function validateFileSize(input, maxSize, unit = 'MB') {
        if (input) {
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
    }

    // Apply validation to all possible file inputs
    validateFileSize(fileInput, 10);
    validateFileSize(fileKaryaInput, 50);
    validateFileSize(suratOrisinalitasInput, 10);
    validateFileSize(suratPengalihanInput, 10);
    validateFileSize(previewInput, 2);
    
    // Description word count
    const descriptionTextarea = document.getElementById('description');
    const wordCountDiv = document.createElement('div');
    wordCountDiv.className = 'form-text';
    wordCountDiv.id = 'word-count';
    descriptionTextarea.parentNode.insertBefore(wordCountDiv, descriptionTextarea.nextSibling);
    
    function updateWordCount() {
        const text = descriptionTextarea.value.trim();
        const words = text ? text.split(/\s+/).length : 0;
        const color = words >= 100 ? 'text-success' : 'text-warning';
        wordCountDiv.innerHTML = `<span class="${color}">Jumlah kata: ${words} (minimal 100 kata)</span>`;
    }
    
    descriptionTextarea.addEventListener('input', updateWordCount);
    updateWordCount();
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const description = descriptionTextarea.value.trim();
        const words = description ? description.split(/\s+/).length : 0;
        
        if (words < 100) {
            e.preventDefault();
            alert('Deskripsi harus minimal 100 kata!');
            descriptionTextarea.focus();
            return false;
        }
        
        const agreement = document.getElementById('agreement');
        if (!agreement.checked) {
            e.preventDefault();
            alert('Anda harus menyetujui syarat dan ketentuan!');
            agreement.focus();
            return false;
        }
    });
    
    // Preview image
    if (previewInput) {
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
                        <small class="text-muted d-block">Preview gambar</small>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
