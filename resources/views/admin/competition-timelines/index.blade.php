@extends('layouts.admin')

@section('title', 'Competition Timeline Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Competition Timeline Management</h1>
        <a href="{{ route('admin.competition-timelines.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Timeline Event
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Timeline Events</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <select name="competition_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Competitions</option>
                            @foreach($competitions as $comp)
                                <option value="{{ $comp->id }}" {{ request('competition_id') == $comp->id ? 'selected' : '' }}>
                                    {{ $comp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">Order</th>
                            <th width="20%">Competition</th>
                            <th width="15%">Date</th>
                            <th width="40%">Title</th>
                            <th width="10%">Status</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timelines as $timeline)
                        <tr>
                            <td>{{ $timeline->order }}</td>
                            <td>{{ $timeline->competition->name }}</td>
                            <td>{{ $timeline->month }} {{ $timeline->day }}, {{ $timeline->year }}</td>
                            <td>{{ $timeline->title }}</td>
                            <td>
                                @if($timeline->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.competition-timelines.edit', $timeline) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.competition-timelines.destroy', $timeline) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No timeline events found. <a href="{{ route('admin.competition-timelines.create') }}">Create one now</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $timelines->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

