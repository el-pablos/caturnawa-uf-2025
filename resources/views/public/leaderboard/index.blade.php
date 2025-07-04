@extends('layouts.caturnawa-2025')

@section('title', 'Leaderboard - UNAS Fest 2025')

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="hero-title text-white mb-3">
                    <i class="bi bi-trophy-fill me-3"></i>Leaderboard
                </h1>
                <p class="hero-subtitle text-white-50 mb-4">
                    Lihat peringkat terbaru peserta kompetisi UNAS Fest 2025
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    @if($competitions->count() > 0)
        <!-- Competition Filter -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Pilih Kompetisi:</label>
                            </div>
                            <div class="col-md-9">
                                <select id="competitionSelect" class="form-select">
                                    @foreach($competitions as $competition)
                                        <option value="{{ $competition->id }}" 
                                                {{ $selectedCompetition && $selectedCompetition->id === $competition->id ? 'selected' : '' }}>
                                            {{ $competition->name }} ({{ $competition->category }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($selectedCompetition && $leaderboard->count() > 0)
            <!-- Competition Info -->
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h4 class="text-primary mb-2">{{ $selectedCompetition->name }}</h4>
                            <p class="text-muted mb-0">
                                <span class="badge bg-primary me-2">{{ $selectedCompetition->category }}</span>
                                <span class="text-muted">{{ $leaderboard->count() }} Peserta</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 3 Podium -->
            @if($leaderboard->count() >= 3)
                <div class="row mb-5">
                    <div class="col-lg-10 mx-auto">
                        <div class="podium-container">
                            <div class="row text-center">
                                <!-- 2nd Place -->
                                <div class="col-md-4 order-md-1 mb-4">
                                    <div class="podium-card podium-second">
                                        <div class="podium-rank">
                                            <i class="bi bi-award-fill text-secondary"></i>
                                            <span class="rank-number">2</span>
                                        </div>
                                        <div class="podium-content">
                                            <h5 class="participant-name">{{ $leaderboard[1]['participant_name'] }}</h5>
                                            @if($leaderboard[1]['team_name'])
                                                <p class="team-name text-muted">{{ $leaderboard[1]['team_name'] }}</p>
                                            @endif
                                            <p class="submission-title">{{ $leaderboard[1]['submission_title'] }}</p>
                                            <div class="score-display">
                                                <span class="score-number">{{ $leaderboard[1]['average_score'] }}</span>
                                                <small class="text-muted">/100</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 1st Place -->
                                <div class="col-md-4 order-md-2 mb-4">
                                    <div class="podium-card podium-first">
                                        <div class="podium-rank">
                                            <i class="bi bi-trophy-fill text-warning"></i>
                                            <span class="rank-number">1</span>
                                        </div>
                                        <div class="podium-content">
                                            <h5 class="participant-name">{{ $leaderboard[0]['participant_name'] }}</h5>
                                            @if($leaderboard[0]['team_name'])
                                                <p class="team-name text-muted">{{ $leaderboard[0]['team_name'] }}</p>
                                            @endif
                                            <p class="submission-title">{{ $leaderboard[0]['submission_title'] }}</p>
                                            <div class="score-display">
                                                <span class="score-number">{{ $leaderboard[0]['average_score'] }}</span>
                                                <small class="text-muted">/100</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3rd Place -->
                                <div class="col-md-4 order-md-3 mb-4">
                                    <div class="podium-card podium-third">
                                        <div class="podium-rank">
                                            <i class="bi bi-award-fill text-warning"></i>
                                            <span class="rank-number">3</span>
                                        </div>
                                        <div class="podium-content">
                                            <h5 class="participant-name">{{ $leaderboard[2]['participant_name'] }}</h5>
                                            @if($leaderboard[2]['team_name'])
                                                <p class="team-name text-muted">{{ $leaderboard[2]['team_name'] }}</p>
                                            @endif
                                            <p class="submission-title">{{ $leaderboard[2]['submission_title'] }}</p>
                                            <div class="score-display">
                                                <span class="score-number">{{ $leaderboard[2]['average_score'] }}</span>
                                                <small class="text-muted">/100</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Full Leaderboard Table -->
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">
                                <i class="bi bi-list-ol me-2"></i>Peringkat Lengkap
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" width="80">Rank</th>
                                            <th>Peserta</th>
                                            <th>Karya</th>
                                            <th class="text-center" width="120">Skor</th>
                                            <th class="text-center" width="100">Juri</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaderboard as $item)
                                            <tr class="{{ $item['rank'] <= 3 ? 'table-warning' : '' }}">
                                                <td class="text-center">
                                                    @if($item['rank'] === 1)
                                                        <span class="badge bg-warning text-dark fs-6">
                                                            <i class="bi bi-trophy-fill me-1"></i>{{ $item['rank'] }}
                                                        </span>
                                                    @elseif($item['rank'] === 2)
                                                        <span class="badge bg-secondary fs-6">
                                                            <i class="bi bi-award-fill me-1"></i>{{ $item['rank'] }}
                                                        </span>
                                                    @elseif($item['rank'] === 3)
                                                        <span class="badge bg-warning text-dark fs-6">
                                                            <i class="bi bi-award-fill me-1"></i>{{ $item['rank'] }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-dark fs-6">{{ $item['rank'] }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $item['participant_name'] }}</strong>
                                                        @if($item['team_name'])
                                                            <br><small class="text-muted">Tim: {{ $item['team_name'] }}</small>
                                                        @endif
                                                        @if($item['institution'])
                                                            <br><small class="text-muted">{{ $item['institution'] }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <strong>{{ $item['submission_title'] }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary fs-6">{{ $item['average_score'] }}/100</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-muted">{{ $item['total_juries'] }} juri</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($selectedCompetition)
            <!-- No Data -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-trophy fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Penilaian</h5>
                            <p class="text-muted">Leaderboard untuk kompetisi {{ $selectedCompetition->name }} belum tersedia.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- No Competitions -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-trophy fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Kompetisi</h5>
                        <p class="text-muted">Leaderboard akan tersedia setelah ada kompetisi yang aktif.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 100px 0 60px;
    margin-top: -80px;
    position: relative;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
}

/* Podium Styles */
.podium-container {
    margin: 2rem 0;
}

.podium-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 2rem 1rem;
    position: relative;
    transition: all 0.3s ease;
    border: 3px solid transparent;
}

.podium-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.podium-first {
    transform: scale(1.05);
    border-color: #ffd700;
    background: linear-gradient(145deg, #fff9e6, #ffffff);
}

.podium-second {
    border-color: #c0c0c0;
    background: linear-gradient(145deg, #f8f9fa, #ffffff);
}

.podium-third {
    border-color: #cd7f32;
    background: linear-gradient(145deg, #fff5e6, #ffffff);
}

.podium-rank {
    margin-bottom: 1rem;
    position: relative;
}

.podium-rank i {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.rank-number {
    position: absolute;
    top: -10px;
    right: 1rem;
    background: rgba(0,0,0,0.1);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    color: #333;
}

.participant-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-size: 1.1rem;
}

.team-name {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    color: #6c757d;
}

.submission-title {
    font-weight: 500;
    color: #495057;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    line-height: 1.4;
}

.score-display {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.score-number {
    font-size: 2rem;
    font-weight: bold;
    color: #667eea;
}

/* Table Styles */
.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
}

.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

/* Card Styles */
.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

/* Badge Styles */
.badge {
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 1rem;
    }

    .podium-first {
        transform: none;
        order: 1 !important;
    }

    .podium-card {
        padding: 1.5rem 1rem;
        margin-bottom: 1rem;
    }

    .score-number {
        font-size: 1.5rem;
    }

    .rank-number {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }

    .table-responsive {
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .hero-section {
        padding: 80px 0 40px;
    }

    .podium-rank i {
        font-size: 2rem;
    }

    .participant-name {
        font-size: 1rem;
    }

    .submission-title {
        font-size: 0.9rem;
    }
}

/* Animation */
.podium-card {
    animation: fadeInUp 0.6s ease-out;
}

.podium-first {
    animation-delay: 0.2s;
}

.podium-second {
    animation-delay: 0.1s;
}

.podium-third {
    animation-delay: 0.3s;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading State */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #667eea;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const competitionSelect = document.getElementById('competitionSelect');

    if (competitionSelect) {
        competitionSelect.addEventListener('change', function() {
            const competitionId = this.value;
            if (competitionId) {
                // Add loading state
                document.body.classList.add('loading');

                // Redirect to new competition
                window.location.href = `{{ route('leaderboard.index') }}?competition=${competitionId}`;
            }
        });
    }

    // Add smooth scroll for better UX
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add hover effects for table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'transform 0.2s ease';
        });

        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
@endpush
