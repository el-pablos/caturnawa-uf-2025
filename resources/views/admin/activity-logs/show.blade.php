@extends('layouts.admin')

@section('title', 'Activity Log Details')

@section('page-title', 'Activity Log Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-{{ $activityLog->color }} text-white">
                <h5 class="mb-0">
                    <i class="{{ $activityLog->icon }} me-2"></i>Activity Log #{{ $activityLog->id }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">Category:</div>
                    <div class="col-md-9">
                        <span class="badge bg-{{ $activityLog->color }}">
                            {{ ucfirst($activityLog->log_name) }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">Description:</div>
                    <div class="col-md-9">{{ $activityLog->description }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">Event:</div>
                    <div class="col-md-9">
                        @if($activityLog->event)
                            <span class="badge bg-secondary">{{ ucfirst($activityLog->event) }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">Date & Time:</div>
                    <div class="col-md-9">
                        {{ $activityLog->created_at->format('d F Y, H:i:s') }}
                        <small class="text-muted">({{ $activityLog->created_at->diffForHumans() }})</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">IP Address:</div>
                    <div class="col-md-9">
                        <code>{{ $activityLog->ip_address ?? '-' }}</code>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 fw-semibold">User Agent:</div>
                    <div class="col-md-9">
                        <small class="text-muted">{{ $activityLog->user_agent ?? '-' }}</small>
                    </div>
                </div>

                @if($activityLog->subject_type)
                    <hr>
                    <h6 class="fw-semibold mb-3">Subject Information</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Subject Type:</div>
                        <div class="col-md-9">
                            <code>{{ class_basename($activityLog->subject_type) }}</code>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-semibold">Subject ID:</div>
                        <div class="col-md-9">
                            <code>#{{ $activityLog->subject_id }}</code>
                        </div>
                    </div>
                @endif

                @if($activityLog->properties && count($activityLog->properties) > 0)
                    <hr>
                    <h6 class="fw-semibold mb-3">Additional Properties</h6>
                    
                    <div class="bg-light p-3 rounded">
                        <pre class="mb-0"><code>{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- User Information -->
        @if($activityLog->user)
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-person me-2"></i>User
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($activityLog->user->avatar)
                            <img src="{{ asset('storage/' . $activityLog->user->avatar) }}" alt="{{ $activityLog->user->name }}" class="rounded-circle" width="80" height="80">
                        @else
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ substr($activityLog->user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-center">
                        <h6 class="mb-1">{{ $activityLog->user->name }}</h6>
                        <p class="text-muted mb-2">{{ $activityLog->user->email }}</p>
                        @if($activityLog->user->roles->isNotEmpty())
                            <span class="badge bg-primary">{{ $activityLog->user->roles->first()->name }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Causer Information -->
        @if($activityLog->causer && $activityLog->causer->id !== $activityLog->user?->id)
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-person-check me-2"></i>Caused By
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($activityLog->causer->avatar)
                            <img src="{{ asset('storage/' . $activityLog->causer->avatar) }}" alt="{{ $activityLog->causer->name }}" class="rounded-circle" width="60" height="60">
                        @else
                            <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                {{ substr($activityLog->causer->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-center">
                        <h6 class="mb-1">{{ $activityLog->causer->name }}</h6>
                        <p class="text-muted mb-2">{{ $activityLog->causer->email }}</p>
                        @if($activityLog->causer->roles->isNotEmpty())
                            <span class="badge bg-secondary">{{ $activityLog->causer->roles->first()->name }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>Related Activities
                </h6>
            </div>
            <div class="card-body">
                @if($activityLog->user)
                    <div class="d-flex justify-content-between mb-2">
                        <span>User's Total Activities:</span>
                        <strong>{{ $activityLog->user->activityLogs()->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Today:</span>
                        <strong>{{ $activityLog->user->activityLogs()->whereDate('created_at', today())->count() }}</strong>
                    </div>
                @endif
                <div class="d-flex justify-content-between">
                    <span>Same Category:</span>
                    <strong>{{ \App\Models\ActivityLog::where('log_name', $activityLog->log_name)->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

