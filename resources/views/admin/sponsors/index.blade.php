@extends('layouts.admin')

@section('title', 'Sponsor Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sponsor Management</h1>
        <a href="{{ route('admin.sponsors.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Sponsor
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
            <h6 class="m-0 font-weight-bold text-primary">All Sponsors</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">Order</th>
                            <th width="10%">Logo</th>
                            <th width="25%">Name</th>
                            <th width="15%">Type</th>
                            <th width="25%">Website</th>
                            <th width="10%">Status</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sponsors as $sponsor)
                        <tr>
                            <td>{{ $sponsor->order }}</td>
                            <td>
                                @if($sponsor->logo)
                                    <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="img-thumbnail" style="max-width: 60px;">
                                @else
                                    <span class="text-muted">No logo</span>
                                @endif
                            </td>
                            <td>{{ $sponsor->name }}</td>
                            <td>
                                <span class="badge bg-{{ $sponsor->type === 'platinum' ? 'primary' : ($sponsor->type === 'gold' ? 'warning' : ($sponsor->type === 'silver' ? 'secondary' : 'info')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $sponsor->type)) }}
                                </span>
                            </td>
                            <td>
                                @if($sponsor->website)
                                    <a href="{{ $sponsor->website }}" target="_blank">{{ Str::limit($sponsor->website, 40) }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($sponsor->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.sponsors.edit', $sponsor) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.sponsors.destroy', $sponsor) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                            <td colspan="7" class="text-center text-muted py-4">
                                No sponsors found. <a href="{{ route('admin.sponsors.create') }}">Create one now</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $sponsors->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

