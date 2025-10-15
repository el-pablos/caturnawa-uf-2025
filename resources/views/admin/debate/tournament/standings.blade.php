@extends('layouts.admin')

@section('title', 'Tournament Standings')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Tournament Standings</h1>
            <p class="text-muted mb-0">{{ $competition->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.debate.tournament.standings.export', ['competition_type' => request('competition_type', 'KDBI')]) }}" 
               class="btn btn-success me-2">
                <i class="bi bi-download me-2"></i>Export CSV
            </a>
            <form action="{{ route('admin.debate.tournament.standings.recalculate') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="competition_type" value="{{ request('competition_type', 'KDBI') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Recalculate
                </button>
            </form>
        </div>
    </div>

    <!-- Stage Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Competition Type</label>
                    <select name="competition_type" class="form-select" onchange="this.form.submit()">
                        <option value="KDBI" {{ request('competition_type') == 'KDBI' ? 'selected' : '' }}>KDBI</option>
                        <option value="EDC" {{ request('competition_type') == 'EDC' ? 'selected' : '' }}>EDC</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stage</label>
                    <select name="stage" class="form-select" onchange="this.form.submit()">
                        <option value="">All Stages</option>
                        <option value="PRELIMINARY" {{ request('stage') == 'PRELIMINARY' ? 'selected' : '' }}>Preliminary</option>
                        <option value="SEMIFINAL" {{ request('stage') == 'SEMIFINAL' ? 'selected' : '' }}>Semifinal</option>
                        <option value="FINAL" {{ request('stage') == 'FINAL' ? 'selected' : '' }}>Final</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Team Standings -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Team Standings</h5>
        </div>
        <div class="card-body">
            @if($standings->isEmpty())
                <p class="text-muted text-center py-3">No standings data available.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover" id="standingsTable">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Team Name</th>
                                <th>Institution</th>
                                <th>Matches</th>
                                <th>Team Points</th>
                                <th>Speaker Points</th>
                                <th>Avg Speaker</th>
                                <th>Avg Position</th>
                                <th>1st</th>
                                <th>2nd</th>
                                <th>3rd</th>
                                <th>4th</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($standings as $index => $standing)
                            <tr class="{{ $index < 8 ? 'table-success' : '' }}">
                                <td>
                                    <strong class="fs-5">{{ $index + 1 }}</strong>
                                    @if($index < 8)
                                        <span class="badge bg-success ms-1">Break</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $standing->registration->team_name ?? 'Unknown' }}</strong>
                                </td>
                                <td>{{ $standing->registration->participant->institution ?? 'Unknown' }}</td>
                                <td>{{ $standing->matches_played }}</td>
                                <td><strong class="text-primary">{{ $standing->team_points }}</strong></td>
                                <td><strong>{{ number_format($standing->speaker_points, 2) }}</strong></td>
                                <td>{{ number_format($standing->average_speaker_points, 2) }}</td>
                                <td>{{ number_format($standing->avg_position, 2) }}</td>
                                <td>{{ $standing->first_places }}</td>
                                <td>{{ $standing->second_places }}</td>
                                <td>{{ $standing->third_places }}</td>
                                <td>{{ $standing->fourth_places }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Speaker Standings -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Speaker Standings</h5>
        </div>
        <div class="card-body">
            @if($speakerStandings->isEmpty())
                <p class="text-muted text-center py-3">No speaker data available.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover" id="speakerStandingsTable">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Speaker Name</th>
                                <th>Team</th>
                                <th>Total Score</th>
                                <th>Average Score</th>
                                <th>Speeches</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($speakerStandings as $index => $speaker)
                            <tr class="{{ $index < 10 ? 'table-info' : '' }}">
                                <td>
                                    <strong class="fs-5">{{ $index + 1 }}</strong>
                                    @if($index < 10)
                                        <span class="badge bg-info ms-1">Top 10</span>
                                    @endif
                                </td>
                                <td><strong>{{ $speaker['speaker_name'] }}</strong></td>
                                <td>{{ $speaker['team_name'] }}</td>
                                <td><strong class="text-primary">{{ $speaker['total_score'] }}</strong></td>
                                <td>{{ $speaker['average_score'] }}</td>
                                <td>{{ $speaker['speeches_count'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#standingsTable').DataTable({
        pageLength: 25,
        order: [[0, 'asc']],
        searching: true,
        paging: true,
        info: true
    });

    $('#speakerStandingsTable').DataTable({
        pageLength: 25,
        order: [[0, 'asc']],
        searching: true,
        paging: true,
        info: true
    });
});
</script>
@endpush

