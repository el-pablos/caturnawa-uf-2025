@extends('layouts.simple')

@section('title', 'Leaderboard - UNAS Fest 2025')
@section('content')
<style>
    .modern-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .modern-hero::before {
        content: '';
        position: absolute;
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

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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

    .dynamic-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background: linear-gradient(45deg, #f8f9fa 0%, #e9ecef 100%);
        overflow: hidden;
    }

    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
    }

    .modern-container {
        position: relative;
        z-index: 1;
    }
</style>

<div class="dynamic-bg"></div>
<div class="container my-5">
    <div class="floating-shapes"></div>
    <!-- Modern Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5 mb-5"
                 data-aos="zoom-in"
                 data-aos-duration="1200"
                 data-aos-easing="ease-out-back">
                <div class="hero-content text-center">
                    <div class="floating-trophy mb-4"
                         data-aos="bounce"
                         data-aos-delay="300"
                         data-aos-duration="800">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h1 class="modern-title mb-4"
                        data-aos="fade-down"
                        data-aos-delay="500"
                        data-aos-duration="800"
                        data-aos-easing="ease-out-cubic">
                        Leaderboard<span style="background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"> UNAS Fest 2025</span>
                    </h1>
                    <p class="modern-subtitle mb-5"
                       data-aos="fade-up"
                       data-aos-delay="700"
                       data-aos-duration="800">
                        Lihat peringkat terbaru peserta kompetisi UNAS Fest 2025.
                        Pantau posisi Anda dan kompetitor lainnya dalam real-time.
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.competitions') }}" class="btn modern-btn btn-lg w-100">
                                <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.faq') }}" class="btn modern-btn-outline btn-lg w-100">
                                <i class="bi bi-question-circle me-2"></i>Tentang
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
                <h2 class="text-center mb-4">
                    <i class="bi bi-funnel text-info"></i>
                    Filter Kompetisi
                </h2>
            </div>
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-info text-white text-center">
                        <h5 class="mb-0">
                            <i class="bi bi-trophy me-2"></i>Pilih Kompetisi untuk Melihat Leaderboard
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Pilih Kompetisi:</label>
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
                    <h2 class="text-center mb-4">
                        <i class="bi bi-award text-success"></i>
                        {{ $selectedCompetition->name }}
                    </h2>
                </div>
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white text-center">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Informasi Kompetisi
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <h3 class="text-primary">{{ $selectedCompetition->category }}</h3>
                                        <p class="text-muted">Kategori</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <h3 class="text-success">{{ $leaderboard->count() }}</h3>
                                        <p class="text-muted">Total Peserta</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <h3 class="text-warning">{{ number_format($selectedCompetition->price ?? 0) }}</h3>
                                        <p class="text-muted">Hadiah (IDR)</p>
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
                            Top 3 Pemenang
                        </h2>
                    </div>
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header bg-warning text-dark text-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-star me-2"></i>Podium Juara
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                <!-- 2nd Place -->
                                <div class="col-md-4 order-md-1 mb-4">
                                    <div class="card border-secondary">
                                        <div class="card-header bg-secondary text-white text-center">
                                            <h4><i class="bi bi-award-fill me-2"></i>Juara 2</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">{{ $leaderboard[1]['participant_name'] ?? 'Unknown Participant' }}</h5>
                                            @if(isset($leaderboard[1]['team_name']) && $leaderboard[1]['team_name'])
                                                <p class="text-muted">Tim: {{ $leaderboard[1]['team_name'] }}</p>
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
                                            <h4><i class="bi bi-trophy-fill me-2"></i>Juara 1</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">{{ $leaderboard[0]['participant_name'] ?? 'Unknown Participant' }}</h5>
                                            @if(isset($leaderboard[0]['team_name']) && $leaderboard[0]['team_name'])
                                                <p class="text-muted">Tim: {{ $leaderboard[0]['team_name'] }}</p>
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
                                            <h4><i class="bi bi-award-fill me-2"></i>Juara 3</h4>
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">{{ $leaderboard[2]['participant_name'] ?? 'Unknown Participant' }}</h5>
                                            @if(isset($leaderboard[2]['team_name']) && $leaderboard[2]['team_name'])
                                                <p class="text-muted">Tim: {{ $leaderboard[2]['team_name'] }}</p>
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
                        Peringkat Lengkap
                    </h2>
                </div>
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="mb-0">
                                <i class="bi bi-table me-2"></i>Tabel Peringkat Semua Peserta
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
                                                        <strong>{{ $item['participant_name'] ?? 'Unknown Participant' }}</strong>
                                                        @if(isset($item['team_name']) && $item['team_name'])
                                                            <br><small class="text-muted">Tim: {{ $item['team_name'] }}</small>
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
                                                    <span class="text-muted">{{ $item['total_juries'] ?? 0 }} juri</span>
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
                    <h2 class="text-center mb-4">
                        <i class="bi bi-exclamation-triangle text-warning"></i>
                        Belum Ada Data
                    </h2>
                </div>
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-warning text-dark text-center">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Informasi
                            </h5>
                        </div>
                        <div class="card-body text-center py-5">
                            <i class="bi bi-trophy fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Penilaian</h5>
                            <p class="text-muted">Leaderboard untuk kompetisi {{ $selectedCompetition->name }} belum tersedia.</p>
                            <a href="{{ route('public.competitions') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Kompetisi
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
                    Belum Ada Kompetisi
                </h2>
            </div>
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white text-center">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi
                        </h5>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="bi bi-trophy fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Kompetisi</h5>
                        <p class="text-muted">Leaderboard akan tersedia setelah ada kompetisi yang aktif.</p>
                        <a href="{{ route('public.competitions') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Lihat Kompetisi
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
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate Hero Section
        AOS.init({
            duration: 1200,
            easing: 'ease-out-back',
            once: true
        });

        // Animate content within Hero
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            selector: '.hero-content h1, .hero-content p, .hero-content .btn'
        });

        // Animate Leaderboard Section if available
        const leaderboardSection = document.getElementById('leaderboard-content');
        if (leaderboardSection) {
            AOS.init({
                duration: 1000,
                easing: 'ease-in-out',
                once: true,
                offset: 50,
                selector: '#leaderboard-content .card'
            });
        }

        // Add specific animations to table rows
        const tableRows = document.querySelectorAll('.table tbody tr');
        tableRows.forEach((row, index) => {
            row.setAttribute('data-aos', 'fade-up');
            row.setAttribute('data-aos-delay', `${index * 50}`);
            row.setAttribute('data-aos-duration', '600');
            row.setAttribute('data-aos-easing', 'ease-in-out');
        });

        // Add animations to badges
        const badges = document.querySelectorAll('.badge');
        badges.forEach(badge => {
            badge.setAttribute('data-aos', 'zoom-in');
            badge.setAttribute('data-aos-duration', '500');
            badge.setAttribute('data-aos-easing', 'ease-in-out');
        });

        // Animate buttons in the table
        const tableButtons = document.querySelectorAll('.table .btn');
        tableButtons.forEach(button => {
            button.setAttribute('data-aos', 'fade-left');
            button.setAttribute('data-aos-duration', '600');
            button.setAttribute('data-aos-easing', 'ease-in-out');
        });

        // Animate badges on leaderboard
        const leaderboardBadges = document.querySelectorAll('#leaderboard-content .badge');
        leaderboardBadges.forEach(badge => {
            badge.setAttribute('data-aos', 'zoom-in');
            badge.setAttribute('data-aos-duration', '600');
            badge.setAttribute('data-aos-easing', 'ease-in-out');
        });

        // Animate cards in leaderboard
        const leaderboardCards = document.querySelectorAll('#leaderboard-content .card');
        leaderboardCards.forEach((card, index) => {
            card.setAttribute('data-aos', 'flip-left');
            card.setAttribute('data-aos-delay', `${index * 200}`);
            card.setAttribute('data-aos-duration', '800');
            card.setAttribute('data-aos-easing', 'ease-out-back');
        });

        // Animate individual elements inside cards
        const cardElements = document.querySelectorAll('#leaderboard-content .card-header, #leaderboard-content .card-body, #leaderboard-content .card-footer');
        cardElements.forEach(element => {
            element.setAttribute('data-aos', 'fade-up');
            element.setAttribute('data-aos-duration', '600');
            element.setAttribute('data-aos-easing', 'ease-in-out');
        });

        // Animate dropdown or select elements
        const selectElements = document.querySelectorAll('select.form-select');
        selectElements.forEach(select => {
            select.setAttribute('data-aos', 'fade-in');
            select.setAttribute('data-aos-duration', '800');
            select.setAttribute('data-aos-easing', 'ease-in-out');
        });

        // Animate table headers
        const tableHeaders = document.querySelectorAll('.table thead tr th');
        tableHeaders.forEach((header, index) => {
            header.setAttribute('data-aos', 'fade-down');
            header.setAttribute('data-aos-delay', `${index * 100}`);
            header.setAttribute('data-aos-duration', '600');
            header.setAttribute('data-aos-easing', 'ease-in-out');
        });
    });
</script>
@endpush
