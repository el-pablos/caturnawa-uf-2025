@extends('layouts.admin')

@section('title', 'Contact Information Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Contact Information Management</h1>
        @if($contact)
        <a href="{{ route('admin.contact-information.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit Contact Information
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($contact)
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td width="30%" class="fw-bold"><i class="bi bi-envelope-fill text-primary me-2"></i>Email</td>
                                <td>{{ $contact->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="bi bi-whatsapp text-success me-2"></i>WhatsApp</td>
                                <td>{{ $contact->whatsapp }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Address</td>
                                <td>{{ $contact->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Social Media</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td width="30%" class="fw-bold"><i class="bi bi-instagram text-danger me-2"></i>Instagram</td>
                                <td>
                                    @if($contact->instagram)
                                        <a href="https://instagram.com/{{ ltrim($contact->instagram, '@') }}" target="_blank">
                                            {{ $contact->instagram }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="bi bi-tiktok text-dark me-2"></i>TikTok</td>
                                <td>
                                    @if($contact->tiktok)
                                        <a href="https://tiktok.com/@{{ ltrim($contact->tiktok, '@') }}" target="_blank">
                                            {{ $contact->tiktok }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold"><i class="bi bi-youtube text-danger me-2"></i>YouTube</td>
                                <td>
                                    @if($contact->youtube)
                                        <a href="{{ $contact->youtube }}" target="_blank">
                                            {{ $contact->youtube }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Status</h6>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center">
                <span class="me-3">Visibility Status:</span>
                @if($contact->is_active)
                    <span class="badge bg-success fs-6">Active - Visible on public pages</span>
                @else
                    <span class="badge bg-secondary fs-6">Inactive - Hidden from public pages</span>
                @endif
            </div>
            <div class="mt-3">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Last updated: {{ $contact->updated_at->format('F d, Y H:i:s') }}
                </small>
            </div>
        </div>
    </div>
    @else
    <div class="card shadow mb-4">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">No contact information found</h5>
            <p class="text-muted">Please run the database seeder to create initial contact information.</p>
            <code class="d-block mt-3">php artisan db:seed --class=ContactInformationSeeder</code>
        </div>
    </div>
    @endif
</div>
@endsection

