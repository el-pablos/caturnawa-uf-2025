@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('page-title', 'Activity Logs')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Today</h6>
                        <h3 class="mb-0">{{ number_format($statistics['today']) }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-calendar-day" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">This Week</h6>
                        <h3 class="mb-0">{{ number_format($statistics['this_week']) }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-calendar-week" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">This Month</h6>
                        <h3 class="mb-0">{{ number_format($statistics['this_month']) }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-calendar-month" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total</h6>
                        <h3 class="mb-0">{{ number_format($statistics['total']) }}</h3>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-activity" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filters
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="log_name" class="form-label">Category</label>
                    <select name="log_name" id="log_name" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($logNames as $logName)
                            <option value="{{ $logName }}" {{ request('log_name') == $logName ? 'selected' : '' }}>
                                {{ ucfirst($logName) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search description..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </div>
                <div>
                    <button type="submit" formaction="{{ route('admin.activity-logs.export') }}" class="btn btn-success">
                        <i class="bi bi-download me-1"></i>Export CSV
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cleanLogsModal">
                        <i class="bi bi-trash me-1"></i>Clean Old Logs
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Activity Logs Table -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>Activity Logs ({{ $logs->total() }} total)
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">ID</th>
                        <th width="10%">Category</th>
                        <th width="15%">User</th>
                        <th width="40%">Description</th>
                        <th width="15%">Date & Time</th>
                        <th width="10%">IP Address</th>
                        <th width="5%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>
                                <span class="badge bg-{{ $log->color }}">
                                    <i class="{{ $log->icon }} me-1"></i>{{ ucfirst($log->log_name) }}
                                </span>
                            </td>
                            <td>
                                @if($log->user)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            @if($log->user->avatar)
                                                <img src="{{ asset('storage/' . $log->user->avatar) }}" alt="{{ $log->user->name }}" class="rounded-circle" width="32" height="32">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    {{ substr($log->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $log->user->name }}</div>
                                            <small class="text-muted">{{ $log->user->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $log->description }}</div>
                                @if($log->subject_type)
                                    <small class="text-muted">
                                        <i class="bi bi-link-45deg"></i>
                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $log->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                <small class="font-monospace">{{ $log->ip_address ?? '-' }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.activity-logs.show', $log) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">No activity logs found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
        <div class="card-footer">
            {{ $logs->links() }}
        </div>
    @endif
</div>

<!-- Clean Logs Modal -->
<div class="modal fade" id="cleanLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.activity-logs.clean') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Clean Old Activity Logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        This action will permanently delete old activity logs. This cannot be undone.
                    </div>
                    <div class="mb-3">
                        <label for="days" class="form-label">Delete logs older than (days)</label>
                        <input type="number" name="days" id="days" class="form-control" value="90" min="30" max="365" required>
                        <small class="text-muted">Minimum: 30 days, Maximum: 365 days</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Clean Logs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

