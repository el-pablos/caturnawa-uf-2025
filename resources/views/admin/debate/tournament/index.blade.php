@extends('layouts.admin')

@section('title', 'Debate Tournament Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Debate Tournament Management</h1>
            <p class="text-muted mb-0">Manage debate rounds, matches, and standings for {{ $competition->name }}</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateTournamentModal">
                <i class="bi bi-plus-circle me-2"></i>Generate Tournament
            </button>
        </div>
    </div>

    <!-- Competition Selector -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.debate.tournament.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Competition Type</label>
                    <select name="competition_type" class="form-select" onchange="this.form.submit()">
                        <option value="KDBI" {{ request('competition_type') == 'KDBI' ? 'selected' : '' }}>KDBI</option>
                        <option value="EDC" {{ request('competition_type') == 'EDC' ? 'selected' : '' }}>EDC</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Tournament Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Teams</h6>
                            <h3 class="mb-0">{{ $status['total_teams'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-trophy-fill text-success" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Rounds</h6>
                            <h3 class="mb-0">{{ $status['total_rounds'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-grid-3x3-gap-fill text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Matches</h6>
                            <h3 class="mb-0">{{ $status['total_matches'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle-fill text-info" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Completed</h6>
                            <h3 class="mb-0">{{ $status['completed_matches'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rounds List -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Tournament Rounds</h5>
        </div>
        <div class="card-body">
            @if($status['rounds']->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No tournament rounds generated yet.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateTournamentModal">
                        Generate Tournament
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Round Name</th>
                                <th>Stage</th>
                                <th>Matches</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($status['rounds'] as $round)
                            <tr>
                                <td>
                                    <strong>{{ $round->round_name }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $round->stage == 'PRELIMINARY' ? 'primary' : ($round->stage == 'SEMIFINAL' ? 'warning' : 'success') }}">
                                        {{ $round->stage }}
                                    </span>
                                </td>
                                <td>{{ $round->matches->count() }} matches</td>
                                <td>
                                    @if($round->is_frozen)
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-lock-fill me-1"></i>Frozen
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-unlock-fill me-1"></i>Active
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.debate.tournament.round', $round->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                    @if($round->is_frozen)
                                        <form action="{{ route('admin.debate.tournament.round.unfreeze', $round->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-unlock me-1"></i>Unfreeze
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.debate.tournament.round.freeze', $round->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-lock me-1"></i>Freeze
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Standings Preview -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Current Standings (Top 10)</h5>
            <a href="{{ route('admin.debate.tournament.standings', ['competition_type' => request('competition_type', 'KDBI')]) }}" class="btn btn-sm btn-outline-primary">
                View Full Standings
            </a>
        </div>
        <div class="card-body">
            @if($standings->isEmpty())
                <p class="text-muted text-center py-3">No standings data available yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Team</th>
                                <th>Matches</th>
                                <th>Team Points</th>
                                <th>Speaker Points</th>
                                <th>Avg Position</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($standings->take(10) as $index => $standing)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>{{ $standing->registration->team_name ?? 'Unknown' }}</td>
                                <td>{{ $standing->matches_played }}</td>
                                <td><strong>{{ $standing->team_points }}</strong></td>
                                <td>{{ number_format($standing->speaker_points, 2) }}</td>
                                <td>{{ number_format($standing->avg_position, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Generate Tournament Modal -->
<div class="modal fade" id="generateTournamentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Tournament</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.debate.tournament.generate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This will delete all existing rounds, matches, and scores for this competition.
                    </div>
                    <input type="hidden" name="competition_type" value="{{ request('competition_type', 'KDBI') }}">
                    <p>Generate tournament structure for <strong>{{ $competition->name }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Tournament</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

