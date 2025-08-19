@extends('layouts.juri')

@section('title', 'Penilaian Karya - ' . $submission->title)

@section('page-title', 'Penilaian Karya')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('juri.scoring.competition', $submission->registration->competition) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Submission Details -->
        <div class="card mb-4">
            <div class="card-header border-success">
                <h5 class="mb-0 text-success">
                    <i class="bi bi-file-earmark-text me-2"></i>{{ $submission->title }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ $submission->registration->team_name ? 'Tim' : 'Peserta' }}:</strong><br>
                        <span class="text-muted">{{ $submission->registration->team_name ?: $submission->registration->user->name }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Institusi:</strong><br>
                        <span class="text-muted">{{ $submission->registration->institution ?? $submission->registration->user->institution ?? 'Tidak diketahui' }}</span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Kompetisi:</strong><br>
                        <span class="badge bg-primary">{{ $submission->registration->competition->name }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Tanggal Submit:</strong><br>
                        <span class="text-muted">{{ $submission->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Deskripsi Karya:</strong>
                    <div class="mt-2 p-3 bg-light rounded">
                        {!! nl2br(e($submission->description)) !!}
                    </div>
                </div>
                
                @if($submission->technologies)
                <div class="mb-3">
                    <strong>Teknologi yang Digunakan:</strong><br>
                    @if(is_array($submission->technologies))
                        @foreach($submission->technologies as $tech)
                            <span class="badge bg-secondary me-1">{{ trim($tech) }}</span>
                        @endforeach
                    @else
                        @foreach(explode(',', $submission->technologies) as $tech)
                            <span class="badge bg-secondary me-1">{{ trim($tech) }}</span>
                        @endforeach
                    @endif
                </div>
                @endif
                
                <div class="row">
                    @if($submission->files && count($submission->files) > 0)
                    <div class="col-md-4 mb-3">
                        <strong>File Karya:</strong><br>
                        @foreach($submission->files as $file)
                            <a href="{{ route('download.submission', [$submission, $file['filename'] ?? $file['name'] ?? '']) }}" 
                               class="btn btn-outline-primary btn-sm mb-1 d-block">
                                <i class="bi bi-download me-1"></i>{{ $file['original_name'] ?? $file['filename'] ?? 'Download File' }}
                            </a>
                        @endforeach
                    </div>
                    @endif
                    
                    @if($submission->video_url)
                    <div class="col-md-4 mb-3">
                        <strong>Video Demo:</strong><br>
                        <a href="{{ $submission->video_url }}" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-play-circle me-1"></i>Lihat Video
                        </a>
                    </div>
                    @endif
                    
                    @if($submission->github_url)
                    <div class="col-md-4 mb-3">
                        <strong>Repository:</strong><br>
                        <a href="{{ $submission->github_url }}" target="_blank" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-github me-1"></i>Lihat Code
                        </a>
                    </div>
                    @endif
                </div>
                
                @if($submission->preview_image)
                <div class="mt-3">
                    <strong>Preview Karya:</strong>
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $submission->preview_image) }}" 
                             alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Scoring Form -->
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-star me-2"></i>Form Penilaian
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('juri.scoring.store', $submission) }}" method="POST" id="scoring-form">
                    @csrf
                    
                    {{-- Competition-specific scoring criteria (moved to controller) --}}

                    {{-- Enhanced competition-specific scoring guidance --}}
                    <div class="alert alert-primary mb-4 border-0 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white rounded-circle p-2 me-3">
                                <i class="bi bi-trophy-fill text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Panduan Penilaian {{ $submission->registration->competition->name }}</h6>
                                <small class="text-primary-emphasis">Parameter khusus kompetisi ini</small>
                            </div>
                        </div>
                        
                        @if (str_contains($competitionName, 'edc') || str_contains($competitionName, 'kdbi'))
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="bg-white rounded p-3 text-center">
                                        <div class="h4 text-primary mb-1">50-100</div>
                                        <small class="text-muted">Range Nilai</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-white rounded p-3 text-center">
                                        <div class="h4 text-success mb-1">0-3</div>
                                        <small class="text-muted">Victory Points</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-white rounded p-3 text-center">
                                        <div class="h4 text-warning mb-1">1-4</div>
                                        <small class="text-muted">Ranking</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 p-3 bg-white rounded">
                                <small><strong>Fokus Penilaian:</strong> Kesesuaian tema, kualitas bukti, kebaruan argumen, delivery style</small>
                            </div>
                        @elseif ($competitionType == 'event_dcc')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="bg-white rounded p-3 text-center">
                                        <div class="h4 text-primary mb-1">0-100</div>
                                        <small class="text-muted">Range Nilai</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-white rounded p-3 text-center">
                                        <div class="h4 text-info mb-1">Multi-Phase</div>
                                        <small class="text-muted">Sistem Penilaian</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 p-3 bg-white rounded">
                                <small><strong>Fokus Penilaian:</strong> Kualitas visual, konten, teknik pembuatan, kreativitas</small>
                            </div>
                        @elseif ($competitionType == 'event_scientific_paper')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="bg-white rounded p-3 text-center">
                                        <div class="h4 text-primary mb-1">40-100</div>
                                        <small class="text-muted">Range Nilai</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-white rounded p-3 text-center">
                                        <div class="h4 text-success mb-1">3 Aspek</div>
                                        <small class="text-muted">Kriteria Utama</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 p-3 bg-white rounded">
                                <small><strong>Fokus Penilaian:</strong> Orisinalitas, metodologi penelitian, analisis data, struktur penulisan</small>
                            </div>
                        @endif
                    </div>

                    @foreach($criteria as $criterion => $maxScore)
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label for="{{ $criterion }}" class="form-label mb-0">
                                    <strong class="text-dark">
                                        @php
                                            $description = $criteriaDescriptions[$criterion] ?? null;
                                            if (is_array($description)) {
                                                echo 'Array Error';
                                            } elseif (is_string($description) && !empty($description)) {
                                                echo $description;
                                            } else {
                                                echo ucfirst(str_replace('_', ' ', $criterion));
                                            }
                                        @endphp
                                    </strong>
                                </label>
                                <span class="badge bg-primary">{{ $minScore }}-{{ $maxScore }} poin</span>
                            </div>
                            
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <div class="position-relative">
                                        <input type="range" class="form-range scoring-range"
                                               id="{{ $criterion }}"
                                               name="criteria[{{ $criterion }}]"
                                               min="{{ $minScore }}" max="{{ $maxScore }}"
                                               value="{{ old('criteria.'.$criterion, ($score && $score->criteria_scores && is_array($score->criteria_scores)) ? ($score->criteria_scores[$criterion] ?? $minScore) : $minScore) }}"
                                               oninput="updateScore('{{ $criterion }}', this.value, {{ $maxScore }})">
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted">{{ $minScore }}</small>
                                            <small class="text-muted">{{ intval($maxScore/2) }}</small>
                                            <small class="text-muted">{{ $maxScore }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control text-center fw-bold scoring-input"
                                               id="{{ $criterion }}_input"
                                               min="{{ $minScore }}" max="{{ $maxScore }}"
                                               value="{{ old('criteria.'.$criterion, ($score && $score->criteria_scores && is_array($score->criteria_scores)) ? ($score->criteria_scores[$criterion] ?? $minScore) : $minScore) }}"
                                               onchange="updateRange('{{ $criterion }}', this.value)">
                                        <span class="input-group-text">/{{ $maxScore }}</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <div class="score-grade fw-bold" id="{{ $criterion }}_grade">-</div>
                                        <small class="text-muted">Grade</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3 p-3 bg-light rounded">
                                <small class="text-muted">
                                    <i class="bi bi-lightbulb me-1"></i>
                                    @php
                                        $description = $criteriaDescriptions[$criterion] ?? null;
                                        if (is_array($description)) {
                                            echo 'Array Data Error';
                                        } elseif (is_string($description) && !empty($description)) {
                                            echo $description;
                                        } else {
                                            echo 'Berikan penilaian sesuai kriteria ' . str_replace('_', ' ', $criterion);
                                        }
                                    @endphp
                                </small>
                            </div>
                        
                            {{-- Enhanced scoring guidance for each competition type --}}
                            @if (str_contains($competitionName, 'edc') || str_contains($competitionName, 'kdbi'))
                                <div class="mt-3">
                                    <div class="accordion" id="accordion{{ $criterion }}">
                                        <div class="accordion-item border-0">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed bg-transparent border-0 p-2" type="button" 
                                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $criterion }}" 
                                                        style="box-shadow: none;">
                                                    <small><i class="bi bi-chevron-down me-2"></i>Lihat Panduan Detail</small>
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $criterion }}" class="accordion-collapse collapse" 
                                                 data-bs-parent="#accordion{{ $criterion }}">
                                                <div class="accordion-body p-2">
                                                    <div class="row g-2">
                                                        <div class="col-4"><span class="badge bg-danger">50-55</span><br><small>Tidak sejalan tema</small></div>
                                                        <div class="col-4"><span class="badge bg-warning">61-65</span><br><small>Mulai terorganisir</small></div>
                                                        <div class="col-4"><span class="badge bg-info">71-75</span><br><small>Sistematis</small></div>
                                                        <div class="col-4"><span class="badge bg-primary">81-85</span><br><small>Pemahaman baik</small></div>
                                                        <div class="col-4"><span class="badge bg-success">91-95</span><br><small>Argumentasi mendalam</small></div>
                                                        <div class="col-4"><span class="badge bg-success">96-100</span><br><small>Orisinalitas tinggi</small></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($competitionType == 'event_dcc')
                                <div class="mt-3">
                                    <div class="row g-2 text-center">
                                        <div class="col-3"><span class="badge bg-danger">0-40</span><br><small>Kurang</small></div>
                                        <div class="col-3"><span class="badge bg-warning">41-60</span><br><small>Cukup</small></div>
                                        <div class="col-3"><span class="badge bg-info">61-80</span><br><small>Baik</small></div>
                                        <div class="col-3"><span class="badge bg-success">81-100</span><br><small>Sangat Baik</small></div>
                                    </div>
                                </div>
                            @elseif ($competitionType == 'event_scientific_paper')
                                <div class="mt-3">
                                    <div class="row g-2 text-center">
                                        <div class="col-3"><span class="badge bg-danger">40-50</span><br><small>Kurang</small></div>
                                        <div class="col-3"><span class="badge bg-warning">60-70</span><br><small>Cukup</small></div>
                                        <div class="col-3"><span class="badge bg-info">80-90</span><br><small>Baik</small></div>
                                        <div class="col-3"><span class="badge bg-success">90-100</span><br><small>Sangat Baik</small></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="mb-4">
                        <label for="comments" class="form-label">
                            <strong>Komentar dan Feedback</strong>
                        </label>
                        <textarea class="form-control" id="comments" name="comments" rows="5" 
                                  placeholder="Berikan feedback konstruktif untuk peserta...">{{ old('comments', ($score && is_string($score?->comments)) ? $score->comments : '') }}</textarea>
                        <div class="form-text">Komentar ini akan membantu peserta untuk pengembangan selanjutnya</div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_final" name="is_final" value="1"
                                   {{ old('is_final', $score?->is_final ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_final">
                                <strong>Finalisasi Penilaian</strong>
                            </label>
                        </div>
                        <div class="form-text text-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Setelah difinalisasi, penilaian tidak dapat diubah lagi
                        </div>
                    </div>
                    
                    {{-- Enhanced Total Score Display --}}
                    <div class="card border-0 shadow-sm bg-gradient">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="display-4 fw-bold text-primary mb-1" id="total-score">0</div>
                                        <small class="text-muted">Total Nilai</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="display-6 fw-bold" id="grade" style="color: #6c757d;">-</div>
                                        <small class="text-muted">Grade</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="display-6 fw-bold text-success" id="percentage">0%</div>
                                        <small class="text-muted">Persentase</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Progress Penilaian</small>
                                    <small class="text-muted" id="progress-text">0/100</small>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         id="score-progress" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card border-warning bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-warning mb-2">
                                        <i class="bi bi-clock-history me-2"></i>Status Penilaian
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-{{ $score && $score->is_final ? 'success' : 'warning' }} me-2">
                                            {{ $score && $score->is_final ? 'Final' : 'Draft' }}
                                        </span>
                                        @if($score && $score->updated_at && method_exists($score->updated_at, 'diffForHumans'))
                                            <small class="text-muted">Terakhir update: {{ $score->updated_at->diffForHumans() }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end gap-2 h-100 align-items-center">
                                <a href="{{ route('juri.scoring.competition', $submission->registration->competition) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" name="action" value="draft" class="btn btn-primary">
                                    <i class="bi bi-cloud-upload me-2"></i>Simpan Draft
                                </button>
                                <button type="submit" name="action" value="final" class="btn btn-success" 
                                        {{ $score && $score->is_final ? 'disabled' : '' }}>
                                    <i class="bi bi-check-circle-fill me-2"></i>Finalisasi
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Scoring Guidelines -->
        <div class="card mb-3">
            <div class="card-header border-info">
                <h6 class="mb-0 text-info">
                    <i class="bi bi-book me-2"></i>Panduan Penilaian
                </h6>
            </div>
            <div class="card-body">
                <div class="accordion" id="guidelinesAccordion">
                    @foreach($criteria as $criterion => $maxScore)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $criterion }}">
                                {{ ucfirst(str_replace('_', ' ', $criterion)) }} ({{ $maxScore }} poin)
                            </button>
                        </h2>
                        <div id="collapse{{ $criterion }}" class="accordion-collapse collapse" 
                             data-bs-parent="#guidelinesAccordion">
                            <div class="accordion-body">
                                @switch($criterion)
                                    @case('creativity')
                                        <ul class="mb-0">
                                            <li><strong>Excellent (21-25):</strong> Sangat kreatif dan inovatif</li>
                                            <li><strong>Good (16-20):</strong> Kreatif dengan beberapa inovasi</li>
                                            <li><strong>Fair (11-15):</strong> Cukup kreatif</li>
                                            <li><strong>Poor (6-10):</strong> Kurang kreatif</li>
                                            <li><strong>Very Poor (0-5):</strong> Tidak kreatif</li>
                                        </ul>
                                        @break
                                    @case('technical')
                                        <ul class="mb-0">
                                            <li><strong>Excellent (21-25):</strong> Implementasi teknis sangat baik</li>
                                            <li><strong>Good (16-20):</strong> Implementasi teknis baik</li>
                                            <li><strong>Fair (11-15):</strong> Implementasi teknis cukup</li>
                                            <li><strong>Poor (6-10):</strong> Implementasi teknis kurang</li>
                                            <li><strong>Very Poor (0-5):</strong> Implementasi teknis buruk</li>
                                        </ul>
                                        @break
                                    @case('presentation')
                                        <ul class="mb-0">
                                            <li><strong>Excellent (21-25):</strong> Presentasi sangat jelas dan menarik</li>
                                            <li><strong>Good (16-20):</strong> Presentasi jelas dan baik</li>
                                            <li><strong>Fair (11-15):</strong> Presentasi cukup jelas</li>
                                            <li><strong>Poor (6-10):</strong> Presentasi kurang jelas</li>
                                            <li><strong>Very Poor (0-5):</strong> Presentasi tidak jelas</li>
                                        </ul>
                                        @break
                                    @case('innovation')
                                        <ul class="mb-0">
                                            <li><strong>Excellent (21-25):</strong> Sangat inovatif dan berdampak</li>
                                            <li><strong>Good (16-20):</strong> Inovatif dengan dampak baik</li>
                                            <li><strong>Fair (11-15):</strong> Cukup inovatif</li>
                                            <li><strong>Poor (6-10):</strong> Kurang inovatif</li>
                                            <li><strong>Very Poor (0-5):</strong> Tidak inovatif</li>
                                        </ul>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Previous Scores (if any) -->
        @if($score && $score->created_at != $score->updated_at)
        <div class="card mb-3">
            <div class="card-header border-secondary">
                <h6 class="mb-0 text-secondary">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Penilaian
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Dibuat:</strong> {{ $score->created_at->format('d M Y H:i') }}
                </div>
                <div class="mb-2">
                    <strong>Terakhir Update:</strong> {{ $score->updated_at->format('d M Y H:i') }}
                </div>
                <div class="mb-2">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $score->is_final ? 'success' : 'warning' }}">
                        {{ $score->is_final ? 'Final' : 'Draft' }}
                    </span>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Enhanced Quick Actions & AI Assistant -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient-primary text-white border-0">
                <h6 class="mb-0">
                    <i class="bi bi-magic me-2"></i>Smart Scoring Assistant
                </h6>
            </div>
            <div class="card-body">
                {{-- Competition-specific quick scores --}}
                @if (str_contains($competitionName, 'edc') || str_contains($competitionName, 'kdbi'))
                    <div class="d-grid gap-2 mb-3">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="setQuickScore(95)">
                            <i class="bi bi-star-fill me-1"></i>Excellent (95) - Argumentasi Mendalam
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickScore(85)">
                            <i class="bi bi-star me-1"></i>Good (85) - Pemahaman Baik
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="setQuickScore(75)">
                            <i class="bi bi-star me-1"></i>Fair (75) - Sistematis
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="setQuickScore(65)">
                            <i class="bi bi-star me-1"></i>Basic (65) - Mulai Terorganisir
                        </button>
                    </div>
                @elseif ($competitionType == 'event_dcc')
                    <div class="d-grid gap-2 mb-3">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="setQuickScore(90)">
                            <i class="bi bi-palette me-1"></i>Sangat Baik (90) - Kreatif & Teknis
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickScore(80)">
                            <i class="bi bi-eye me-1"></i>Baik (80) - Visual Menarik
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="setQuickScore(70)">
                            <i class="bi bi-check me-1"></i>Cukup (70) - Memenuhi Standar
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="setQuickScore(60)">
                            <i class="bi bi-pencil me-1"></i>Perlu Perbaikan (60)
                        </button>
                    </div>
                @else
                    <div class="d-grid gap-2 mb-3">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="setQuickScore(85)">
                            <i class="bi bi-star-fill me-1"></i>Excellent (85)
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickScore(75)">
                            <i class="bi bi-star me-1"></i>Good (75)
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="setQuickScore(65)">
                            <i class="bi bi-star me-1"></i>Fair (65)
                        </button>
                    </div>
                @endif
                
                <hr class="my-3">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="autoBalance()">
                        <i class="bi bi-sliders me-1"></i>Auto Balance Scores
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="resetScores()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Semua
                    </button>
                </div>
                
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">
                        <i class="bi bi-lightbulb me-1"></i>
                        <strong>Tips:</strong> Gunakan tombol quick score sebagai baseline, lalu sesuaikan detail per kriteria
                    </small>
                </div>
            </div>
        </div>
        
        {{-- Score Distribution Chart --}}
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-info text-white border-0">
                <h6 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>Distribusi Nilai
                </h6>
            </div>
            <div class="card-body">
                <canvas id="scoreDistributionChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.scoring-range {
    background: linear-gradient(to right, #dc3545 0%, #ffc107 50%, #198754 100%);
    height: 8px;
}

