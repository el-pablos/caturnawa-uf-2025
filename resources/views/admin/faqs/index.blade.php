@extends('layouts.admin')

@section('title', 'FAQ Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">FAQ Management</h1>
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New FAQ
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
            <h6 class="m-0 font-weight-bold text-primary">All FAQs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">Order</th>
                            <th width="30%">Question</th>
                            <th width="40%">Answer</th>
                            <th width="10%">Status</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                        <tr>
                            <td>{{ $faq->order }}</td>
                            <td>{{ Str::limit($faq->question, 100) }}</td>
                            <td>{{ Str::limit($faq->answer, 150) }}</td>
                            <td>
                                @if($faq->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
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
                            <td colspan="5" class="text-center text-muted py-4">
                                No FAQs found. <a href="{{ route('admin.faqs.create') }}">Create one now</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $faqs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

