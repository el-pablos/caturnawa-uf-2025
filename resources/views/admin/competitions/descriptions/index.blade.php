@extends('layouts.admin')

@section('title', 'Kelola Deskripsi - ' . $competition->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Kelola Deskripsi</h1>
            <p class="text-muted">{{ $competition->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.competitions.show', $competition) }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.competitions.descriptions.create', $competition) }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Deskripsi
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Terms & Conditions Special Form -->
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-text me-2"></i>
                Syarat & Ketentuan
                <span class="badge bg-light text-primary ms-2">Editor Khusus</span>
            </h5>
        </div>
        <div class="card-body">
            <form id="termsForm" action="{{ route('admin.competitions.descriptions.update-terms', $competition) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="terms_content" class="form-label">
                        <i class="bi bi-pencil-square me-1"></i>Isi Syarat & Ketentuan
                    </label>
                    <div class="form-text mb-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Gunakan format berikut untuk membuat syarat & ketentuan yang terstruktur:
                    </div>

                    <!-- Template Guide -->
                    <div class="alert alert-info">
                        <h6><i class="bi bi-lightbulb me-1"></i>Template Format:</h6>
                        <small>
                            <strong>1. Ketentuan Umum</strong><br>
                            - Poin ketentuan umum 1<br>
                            - Poin ketentuan umum 2<br><br>

                            <strong>2. Persyaratan Peserta</strong><br>
                            - Persyaratan 1<br>
                            - Persyaratan 2<br><br>

                            <strong>3. Ketentuan Kompetisi</strong><br>
                            - Ketentuan kompetisi 1<br>
                            - Ketentuan kompetisi 2<br><br>

                            <strong>4. Hak dan Kewajiban</strong><br>
                            - Hak peserta<br>
                            - Kewajiban peserta<br><br>

                            <strong>5. Sanksi dan Diskualifikasi</strong><br>
                            - Kondisi sanksi<br>
                            - Proses diskualifikasi
                        </small>
                    </div>

                    <textarea
                        name="terms_content"
                        id="terms_content"
                        class="form-control @error('terms_content') is-invalid @enderror"
                        rows="15"
                        placeholder="Masukkan syarat & ketentuan kompetisi di sini...">{{ old('terms_content', $termsAndConditions->content ?? '') }}</textarea>

                    @error('terms_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        @if($termsAndConditions)
                            <i class="bi bi-clock me-1"></i>
                            Terakhir diperbarui: {{ $termsAndConditions->updated_at->format('d M Y, H:i') }}
                            @if($termsAndConditions->updater)
                                oleh {{ $termsAndConditions->updater->name }}
                            @endif
                        @else
                            <i class="bi bi-info-circle me-1"></i>
                            Belum ada syarat & ketentuan yang dibuat
                        @endif
                    </div>

                    <div>
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="fillTemplate()">
                            <i class="bi bi-magic me-1"></i>Isi Template
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-floppy me-1"></i>
                            {{ $termsAndConditions ? 'Perbarui' : 'Simpan' }} Syarat & Ketentuan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Descriptions by Section -->
    @if($descriptions->count() > 0)
        @foreach($descriptions as $section => $sectionDescriptions)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-folder me-2"></i>
                        {{ ucfirst($section) }}
                        <span class="badge bg-primary ms-2">{{ $sectionDescriptions->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Urutan</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Diperbarui</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sectionDescriptions as $description)
                                    <tr>
                                        <td>
                                            <strong>{{ $description->title }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ Str::limit(strip_tags($description->content), 100) }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $description->order }}</span>
                                        </td>
                                        <td>
                                            @if($description->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                {{ $description->created_at->format('d M Y, H:i') }}
                                                @if($description->creator)
                                                    <br>oleh {{ $description->creator->name }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <small>
                                                {{ $description->updated_at->format('d M Y, H:i') }}
                                                @if($description->updater)
                                                    <br>oleh {{ $description->updater->name }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.competitions.descriptions.edit', [$competition, $description]) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Edit Deskripsi">
                                                    <i class="bi bi-pencil-square"></i>
                                                    <span class="d-none d-md-inline ms-1">Edit</span>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDelete({{ $description->id }})"
                                                        title="Hapus Deskripsi">
                                                    <i class="bi bi-trash3"></i>
                                                    <span class="d-none d-md-inline ms-1">Hapus</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-text text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Belum Ada Deskripsi</h4>
                <p class="text-muted">Mulai dengan menambahkan deskripsi pertama untuk kompetisi ini.</p>
                <a href="{{ route('admin.competitions.descriptions.create', $competition) }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Deskripsi
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus deskripsi ini?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(descriptionId) {
    const form = document.getElementById('deleteForm');
    form.action = `{{ route('admin.competitions.descriptions.index', $competition) }}/${descriptionId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Debug form submission
document.getElementById('termsForm').addEventListener('submit', function(e) {
    console.log('Form submission:', {
        action: this.action,
        method: this.method,
        csrf: this.querySelector('input[name="_token"]').value,
        methodField: this.querySelector('input[name="_method"]').value
    });
});

// Fill template for Terms & Conditions
function fillTemplate() {
    const template = `1. KETENTUAN UMUM
- Kompetisi ini terbuka untuk semua peserta yang memenuhi syarat
- Peserta wajib mengikuti seluruh ketentuan yang berlaku
- Keputusan panitia bersifat final dan tidak dapat diganggu gugat
- Panitia berhak mengubah ketentuan sewaktu-waktu dengan pemberitahuan

2. PERSYARATAN PESERTA
- Peserta adalah individu atau tim sesuai kategori kompetisi
- Peserta wajib melakukan registrasi dan pembayaran sesuai ketentuan
- Peserta wajib menyertakan dokumen yang diperlukan
- Peserta bertanggung jawab atas kebenaran data yang diberikan

3. KETENTUAN KOMPETISI
- Kompetisi dilaksanakan sesuai timeline yang telah ditentukan
- Peserta wajib mengikuti seluruh tahapan kompetisi
- Karya yang disubmit harus original dan belum pernah dipublikasikan
- Peserta tidak diperkenankan melakukan plagiarisme dalam bentuk apapun

4. HAK DAN KEWAJIBAN
Hak Peserta:
- Mendapatkan sertifikat partisipasi
- Berkompetisi secara fair dan adil
- Mendapatkan feedback dari juri (jika tersedia)

Kewajiban Peserta:
- Mengikuti seluruh ketentuan kompetisi
- Menjaga sportivitas dan etika kompetisi
- Menghormati peserta lain dan panitia

5. SANKSI DAN DISKUALIFIKASI
- Pelanggaran ringan akan mendapat peringatan
- Pelanggaran berat dapat mengakibatkan diskualifikasi
- Tindakan curang atau plagiarisme akan langsung didiskualifikasi
- Peserta yang didiskualifikasi tidak berhak atas pengembalian biaya pendaftaran`;

    document.getElementById('terms_content').value = template;
}
</script>
@endsection
