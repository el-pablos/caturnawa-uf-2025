@extends('layouts.admin')

@section('title', 'Global Search')

@section('page-title', 'Global Search')

@section('content')
<!-- Search Form -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.search.index') }}">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="q" class="form-label fw-semibold">Search Query</label>
                    <input type="text" name="q" id="q" class="form-control form-control-lg" 
                           placeholder="Search competitions, registrations, users, submissions..." 
                           value="{{ $query }}" autofocus>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="entity" class="form-label fw-semibold">Search In</label>
                    <select name="entity" id="entity" class="form-select">
                        <option value="all" {{ $entity === 'all' ? 'selected' : '' }}>All Entities</option>
                        <option value="competitions" {{ $entity === 'competitions' ? 'selected' : '' }}>Competitions</option>
                        <option value="registrations" {{ $entity === 'registrations' ? 'selected' : '' }}>Registrations</option>
                        <option value="users" {{ $entity === 'users' ? 'selected' : '' }}>Users</option>
                        <option value="submissions" {{ $entity === 'submissions' ? 'selected' : '' }}>Submissions</option>
                        <option value="payments" {{ $entity === 'payments' ? 'selected' : '' }}>Payments</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-search me-2"></i>Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($query && strlen($query) >= 2)
    <!-- Statistics -->
    @if(!empty($statistics))
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">Search Results for "{{ $query }}"</h6>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h4 class="text-primary mb-0">{{ $statistics['competitions'] ?? 0 }}</h4>
                                    <small class="text-muted">Competitions</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h4 class="text-success mb-0">{{ $statistics['registrations'] ?? 0 }}</h4>
                                    <small class="text-muted">Registrations</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h4 class="text-info mb-0">{{ $statistics['users'] ?? 0 }}</h4>
                                    <small class="text-muted">Users</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h4 class="text-warning mb-0">{{ $statistics['submissions'] ?? 0 }}</h4>
                                    <small class="text-muted">Submissions</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h4 class="text-danger mb-0">{{ $statistics['payments'] ?? 0 }}</h4>
                                    <small class="text-muted">Payments</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <h4 class="text-dark mb-0">{{ array_sum($statistics) }}</h4>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Results -->
    @if(!empty($results))
        <!-- Competitions -->
        @if(isset($results['competitions']) && $results['competitions']->isNotEmpty())
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy me-2"></i>Competitions ({{ $results['competitions']->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($results['competitions'] as $competition)
                            <a href="{{ route('admin.competitions.show', $competition) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $competition->name }}</h6>
                                        <p class="mb-1 text-muted small">{{ Str::limit($competition->description, 100) }}</p>
                                        <small class="text-muted">
                                            <span class="badge bg-secondary">{{ $competition->category }}</span>
                                            <span class="badge bg-{{ $competition->is_active ? 'success' : 'danger' }}">
                                                {{ $competition->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </small>
                                    </div>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Registrations -->
        @if(isset($results['registrations']) && $results['registrations']->isNotEmpty())
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-check me-2"></i>Registrations ({{ $results['registrations']->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($results['registrations'] as $registration)
                            <a href="{{ route('admin.registrations.show', $registration) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $registration->registration_number }}</h6>
                                        <p class="mb-1 text-muted small">
                                            {{ $registration->user->name }} - {{ $registration->competition->name }}
                                        </p>
                                        <small class="text-muted">
                                            <span class="badge bg-{{ $registration->status === 'confirmed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                            @if($registration->team_name)
                                                <span class="badge bg-info">{{ $registration->team_name }}</span>
                                            @endif
                                        </small>
                                    </div>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Users -->
        @if(isset($results['users']) && $results['users']->isNotEmpty())
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>Users ({{ $results['users']->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($results['users'] as $user)
                            <a href="{{ route('admin.users.show', $user) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle me-3" width="40" height="40">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $user->name }}</h6>
                                            <p class="mb-1 text-muted small">{{ $user->email }}</p>
                                            <small class="text-muted">
                                                @if($user->roles->isNotEmpty())
                                                    <span class="badge bg-primary">{{ $user->roles->first()->name }}</span>
                                                @endif
                                                @if($user->institution)
                                                    <span class="badge bg-secondary">{{ $user->institution }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Submissions -->
        @if(isset($results['submissions']) && $results['submissions']->isNotEmpty())
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Submissions ({{ $results['submissions']->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($results['submissions'] as $submission)
                            <a href="{{ route('admin.submissions.show', $submission) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $submission->title }}</h6>
                                        <p class="mb-1 text-muted small">
                                            {{ $submission->registration->user->name }} - {{ $submission->registration->competition->name }}
                                        </p>
                                        <small class="text-muted">
                                            <span class="badge bg-{{ $submission->is_final ? 'success' : 'secondary' }}">
                                                {{ $submission->is_final ? 'Final' : 'Draft' }}
                                            </span>
                                        </small>
                                    </div>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Payments -->
        @if(isset($results['payments']) && $results['payments']->isNotEmpty())
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-coin me-2"></i>Payments ({{ $results['payments']->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($results['payments'] as $payment)
                            <a href="{{ route('admin.payments.show', $payment) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $payment->order_id }}</h6>
                                        <p class="mb-1 text-muted small">
                                            {{ $payment->registration->user->name }} - Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </p>
                                        <small class="text-muted">
                                            <span class="badge bg-{{ $payment->status === 'settlement' ? 'success' : 'warning' }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </small>
                                    </div>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-search" style="font-size: 4rem; color: #ccc;"></i>
                <h5 class="mt-3 text-muted">No results found for "{{ $query }}"</h5>
                <p class="text-muted">Try different keywords or filters</p>
            </div>
        </div>
    @endif
@else
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-search" style="font-size: 4rem; color: #ccc;"></i>
            <h5 class="mt-3 text-muted">Enter a search query</h5>
            <p class="text-muted">Search across competitions, registrations, users, submissions, and payments</p>
        </div>
    </div>
@endif
@endsection

