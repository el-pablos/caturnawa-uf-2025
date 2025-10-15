@extends('layouts.admin')

@section('title', 'Round Details - ' . $round->round_name)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.debate.tournament.index', ['competition_type' => $round->competition->type]) }}">Tournament</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $round->round_name }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-1">{{ $round->round_name }}</h1>
            <p class="text-muted mb-0">{{ $round->competition->name }}</p>
        </div>
        <div>
            @if($round->is_frozen)
                <span class="badge bg-secondary fs-6 me-2">
                    <i class="bi bi-lock-fill me-1"></i>Frozen
                </span>
            @else
                <span class="badge bg-success fs-6 me-2">
                    <i class="bi bi-unlock-fill me-1"></i>Active
                </span>
            @endif
        </div>
    </div>

    <!-- Round Info -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Stage</h6>
                    <h4 class="mb-0">
                        <span class="badge bg-{{ $round->stage == 'PRELIMINARY' ? 'primary' : ($round->stage == 'SEMIFINAL' ? 'warning' : 'success') }}">
                            {{ $round->stage }}
                        </span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Matches</h6>
                    <h4 class="mb-0">{{ $round->matches->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Completed</h6>
                    <h4 class="mb-0">{{ $round->matches->filter(fn($m) => $m->completed_at)->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Pending</h6>
                    <h4 class="mb-0">{{ $round->matches->filter(fn($m) => !$m->completed_at)->count() }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Motion -->
    @if($round->motion)
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="text-muted mb-2">Motion</h6>
            <p class="mb-0 fs-5">{{ $round->motion }}</p>
        </div>
    </div>
    @endif

    <!-- Matches List -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Matches</h5>
        </div>
        <div class="card-body">
            @if($round->matches->isEmpty())
                <p class="text-muted text-center py-3">No matches in this round.</p>
            @else
                <div class="row g-3">
                    @foreach($round->matches as $match)
                    <div class="col-md-6">
                        <div class="card border {{ $match->completed_at ? 'border-success' : 'border-warning' }}">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <strong>Match {{ $match->match_number }}</strong>
                                @if($match->completed_at)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <!-- Teams -->
                                <div class="mb-3">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="p-2 border rounded {{ $match->first_place_team_id == $match->team1_id ? 'bg-success bg-opacity-10 border-success' : '' }}">
                                                <small class="text-muted d-block">OG (Opening Government)</small>
                                                <strong>{{ $match->team1->team_name ?? 'TBD' }}</strong>
                                                @if($match->first_place_team_id == $match->team1_id)
                                                    <span class="badge bg-success ms-1">1st</span>
                                                @elseif($match->second_place_team_id == $match->team1_id)
                                                    <span class="badge bg-info ms-1">2nd</span>
                                                @elseif($match->third_place_team_id == $match->team1_id)
                                                    <span class="badge bg-warning ms-1">3rd</span>
                                                @elseif($match->fourth_place_team_id == $match->team1_id)
                                                    <span class="badge bg-secondary ms-1">4th</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 border rounded {{ $match->first_place_team_id == $match->team2_id ? 'bg-success bg-opacity-10 border-success' : '' }}">
                                                <small class="text-muted d-block">OO (Opening Opposition)</small>
                                                <strong>{{ $match->team2->team_name ?? 'TBD' }}</strong>
                                                @if($match->first_place_team_id == $match->team2_id)
                                                    <span class="badge bg-success ms-1">1st</span>
                                                @elseif($match->second_place_team_id == $match->team2_id)
                                                    <span class="badge bg-info ms-1">2nd</span>
                                                @elseif($match->third_place_team_id == $match->team2_id)
                                                    <span class="badge bg-warning ms-1">3rd</span>
                                                @elseif($match->fourth_place_team_id == $match->team2_id)
                                                    <span class="badge bg-secondary ms-1">4th</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 border rounded {{ $match->first_place_team_id == $match->team3_id ? 'bg-success bg-opacity-10 border-success' : '' }}">
                                                <small class="text-muted d-block">CG (Closing Government)</small>
                                                <strong>{{ $match->team3->team_name ?? 'TBD' }}</strong>
                                                @if($match->first_place_team_id == $match->team3_id)
                                                    <span class="badge bg-success ms-1">1st</span>
                                                @elseif($match->second_place_team_id == $match->team3_id)
                                                    <span class="badge bg-info ms-1">2nd</span>
                                                @elseif($match->third_place_team_id == $match->team3_id)
                                                    <span class="badge bg-warning ms-1">3rd</span>
                                                @elseif($match->fourth_place_team_id == $match->team3_id)
                                                    <span class="badge bg-secondary ms-1">4th</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 border rounded {{ $match->first_place_team_id == $match->team4_id ? 'bg-success bg-opacity-10 border-success' : '' }}">
                                                <small class="text-muted d-block">CO (Closing Opposition)</small>
                                                <strong>{{ $match->team4->team_name ?? 'TBD' }}</strong>
                                                @if($match->first_place_team_id == $match->team4_id)
                                                    <span class="badge bg-success ms-1">1st</span>
                                                @elseif($match->second_place_team_id == $match->team4_id)
                                                    <span class="badge bg-info ms-1">2nd</span>
                                                @elseif($match->third_place_team_id == $match->team4_id)
                                                    <span class="badge bg-warning ms-1">3rd</span>
                                                @elseif($match->fourth_place_team_id == $match->team4_id)
                                                    <span class="badge bg-secondary ms-1">4th</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Judge & Room -->
                                <div class="mb-2">
                                    <small class="text-muted">Judge:</small>
                                    <strong>{{ $match->judge->name ?? 'Not assigned' }}</strong>
                                </div>
                                @if($match->room_name)
                                <div class="mb-2">
                                    <small class="text-muted">Room:</small>
                                    <strong>{{ $match->room_name }}</strong>
                                </div>
                                @endif
                                @if($match->scheduled_at)
                                <div class="mb-2">
                                    <small class="text-muted">Scheduled:</small>
                                    <strong>{{ $match->scheduled_at->format('d M Y, H:i') }}</strong>
                                </div>
                                @endif

                                <!-- Actions -->
                                <div class="mt-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editMatchModal{{ $match->id }}">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </button>
                                    @if($match->completed_at)
                                        <button type="button" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-eye me-1"></i>View Scores
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Match Modal -->
                    <div class="modal fade" id="editMatchModal{{ $match->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Match {{ $match->match_number }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.debate.tournament.match.update', $match->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Room Name</label>
                                            <input type="text" name="room_name" class="form-control" value="{{ $match->room_name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Scheduled Time</label>
                                            <input type="datetime-local" name="scheduled_at" class="form-control" 
                                                   value="{{ $match->scheduled_at ? $match->scheduled_at->format('Y-m-d\TH:i') : '' }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Assign Judge</label>
                                            <select name="judge_id" class="form-select">
                                                <option value="">Select Judge</option>
                                                @foreach(\App\Models\User::where('role', 'judge')->get() as $judge)
                                                    <option value="{{ $judge->id }}" {{ $match->judge_id == $judge->id ? 'selected' : '' }}>
                                                        {{ $judge->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

