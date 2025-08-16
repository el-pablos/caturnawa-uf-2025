<!-- Upload Karya Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important; box-shadow: 0 4px 12px rgba(23, 162, 184, 0.25);">
            <div class="card-body text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-3 fw-bold" style="color: #ffffff; text-shadow: 2px 2px 6px rgba(0,0,0,0.8); font-weight: 800;">
                            <i class="bi bi-cloud-upload-fill me-2"></i>
                            Upload Karya Kompetisi
                        </h4>
                        <p class="mb-3 lead" style="color: #ffffff; text-shadow: 1px 1px 4px rgba(0,0,0,0.7); font-weight: 500;">
                            Upload karya Anda sesuai dengan ketentuan masing-masing lomba yang telah dikonfirmasi pembayarannya.
                        </p>
                        <div class="d-flex gap-2">
                            <span class="badge p-2" style="background-color: rgba(255,255,255,0.95); color: #1f2937; font-weight: 600; text-shadow: none;">
                                <i class="bi bi-check-circle me-1"></i>Tanpa Konfirmasi Admin
                            </span>
                            <span class="badge p-2" style="background-color: rgba(255,255,255,0.95); color: #1f2937; font-weight: 600; text-shadow: none;">
                                <i class="bi bi-lightning me-1"></i>Langsung Submit
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-file-earmark-arrow-up display-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registrations with Upload Access -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-list-check me-2 text-success"></i>
                    Kompetisi yang Dapat Diupload Karya
                </h6>
            </div>
            <div class="card-body">
                @php
                    $confirmedRegistrations = auth()->user()->registrations()
                        ->whereIn('status', ['confirmed', 'paid'])
                        ->with(['competition', 'submission'])
                        ->get();
                @endphp

                @forelse($confirmedRegistrations as $registration)
                <div class="card mb-3 border-start border-4 border-success">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    @if($registration->competition->image)
                                    <img src="{{ asset('storage/competitions/' . $registration->competition->image) }}" 
                                         alt="{{ $registration->competition->name }}" 
                                         class="rounded me-3" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $registration->competition->name }}</h6>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-success">{{ ucfirst($registration->status) }}</span>
                                            <span class="badge bg-info">{{ ucfirst($registration->competition->category) }}</span>
                                            @if($registration->team_name)
                                            <span class="badge bg-secondary">{{ $registration->team_name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-muted small">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <i class="bi bi-calendar me-1"></i>
                                            Terdaftar: {{ $registration->created_at->format('d M Y') }}
                                        </div>
                                        <div class="col-sm-6">
                                            <i class="bi bi-person me-1"></i>
                                            {{ $registration->participant_category }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Submission Status -->
                                @if($registration->submission)
                                <div class="mt-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-check text-success me-2"></i>
                                        <span class="text-success fw-semibold">Karya sudah diupload</span>
                                        @if($registration->submission->is_final)
                                        <span class="badge bg-success ms-2">Final</span>
                                        @else
                                        <span class="badge bg-warning ms-2">Draft</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        Upload terakhir: {{ $registration->submission->updated_at->format('d M Y H:i') }}
                                    </small>
                                </div>
                                @else
                                <div class="mt-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                        <span class="text-warning fw-semibold">Belum ada karya yang diupload</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="col-md-4 text-end">
                                @if($registration->submission)
                                <div class="d-grid gap-2">
                                    <a href="{{ route('peserta.submissions.show', $registration->submission) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Lihat Karya
                                    </a>
                                    @if(!$registration->submission->is_final)
                                    <a href="{{ route('peserta.submissions.edit', $registration->submission) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil me-1"></i>Edit Draft
                                    </a>
                                    @endif
                                </div>
                                @else
                                <div class="d-grid">
                                    <a href="{{ route('peserta.submissions.create', ['registration' => $registration->id]) }}" 
                                       class="btn btn-success">
                                        <i class="bi bi-cloud-upload me-1"></i>Upload Karya
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                    <h5 class="text-muted mt-3">Belum Ada Kompetisi yang Dikonfirmasi</h5>
                    <p class="text-muted">
                        Anda perlu mendaftar kompetisi dan menyelesaikan pembayaran terlebih dahulu<br>
                        sebelum dapat mengupload karya.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('peserta.competitions.index') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Daftar Kompetisi
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Upload Requirements Info -->
@if($confirmedRegistrations->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning bg-opacity-10">
                <h6 class="mb-0 text-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    Ketentuan Upload Karya
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Ketentuan Umum:</h6>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success me-2"></i>Setiap lomba memiliki ketentuan berbeda</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Upload langsung tanpa konfirmasi admin</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Dapat edit draft sebelum submit final</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Perhatikan deadline masing-masing lomba</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Format File:</h6>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-file-pdf text-danger me-2"></i>PDF untuk dokumen</li>
                            <li><i class="bi bi-file-image text-primary me-2"></i>JPG/PNG untuk gambar</li>
                            <li><i class="bi bi-file-play text-success me-2"></i>MP4 untuk video</li>
                            <li><i class="bi bi-file-zip text-warning me-2"></i>ZIP untuk multiple files</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    <strong>Tips:</strong> Pastikan Anda sudah mendapat konfirmasi dari contact person WhatsApp 
                    sebelum mengupload karya untuk memastikan pembayaran sudah terverifikasi.
                </div>
            </div>
        </div>
    </div>
</div>
@endif