.scoring-range::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #0d6efd;
    cursor: pointer;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.scoring-input {
    font-size: 1.2em;
    height: 45px;
}

.score-grade {
    font-size: 1.5em;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #0d6efd, #6610f2);
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.accordion-button:focus {
    box-shadow: none;
}

@keyframes scoreUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.score-updated {
    animation: scoreUpdate 0.3s ease-in-out;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
    
    // Auto-save draft every 2 minutes
    setInterval(function() {
        if (document.getElementById('is_final').checked === false) {
            saveDraft();
        }
    }, 120000);
    
    // Add smooth animations to score updates
    const inputs = document.querySelectorAll('.scoring-input');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.add('score-updated');
            setTimeout(() => {
                this.classList.remove('score-updated');
            }, 300);
        });
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            saveDraft();
        }
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            document.querySelector('button[value="final"]').click();
        }
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function updateScore(criterion, value, maxScore) {
    document.getElementById(criterion + '_input').value = value;
    document.getElementById(criterion).value = value;
    
    // Add visual feedback
    const inputElement = document.getElementById(criterion + '_input');
    inputElement.classList.add('score-updated');
    setTimeout(() => {
        inputElement.classList.remove('score-updated');
    }, 300);
    
    calculateTotal();
}

function updateRange(criterion, value) {
    document.getElementById(criterion).value = value;
    
    // Add visual feedback
    const rangeElement = document.getElementById(criterion);
    rangeElement.classList.add('score-updated');
    setTimeout(() => {
        rangeElement.classList.remove('score-updated');
    }, 300);
    
    calculateTotal();
}

function calculateTotal() {
    const criteria = @json(array_keys($criteria));
    const criteriaObj = @json($criteria);
    let total = 0;
    let maxTotal = 0;
    let scoreData = [];
    
    criteria.forEach(function(criterion) {
        const value = parseInt(document.getElementById(criterion + '_input').value) || 0;
        const maxValue = criteriaObj[criterion];
        total += value;
        maxTotal += maxValue;
        
        // Update individual grade
        updateCriterionGrade(criterion, value, maxValue);
        scoreData.push({criterion: criterion, score: value, max: maxValue});
    });
    
    document.getElementById('total-score').textContent = total;
    
    // Update percentage
    const percentage = maxTotal > 0 ? Math.round((total / maxTotal) * 100) : 0;
    document.getElementById('percentage').textContent = percentage + '%';
    document.getElementById('progress-text').textContent = total + '/' + maxTotal;
    
    // Update progress bar
    const progressBar = document.getElementById('score-progress');
    progressBar.style.width = percentage + '%';
    
    // Enhanced grade calculation based on competition type
    const competitionType = '{{ $competitionType }}';
    const competitionName = '{{ $competitionName }}';
    let grade = 'F';
    let progressClass = 'bg-danger';
    let gradeColor = '#dc3545';
    
    if (competitionType === 'event_debate' || competitionName.includes('edc') || competitionName.includes('kdbi')) {
        // Debate competitions: 50-100 range
        if (percentage >= 95) {
            grade = 'A+';
            progressClass = 'bg-success';
            gradeColor = '#198754';
        } else if (percentage >= 90) {
            grade = 'A';
            progressClass = 'bg-success';
            gradeColor = '#198754';
        } else if (percentage >= 85) {
            grade = 'A-';
            progressClass = 'bg-success';
            gradeColor = '#198754';
        } else if (percentage >= 80) {
            grade = 'B+';
            progressClass = 'bg-primary';
            gradeColor = '#0d6efd';
        } else if (percentage >= 75) {
            grade = 'B';
            progressClass = 'bg-info';
            gradeColor = '#0dcaf0';
        } else if (percentage >= 70) {
            grade = 'B-';
            progressClass = 'bg-info';
            gradeColor = '#0dcaf0';
        } else if (percentage >= 65) {
            grade = 'C+';
            progressClass = 'bg-warning';
            gradeColor = '#ffc107';
        } else if (percentage >= 60) {
            grade = 'C';
            progressClass = 'bg-warning';
            gradeColor = '#ffc107';
        } else {
            grade = 'D';
            progressClass = 'bg-danger';
            gradeColor = '#dc3545';
        }
    } else {
        // Other competitions: 0-100 range
        if (percentage >= 90) {
            grade = 'A';
            progressClass = 'bg-success';
            gradeColor = '#198754';
        } else if (percentage >= 80) {
            grade = 'B';
            progressClass = 'bg-primary';
            gradeColor = '#0d6efd';
        } else if (percentage >= 70) {
            grade = 'C';
            progressClass = 'bg-info';
            gradeColor = '#0dcaf0';
        } else if (percentage >= 60) {
            grade = 'D';
            progressClass = 'bg-warning';
            gradeColor = '#ffc107';
        } else {
            grade = 'F';
            progressClass = 'bg-danger';
            gradeColor = '#dc3545';
        }
    }
    
    const gradeElement = document.getElementById('grade');
    gradeElement.textContent = grade;
    gradeElement.style.color = gradeColor;
    progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated ' + progressClass;
    
    // Update chart if exists
    updateScoreChart(scoreData);
}

function updateCriterionGrade(criterion, score, maxScore) {
    const percentage = maxScore > 0 ? Math.round((score / maxScore) * 100) : 0;
    const gradeElement = document.getElementById(criterion + '_grade');
    
    let grade = 'F';
    let color = '#dc3545';
    
    if (percentage >= 90) {
        grade = 'A';
        color = '#198754';
    } else if (percentage >= 80) {
        grade = 'B';
        color = '#0d6efd';
    } else if (percentage >= 70) {
        grade = 'C';
        color = '#0dcaf0';
    } else if (percentage >= 60) {
        grade = 'D';
        color = '#ffc107';
    }
    
    if (gradeElement) {
        gradeElement.textContent = grade;
        gradeElement.style.color = color;
    }
}

// Auto balance scores based on target total
function autoBalance() {
    const criteria = @json($criteria);
    const criteriaKeys = Object.keys(criteria);
    const targetTotal = prompt('Masukkan target total nilai (contoh: 85):', '85');
    
    if (!targetTotal || isNaN(targetTotal)) return;
    
    const target = parseInt(targetTotal);
    let totalWeight = 0;
    
    // Calculate total weight
    criteriaKeys.forEach(criterion => {
        totalWeight += criteria[criterion];
    });
    
    // Distribute scores proportionally
    criteriaKeys.forEach(criterion => {
        const maxScore = criteria[criterion];
        const proportion = maxScore / totalWeight;
        const calculatedScore = Math.round(target * proportion);
        const finalScore = Math.min(calculatedScore, maxScore);
        
        document.getElementById(criterion).value = finalScore;
        document.getElementById(criterion + '_input').value = finalScore;
    });
    
    calculateTotal();
}

