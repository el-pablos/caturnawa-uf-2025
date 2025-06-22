@extends('layouts.juri')

@section('title', 'Review Submission')
@section('page-title', 'Review Submission Peserta')

@section('sidebar-menu')
    <a class="nav-link" href="{{ route('juri.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a class="nav-link" href="{{ route('juri.competitions.index') }}">
        <i class="bi bi-trophy me-2"></i>Kompetisi Saya
    </a>
    <a class="nav-link" href="{{ route('juri.scoring.index') }}">
        <i class="bi bi-star me-2"></i>Penilaian
    </a>
    <a class="nav-link active" href="{{ route('juri.submissions.index') }}">
        <i class="bi bi-file-earmark-text me-2"></i>Review Submission
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter Submission</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('juri.submissions.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="competition_id" class="form-label">Kompetisi</label>
                            <select name="competition_id" id="competition_id" class="form-select">
                                <option value="">Semua Kompetisi</option>
                                @foreach($competitions as $competition)
                                    <option value="{{ $competition->id }}" {{ request('competition_id') == $competition->id ? 'selected' : '' }}>
                                        {{ $competition->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status Penilaian</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Dinilai</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="final" {{ request('status') == 'final' ? 'selected' : '' }}>Final</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('juri.submissions.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Daftar Submission</h5>
            </div>
            <div class="card-body">
                @if($submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Peserta</th>
                                    <th>Kompetisi</th>
                                    <th>Tanggal Submit</th>
                                    <th>Status Penilaian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $submission)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    @if($submission->registration->user->profile_photo)
                                                        <img src="{{ $submission->registration->user->profile_photo_url }}" 
                                                             class="rounded-circle" width="40" height="40" alt="Avatar">
                                                    @else
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <span class="text-white fw-bold">
                                                                {{ substr($submission->registration->user->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $submission->registration->user->name }}</div>
                                                    <small class="text-muted">{{ $submission->registration->display_name }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $submission->registration->competition->name }}</div>
                                            <small class="text-muted">{{ $submission->registration->competition->category }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y') : 'Belum submit' }}</div>
                                            <small class="text-muted">{{ $submission->submitted_at ? $submission->submitted_at->format('H:i') : '' }}</small>
                                        </td>
                                        <td>
                                            @if($submission->jury_score)
                                                @if($submission->jury_score->is_final)
                                                    <span class="badge bg-success">Final</span>
                                                @else
                                                    <span class="badge bg-warning">Draft</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Belum Dinilai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('juri.submissions.show', $submission) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>Lihat
                                                </a>
                                                <a href="{{ route('juri.scoring.submission', $submission) }}" 
                                                   class="btn btn-outline-success">
                                                    <i class="bi bi-star me-1"></i>Nilai
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $submissions->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-text fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak Ada Submission</h5>
                        <p class="text-muted">Belum ada submission yang perlu direview.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.avatar-sm img {
    object-fit: cover;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush
