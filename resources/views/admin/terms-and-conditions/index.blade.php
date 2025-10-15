@extends('layouts.admin')

@section('title', 'Terms & Conditions Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Terms & Conditions Management</h1>
        <a href="{{ route('admin.terms-and-conditions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Terms & Conditions
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
            <h6 class="m-0 font-weight-bold text-primary">All Terms & Conditions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">Order</th>
                            <th width="30%">Title</th>
                            <th width="15%">Type</th>
                            <th width="35%">Content Preview</th>
                            <th width="10%">Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($terms as $term)
                        <tr>
                            <td>{{ $term->order }}</td>
                            <td>{{ $term->title }}</td>
                            <td>
                                @php
                                    $badgeColors = [
                                        'general' => 'primary',
                                        'competition' => 'success',
                                        'privacy' => 'warning',
                                        'payment' => 'info'
                                    ];
                                    $badgeColor = $badgeColors[$term->type] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeColor }}">
                                    {{ ucfirst($term->type) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($term->content, 100) }}</td>
                            <td>
                                @if($term->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.terms-and-conditions.edit', $term) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.terms-and-conditions.destroy', $term) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this term?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No terms and conditions found. <a href="{{ route('admin.terms-and-conditions.create') }}">Create one now</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $terms->links() }}
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-info text-white">
            <h6 class="m-0 font-weight-bold"><i class="bi bi-info-circle me-2"></i>Information</h6>
        </div>
        <div class="card-body">
            <h6 class="fw-bold">Types of Terms & Conditions:</h6>
            <ul class="mb-0">
                <li><strong>General:</strong> General terms and conditions for using the platform</li>
                <li><strong>Competition:</strong> Specific rules and regulations for competitions</li>
                <li><strong>Privacy:</strong> Privacy policy and data protection information</li>
                <li><strong>Payment:</strong> Payment terms, refund policy, and financial regulations</li>
            </ul>
        </div>
    </div>
</div>
@endsection

