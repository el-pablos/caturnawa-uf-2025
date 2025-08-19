@extends('layouts.peserta')

@section('title', 'Registration Locked')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Registration Locked</h1>
            <p class="text-muted">{{ $registration->registration_number }}</p>
        </div>
        <a href="{{ route('peserta.registrations.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Registrations
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-lock-fill me-2"></i>Registration Locked
                    </h5>
                </div>
                <div class="card-body text-center">
                    <i class="bi bi-lock-fill text-warning" style="font-size: 4rem;"></i>
                    
                    <h4 class="mt-3 mb-3">This registration has been locked by an administrator</h4>
                    
                    @if($registration->lock_reason)
                        <div class="alert alert-info">
                            <strong>Reason:</strong> {{ $registration->lock_reason }}
                        </div>
                    @endif
                    
                    <div class="mt-4">
                        <p class="text-muted">
                            Locked on {{ $registration->locked_at ? $registration->locked_at->format('d M Y, H:i') : 'Unknown' }}
                            @if($registration->lockedBy)
                                by {{ $registration->lockedBy->name }}
                            @endif
                        </p>
                    </div>
                    
                    <div class="mt-4">
                        <p>If you believe this is an error, please contact our support team via WhatsApp:</p>
                        <a href="https://wa.me/6285817378442?text=Hello, my registration {{ $registration->registration_number }} has been locked and I need assistance." 
                           target="_blank" 
                           class="btn btn-success">
                            <i class="bi bi-whatsapp"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection