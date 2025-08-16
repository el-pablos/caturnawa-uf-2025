@extends('layouts.simple')

@section('title', 'Leaderboard - Caturnawa UNAS FEST 2025')
@section('content')
<style>
    html, body {
    max-width: 100%;
    overflow-x: hidden;
}

    .modern-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .modern-hero::before {
        content: '';
        position: abso                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Competition Information
                        </h5>e;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="50" cy="10" r="0.8" fill="rgba(255,255,255,0.12)"/><circle cx="10" cy="60" r="1.2" fill="rgba(255,255,255,0.06)"/><circle cx="90" cy="30" r="0.9" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
        animation: float 20s ease-in-out infinite;
    }

    .modern-hero::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 30s linear infinite;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .modern-title {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(45deg, #fff, #f8f9fa, #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        letter-spacing: -1px;
    }

    .modern-subtitle {
        font-size: 1.3rem;
        font-weight: 300;
        color: rgba(255,255,255,0.9);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        line-height: 1.6;
    }

    .modern-btn {
        background: linear-gradient(45deg, #ff6b6b, #feca57);
        border: none;
        border-radius: 50px;
        padding: 15px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px rgba(255,107,107,0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .modern-btn:hover::before {
        left: 100%;
    }

    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(255,107,107,0.4);
    }

    .modern-btn-outline {
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 13px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white;
        transition: all 0.3s ease;
    }

    .modern-btn-outline:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255,255,255,0.2);
        color: white;
    }

    .floating-trophy {
        font-size: 4rem;
        animation: floatTrophy 3s ease-in-out infinite;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
    }

    @keyframes floatTrophy {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }

    .modern-container {
        position: relative;
        z-index: 1;
    }
        .bubbles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }

    .bubbles li {
        position: absolute;
        list-style: none;
        display: block;
        width: 20px;
        height: 20px;
        background: rgba(255, 255, 255, 0.2);
        animation: animate-bubbles 25s linear infinite;
        bottom: -150px;
        border-radius: 50%;
    }

    .bubbles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
    .bubbles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
    .bubbles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
    .bubbles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
    .bubbles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
    .bubbles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
    .bubbles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
    .bubbles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
    .bubbles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
    .bubbles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

    @keyframes animate-bubbles {
        0% { transform: translateY(0) rotate(0deg); opacity: 1; }
        100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
    }

    @media (max-width: 768px) {
        .modern-title {
            font-size: 2.5rem;
        }

        .modern-subtitle {
            font-size: 1rem;
        }

        .floating-trophy {
            font-size: 3rem;
        }
    }
</style>

<div class="dynamic-bg"></div>
<div class="container my-5">
    <div class="floating-shapes"></div>
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5 mb-5">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                <div class="hero-content text-center">
                    <div class="floating-trophy mb-4">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h1 class="modern-title mb-4">
                        Leaderboard <span style="background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"> UNAS FEST 2025</span>
                    </h1>
                    <p class="modern-subtitle mb-5">
                        View the latest rankings of UNAS FEST 2025 competition participants.
                        Monitor your position and other competitors in real-time.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.competitions') }}" class="btn modern-btn btn-auto w-100">
                                <i class="bi bi-trophy me-2"></i>View Competitions
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.faq') }}" class="btn modern-btn-outline btn-auto w-100">
                                <i class="bi bi-question-circle me-2"></i>About
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($competitions->count() > 0)
        <!-- Competition Filter Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center fw-bold mb-4" style="color: #667eea;">
                    <i class="bi bi-funnel"></i>
                    Filter Competition
                </h2>
            </div>
            <div class="col-12" data-aos="fade-up" data-aos-delay="200">
                <div class="card shadow">
                    <div class="card-header bg-info text-white text-center">
                        <h5 class="mb-0">
                            <i class="bi bi-trophy me-2"></i>Select Competition to View Leaderboard
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Select Competition:</label>
                            </div>
                            <div class="col-md-9">
                                <select id="competitionSelect" class="form-select form-select-lg">
                                    @foreach($competitions as $competition)
                                        <option value="{{ $competition->id }}"
                                                {{ $selectedCompetition && $selectedCompetition->id === $competition->id ? 'selected' : '' }}>
                                            {{ $competition->name }} ({{ \App\Models\Competition::CATEGORIES[$competition->category] ?? $competition->category }})
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
            <!-- Competition Info Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="text-center mb-4" data-aos="fade-up">
                        <i class="bi bi-award text-success"></i>
                        {{ $selectedCompetition->name }}
                    </h2>
                </div>
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white text-center">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Competition Information
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <h3 class="text-primary">{{ $selectedCompetition->category }}</h3>
                                        <p class="text-muted">Category</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <h3 class="text-success">{{ $leaderboard->count() }}</h3>
                                        <p class="text-muted">Total Participants</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <h3 class="text-warning">{{ number_format($selectedCompetition->price ?? 0) }}</h3>
                                        <p class="text-muted">Prize (IDR)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 3 Podium Section -->
            @if($leaderboard->count() >= 3)
                <div class="row mb-5">
                    <div class="col-12">
                        <h2 class="text-center mb-4">
                            <i class="bi bi-trophy-fill text-warning"></i>
                            Top 3 Winners
                        </h2>
                    </div>
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header bg-warning text-dark text-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-star me-2"></i>Winner Podium
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                <!-- 2nd Place -->
                                <div class="col-md-4 order-md-1 mb-4">
                                    <div class="card border-secondary">
                                        <div class="card-header bg-secondary text-white text-center">
                                            <h4><i class="bi bi-award-fill me-2"></i>2nd Place</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">{{ $leaderboard[1]['participant_name'] ?? 'Unknown Participant' }}</h5>
                                            @if(isset($leaderboard[1]['team_name']) && $leaderboard[1]['team_name'])
                                                <p class="text-muted">Team: {{ $leaderboard[1]['team_name'] }}</p>
                                            @endif
                                            <p class="card-text"><strong>{{ $leaderboard[1]['submission_title'] ?? $leaderboard[1]['team_name'] ?? 'No Title' }}</strong></p>
                                            <div class="score-badge">
                                                <span class="badge bg-secondary fs-5">{{ $leaderboard[1]['average_score'] ?? 0 }}/100</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 1st Place -->
                                <div class="col-md-4 order-md-2 mb-4">
                                    <div class="card border-warning shadow-lg">
                                        <div class="card-header bg-warning text-dark text-center">
                                            <h4><i class="bi bi-trophy-fill me-2"></i>1st Place</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">{{ $leaderboard[0]['participant_name'] ?? 'Unknown Participant' }}</h5>
                                            @if(isset($leaderboard[0]['team_name']) && $leaderboard[0]['team_name'])
                                                <p class="text-muted">Team: {{ $leaderboard[0]['team_name'] }}</p>
                                            @endif
                                            <p class="card-text"><strong>{{ $leaderboard[0]['submission_title'] ?? $leaderboard[0]['team_name'] ?? 'No Title' }}</strong></p>
                                            <div class="score-badge">
                                                <span class="badge bg-warning text-dark fs-4">{{ $leaderboard[0]['average_score'] ?? 0 }}/100</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3rd Place -->
                                <div class="col-md-4 order-md-3 mb-4">
                                    <div class="card border-danger">
                                        <div class="card-header bg-danger text-white text-center">
                                            <h4><i class="bi bi-award-fill me-2"></i>3rd Place</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">{{ $leaderboard[2]['participant_name'] ?? 'Unknown Participant' }}</h5>
                                            @if(isset($leaderboard[2]['team_name']) && $leaderboard[2]['team_name'])
                                                <p class="text-muted">Team: {{ $leaderboard[2]['team_name'] }}</p>
                                            @endif
                                            <p class="card-text"><strong>{{ $leaderboard[2]['submission_title'] ?? $leaderboard[2]['team_name'] ?? 'No Title' }}</strong></p>
                                            <div class="score-badge">
                                                <span class="badge bg-danger fs-5">{{ $leaderboard[2]['average_score'] ?? 0 }}/100</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Full Leaderboard Table Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-list-ol text-primary"></i>
                        Complete Rankings
                    </h2>
                </div>
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="mb-0">
                                <i class="bi bi-table me-2"></i>All Participants Ranking Table
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" width="80">Rank</th>
                                            <th>Participant</th>
                                            <th>Work</th>
                                            <th class="text-center" width="120">Score</th>
                                            <th class="text-center" width="100">Judges</th>
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
                                                        <strong>{{ $item['participant_name'] ?? 'Unknown Participant' }}</strong>
                                                        @if(isset($item['team_name']) && $item['team_name'])
                                                            <br><small class="text-muted">Team: {{ $item['team_name'] }}</small>
                                                        @endif
                                                        @if(isset($item['institution']) && $item['institution'])
                                                            <br><small class="text-muted">{{ $item['institution'] }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <strong>{{ $item['submission_title'] ?? $item['team_name'] ?? 'No Title' }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary fs-6">{{ $item['average_score'] ?? 0 }}/100</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-muted">{{ $item['total_juries'] ?? 0 }} judges</span>
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
            <!-- No Data Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="text-center fw-bold mb-4" style="color: #667eea;">
                        <i class="bi bi-exclamation-triangle"></i>
                        No Data Available
                    </h2>
                </div>
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-warning text-dark text-center">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Information
                            </h5>
                        </div>
                        <div class="card-body text-center py-5">
                            <i class="bi bi-trophy fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">No Evaluations Yet</h5>
                            <p class="text-muted">Leaderboard for {{ $selectedCompetition->name }} competition is not available yet.</p>
                            <a href="{{ route('public.competitions') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Competitions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- No Competitions Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center mb-4">
                    <i class="bi bi-exclamation-circle text-danger"></i>
                    No Competitions Available
                </h2>
            </div>
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white text-center">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Information
                        </h5>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="bi bi-trophy fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No Competitions Available</h5>
                        <p class="text-muted">Leaderboard will be available after there are active competitions.</p>
                        <a href="{{ route('public.competitions') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>View Competitions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Consistent with home page styling */
.stat-item {
    padding: 1rem;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
    margin-bottom: 1rem;
}

.stat-item h3 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stat-item p {
    margin-bottom: 0;
    font-size: 0.9rem;
}

/* Card enhancements */
.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    font-weight: 600;
    border-bottom: none;
}

/* Table styling consistent with other pages */
.table {
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Badge styling */
.badge {
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
}

/* Score badge special styling */
.score-badge {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

/* Jumbotron consistent styling */
.jumbotron {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 123, 255, 0.3);
}

/* Button styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
}

/* Responsive design */
@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }

    .lead {
        font-size: 1rem;
    }

    .stat-item h3 {
        font-size: 1.5rem;
    }

    .table-responsive {
        font-size: 0.9rem;
    }

    .card-body {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .jumbotron {
        padding: 3rem 1.5rem !important;
    }

    .btn-lg {
        font-size: 1rem;
        padding: 0.75rem 1rem;
    }

    .stat-item {
        padding: 0.75rem;
    }
}

/* Simple animation for cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Loading state */
.loading {
    opacity: 0.6;
    pointer-events: none;
    position: relative;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #007bff;
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
