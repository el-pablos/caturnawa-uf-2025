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
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.competitions.descriptions.create', $competition) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Deskripsi
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Descriptions by Section -->
    @if($descriptions->count() > 0)
        @foreach($descriptions as $section => $sectionDescriptions)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-folder me-2"></i>
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
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete({{ $description->id }})">
                                                    <i class="fas fa-trash"></i>
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
                <i class="fas fa-file-alt text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Belum Ada Deskripsi</h4>
                <p class="text-muted">Mulai dengan menambahkan deskripsi pertama untuk kompetisi ini.</p>
                <a href="{{ route('admin.competitions.descriptions.create', $competition) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Deskripsi
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
</script>
@endsection
