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

    <!-- Leaderboard Section -->
    @if($leaderboard && $leaderboard->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-trophy me-2"></i>Leaderboard UNAS Fest 2025
                    </h3>
                    <p class="mb-0">Peringkat Tim Terbaik Berdasarkan Victory Points</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="80">Rank</th>
                                    <th>Tim</th>
                                    <th>Kompetisi</th>
                                    <th>Institusi</th>
                                    <th class="text-center" width="120">Skor</th>
                                    <th class="text-center" width="150">Victory Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaderboard as $item)
                                    <tr class="{{ $item['rank'] <= 3 ? 'table-warning' : '' }}">
                                        <td class="text-center">
                                            @if($item['rank'] == 1)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="bi bi-trophy-fill"></i> {{ $item['rank'] }}
                                                </span>
                                            @elseif($item['rank'] == 2)
                                                <span class="badge bg-secondary fs-6">
                                                    <i class="bi bi-award-fill"></i> {{ $item['rank'] }}
                                                </span>
                                            @elseif($item['rank'] == 3)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="bi bi-award"></i> {{ $item['rank'] }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $item['rank'] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $item['team_name'] }}</strong>
                                                @if($item['team_name'] !== $item['participant_name'])
                                                    <br><small class="text-muted">{{ $item['participant_name'] }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $item['competition'] }}</span>
                                        </td>
                                        <td>{{ $item['institution'] ?? 'Tidak ada' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $item['score'] }}/100</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="bi bi-star-fill"></i> {{ $item['victory_points'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('leaderboard.index') }}" class="btn btn-primary">
                        <i class="bi bi-trophy me-2"></i>Lihat Leaderboard Lengkap
                    </a>
                </div>
            </div>
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