// Update score distribution chart
function updateScoreChart(scoreData) {
    // Implementation for chart update would go here
    // Using Chart.js or similar library
    console.log('Updating chart with data:', scoreData);
}

function setQuickScore(targetTotal) {
    const criteria = @json($criteria);
    const criteriaKeys = Object.keys(criteria);
    const scorePerCriterion = Math.floor(targetTotal / criteriaKeys.length);
    const remainder = targetTotal % criteriaKeys.length;
    
    criteriaKeys.forEach(function(criterion, index) {
        const score = scorePerCriterion + (index < remainder ? 1 : 0);
        const maxScore = criteria[criterion];
        const finalScore = Math.min(score, maxScore);
        
        document.getElementById(criterion).value = finalScore;
        document.getElementById(criterion + '_input').value = finalScore;
    });
    
    calculateTotal();
}

function resetScores() {
    if (confirm('Reset semua nilai ke 0?')) {
        const criteria = @json(array_keys($criteria));
        
        criteria.forEach(function(criterion) {
            document.getElementById(criterion).value = 0;
            document.getElementById(criterion + '_input').value = 0;
        });
        
        calculateTotal();
    }
}

function saveDraft() {
    const formData = new FormData(document.getElementById('scoring-form'));
    formData.append('action', 'draft');
    
    fetch(document.getElementById('scoring-form').action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show temporary success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>Draft tersimpan otomatis
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Auto-save failed:', error);
    });
}

// Form submission handling
document.getElementById('scoring-form').addEventListener('submit', function(e) {
    const action = e.submitter.value;
    
    if (action === 'final') {
        if (!confirm('Finalisasi penilaian? Setelah ini nilai tidak dapat diubah lagi!')) {
            e.preventDefault();
            return false;
        }
        document.getElementById('is_final').checked = true;
    }
});
</script>
@endpush
