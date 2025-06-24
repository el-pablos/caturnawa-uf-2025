@extends('layouts.juri')

@section('title', 'Detail Submission')
@section('page-title', 'Detail Submission')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('juri.submissions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <a href="{{ route('juri.scoring.submission', $submission) }}" class="btn btn-primary">
            <i class="bi bi-star me-2"></i>Beri Nilai
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Submission Details -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>{{ $submission->title }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Peserta</h6>
                        <div class="mb-2">
                            <strong>Nama:</strong> {{ $submission->registration->user->name }}
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> {{ $submission->registration->user->email }}
                        </div>
                        <div class="mb-2">
                            <strong>Institusi:</strong> {{ $submission->registration->institution ?: '-' }}
                        </div>
                        <div class="mb-2">
                            <strong>No. Registrasi:</strong> 
                            <code>{{ $submission->registration->registration_number }}</code>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Kompetisi</h6>
                        <div class="mb-2">
                            <strong>Kompetisi:</strong> {{ $submission->registration->competition->name }}
                        </div>
                        <div class="mb-2">
                            <strong>Kategori:</strong> 
                            <span class="badge bg-primary">{{ ucfirst($submission->registration->competition->category) }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong>
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
                            <strong>Disubmit:</strong> 
                            {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y H:i') : '-' }}
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Deskripsi Karya</h6>
                    <div class="bg-light p-3 rounded">
                        {!! nl2br(e($submission->description)) !!}
                    </div>
                </div>

                @if($submission->video_url)
                <div class="mb-4">
                    <h6 class="text-muted">Video Demo</h6>
                    <a href="{{ $submission->video_url }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-play-circle me-2"></i>Lihat Video
                    </a>
                </div>
                @endif

                @if($submission->repository_url)
                <div class="mb-4">
                    <h6 class="text-muted">Repository</h6>
                    <a href="{{ $submission->repository_url }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-github me-2"></i>Lihat Repository
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Files -->
        @if($submission->files && count($submission->files) > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-files me-2"></i>File Karya
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($submission->files as $file)
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1">{{ $file['original_name'] }}</h6>
                                        <small class="text-muted">
                                            {{ number_format($file['size'] / 1024, 2) }} KB
                                        </small>
                                    </div>
                                    <a href="{{ asset('storage/' . $file['path']) }}" 
                                       class="btn btn-outline-primary btn-sm" target="_blank">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Comments -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-chat-dots me-2"></i>Komentar Juri
                </h6>
            </div>
            <div class="card-body">
                @if($allComments && count($allComments) > 0)
                    @foreach($allComments as $comment)
                    <div class="comment-item mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <strong>{{ $comment->user->name }}</strong>
                            <small class="text-muted">{{ $comment->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <p class="mb-0">{{ $comment->comment }}</p>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">Belum ada komentar dari juri.</p>
                @endif

                <!-- Add Comment Form -->
                <form action="{{ route('juri.submissions.comment', $submission) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-3">
                        <label for="comment" class="form-label">Tambah Komentar</label>
                        <textarea class="form-control @error('comment') is-invalid @enderror" 
                                  id="comment" name="comment" rows="3" 
                                  placeholder="Berikan komentar atau feedback untuk submission ini..."></textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-2"></i>Kirim Komentar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Preview Image -->
        @if($submission->preview_image)
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-image me-2"></i>Preview
                </h6>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $submission->preview_image) }}" 
                     alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
            </div>
        </div>
        @endif

        <!-- Scoring Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-star me-2"></i>Status Penilaian
                </h6>
            </div>
            <div class="card-body">
                @if($myScore)
                    @if($myScore->is_final)
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Penilaian Selesai</strong><br>
                            Anda telah memberikan penilaian final untuk submission ini.
                        </div>
                        <div class="mb-2">
                            <strong>Total Skor:</strong> {{ number_format($myScore->total_score, 1) }}/100
                        </div>
                        <a href="{{ route('juri.scoring.submission', $submission) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye me-2"></i>Lihat Penilaian
                        </a>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-clock me-2"></i>
                            <strong>Draft Tersimpan</strong><br>
                            Anda memiliki draft penilaian yang belum disubmit.
                        </div>
                        <a href="{{ route('juri.scoring.submission', $submission) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil me-2"></i>Lanjutkan Penilaian
                        </a>
                    @endif
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Belum Dinilai</strong><br>
                        Submission ini belum Anda nilai.
                    </div>
                    <a href="{{ route('juri.scoring.submission', $submission) }}" class="btn btn-success btn-sm">
                        <i class="bi bi-star me-2"></i>Mulai Penilaian
                    </a>
                @endif
            </div>
        </div>

        <!-- Submission Timeline -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Timeline
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Submission Dibuat</h6>
                            <small class="text-muted">{{ $submission->created_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                    @if($submission->submitted_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Submission Disubmit</h6>
                            <small class="text-muted">{{ $submission->submitted_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                    @if($myScore && $myScore->is_final)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Dinilai oleh Anda</h6>
                            <small class="text-muted">{{ $myScore->submitted_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.comment-item {
    background: #f8f9fa;
}
</style>
@endpush
