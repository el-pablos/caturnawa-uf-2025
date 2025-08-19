@extends('layouts.admin')

@section('title', 'Detail Submission')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Detail Submission</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.submissions.index') }}">Submissions</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">{{ $submission->title ?? 'Belum ada judul' }}</h5>
                        <span class="badge badge-{{ $submission->status_class }} fs-6">
                            {{ $submission->status_label }}
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Kompetisi:</strong> {{ $submission->registration->competition->name ?? 'N/A' }}</p>
                            <p><strong>Peserta:</strong> {{ $submission->registration->user->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $submission->registration->user->email ?? 'N/A' }}</p>
                            <p><strong>Nama Tim:</strong> {{ $submission->team_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Submit:</strong> {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y H:i') : 'Belum disubmit' }}</p>
                            <p><strong>Ukuran File:</strong> {{ $submission->file_size_formatted ?? '0 bytes' }}</p>
                            <p><strong>Jumlah File:</strong> {{ $submission->getFileCount() }}</p>
                            <p><strong>View Count:</strong> {{ $submission->view_count ?? 0 }}</p>
                        </div>
                    </div>

                    @if($submission->description)
                        <div class="mt-4">
                            <h6>Deskripsi</h6>
                            <p class="text-muted">{{ $submission->description }}</p>
                        </div>
                    @endif

                    @if($submission->submission_notes)
                        <div class="mt-4">
                            <h6>Catatan Submission</h6>
                            <p class="text-muted">{{ $submission->submission_notes }}</p>
                        </div>
                    @endif

                    @if($submission->metodologi)
                        <div class="mt-4">
                            <h6>Metodologi</h6>
                            <p class="text-muted">{{ is_array($submission->metodologi) ? implode(', ', $submission->metodologi) : $submission->metodologi }}</p>
                        </div>
                    @endif

                    @if($submission->team_members)
                        <div class="mt-4">
                            <h6>Anggota Tim</h6>
                            @if(is_array($submission->team_members))
                                <ul class="list-group list-group-flush">
                                    @foreach($submission->team_members as $member)
                                        <li class="list-group-item px-0">
                                            {{ is_array($member) ? $member['name'] : $member }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">{{ $submission->team_members }}</p>
                            @endif
                        </div>
                    @endif

                    @if($submission->video_url)
                        <div class="mt-4">
                            <h6>Video URL</h6>
                            <a href="{{ $submission->video_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-play"></i> Lihat Video
                            </a>
                        </div>
                    @endif

                    @if($submission->github_url)
                        <div class="mt-4">
                            <h6>GitHub URL</h6>
                            <a href="{{ $submission->github_url }}" target="_blank" class="btn btn-outline-dark btn-sm">
                                <i class="fab fa-github"></i> Lihat Repository
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Files Section -->
            @if($submission->hasFiles())
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Files</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama File</th>
                                        <th>Ukuran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submission->getDownloadableFiles() as $file)
                                        <tr>
                                            <td>{{ $file['original_name'] }}</td>
                                            <td>{{ number_format($file['size'] / 1024, 2) }} KB</td>
                                            <td>
                                                @if($file['url'])
                                                    <a href="{{ $file['url'] }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download"></i> Download
                                                    </a>
                                                @else
                                                    <span class="text-muted">Tidak tersedia</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Aksi</h5>
                    <div class="d-grid gap-2">
                        @if($submission->status !== 'approved')
                            <button type="button" class="btn btn-success w-100" onclick="approveSubmission({{ $submission->id }})">
                                <i class="bi bi-check-circle"></i> Setujui
                            </button>
                        @endif

                        @if($submission->status !== 'rejected')
                            <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle"></i> Tolak
                            </button>
                        @endif

                        <form action="{{ route('admin.submissions.destroy', $submission) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin menghapus submission ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Scores -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Penilaian Juri</h5>
                    @if($submission->scores->count() > 0)
                        @foreach($submission->scores as $score)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-1">{{ $score->jury->name ?? 'N/A' }}</h6>
                                    <span class="badge badge-{{ $score->is_final ? 'success' : 'warning' }}">
                                        {{ $score->is_final ? 'Final' : 'Draft' }}
                                    </span>
                                </div>
                                <p class="mb-1"><strong>Skor:</strong> {{ $score->total_score }}/100</p>
                                @if($score->comments)
                                    <p class="mb-0 text-muted small">{{ $score->comments }}</p>
                                @endif
                            </div>
                        @endforeach
                        <div class="mt-3 text-center">
                            <strong>Rata-rata: {{ number_format($submission->getAverageScore(), 2) }}/100</strong>
                        </div>
                    @else
                        <p class="text-muted">Belum ada penilaian dari juri.</p>
                    @endif
                </div>
            </div>

            <!-- Competition Info -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Info Kompetisi</h5>
                    <p><strong>Nama:</strong> {{ $submission->registration->competition->name ?? 'N/A' }}</p>
                    <p><strong>Kategori:</strong> {{ $submission->registration->competition->category ?? 'N/A' }}</p>
                    <p><strong>Deadline:</strong> {{ $submission->registration->competition->submission_deadline ? $submission->registration->competition->submission_deadline->format('d M Y H:i') : 'Tidak ada' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.submissions.reject', $submission) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Tolak Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function approveSubmission(submissionId) {
    if (confirm('Apakah Anda yakin ingin menyetujui submission ini?')) {
        fetch(`/admin/submissions/${submissionId}/approve`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyetujui submission');
        });
    }
}
</script>
@endpush
