@extends('layouts.simple')

@section('title', 'UNAS Fest 2025 - Festival Kompetisi Nasional')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded">
                <h1 class="display-4">UNAS Fest 2025</h1>
                <p class="lead">Festival Kompetisi Nasional Terbesar Indonesia</p>
                <hr class="my-4">
                <p>Bergabunglah dengan kompetisi Teknologi, Kesehatan, dan Biodiversitas menanti!</p>
                <a class="btn btn-light btn-lg" href="{{ route('public.competitions') }}" role="button">
                    <i class="bi bi-trophy"></i> Lihat Kompetisi
                </a>
            </div>
        </div>
    </div>

    <!-- Leaderboard Section by Competition -->
    @if(isset($competitionLeaderboards) && count($competitionLeaderboards) > 0)
    <div class="row mt-5">
        <div class="col-12 mb-4">
            <div class="text-center">
                <h2 class="fw-bold text-primary">
                    <i class="bi bi-trophy me-2"></i>Leaderboard UNAS Fest 2025
                </h2>
                <p class="text-muted">Peringkat Tim Terbaik Per Kompetisi</p>
            </div>
        </div>

        @foreach($competitions as $competition)
            @if(isset($competitionLeaderboards[$competition->id]) && count($competitionLeaderboards[$competition->id]) > 0)
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-trophy me-2"></i>{{ $competition->name }}
                        </h5>
                        <small>Top 4 Peringkat</small>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="100">Rank</th>
                                        <th>Tim</th>
                                        <th>Institusi</th>
                                        <th class="text-center" width="100">Victory Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($competitionLeaderboards[$competition->id] as $index => $team)
                                    <tr class="{{ $index < 3 ? 'table-warning' : '' }}">
                                        <td class="text-center">
                                            @if($index == 0)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="bi bi-trophy-fill"></i> 1st
                                                </span>
                                            @elseif($index == 1)
                                                <span class="badge bg-secondary fs-6">
                                                    <i class="bi bi-award-fill"></i> 2nd
                                                </span>
                                            @elseif($index == 2)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="bi bi-award"></i> 3rd
                                                </span>
                                            @elseif($index == 3)
                                                <span class="badge bg-info text-white fs-6">
                                                    <i class="bi bi-star"></i> Jury Mention
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $team['team_name'] }}</strong>
                                                @if($team['participants'] && count($team['participants']) > 0)
                                                    <br><small class="text-muted">{{ $team['participants'][0]['name'] ?? '' }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ $team['institution'] ?? 'Tidak ada' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">
                                                <i class="bi bi-star-fill"></i> {{ $team['total_victory_points'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('leaderboard.index', ['competition' => $competition->id]) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Lihat Detail
                    </a>
                    <a href="{{ route('matalomba.show', $competition->slug) }}" class="btn btn-outline-success btn-sm ms-2">
                        <i class="bi bi-trophy me-1"></i>Lihat Babak
                    </a>
                </div>
            </div>
        </div>
        @endif
        @endforeach

        <div class="col-12 text-center mt-3">
            <a href="{{ route('leaderboard.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-trophy me-2"></i>Lihat Semua Leaderboard
            </a>
        </div>
    </div>
    @else
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="bi bi-trophy text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">Leaderboard Belum Tersedia</h4>
                    <p class="text-muted">Leaderboard akan ditampilkan setelah ada submission yang dinilai.</p>
                    <a href="{{ route('public.competitions') }}" class="btn btn-primary">
                        <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Featured Competitions -->
    @if($competitions && $competitions->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Kompetisi UnasFest</h2>
        </div>
        @foreach($competitions as $competition)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $competition->name }}</h5>
                    <p class="card-text">{{ Str::limit($competition->description, 100) }}</p>
                    <p class="text-muted">
                        <i class="bi bi-calendar"></i> 
                        {{ $competition->registration_end->format('d M Y') }}
                    </p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('public.competition.detail', $competition->slug) }}" class="btn btn-primary">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
