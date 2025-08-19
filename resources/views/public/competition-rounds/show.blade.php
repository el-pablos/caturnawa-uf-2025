@extends('layouts.public')

@section('title', $competition->name . ' - Competition Rounds')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container">
        <!-- Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    @if($competition->image)
                        <img src="{{ asset('storage/competitions/' . $competition->image) }}" 
                             alt="{{ $competition->name }}" class="me-3" style="height: 80px;">
                    @endif
                    <div>
                        <h1 class="text-white mb-0">{{ $competition->name }}</h1>
                        <p class="text-white-50 mb-0">{{ $competition->short_description }}</p>
                    </div>
                </div>
                <a href="{{ route('public.competitions') }}" class="btn btn-outline-light btn-auto w-100">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>

        <!-- Competition Description -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title text-primary mb-3">
                            <i class="bi bi-info-circle me-2"></i>About Competition
                        </h5>
                        <p class="card-text">{{ $competition->description }}</p>
                        
                        @if($competition->theme)
                        <div class="alert alert-info">
                            <strong>Theme:</strong> {{ $competition->theme }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Participants Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-people me-2"></i>Competition Participants
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($registrations->count() > 0)
                            <div class="row">
                                @foreach($registrations as $registration)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            @if($registration->logo_instansi)
                                                <img src="{{ asset('storage/' . $registration->logo_instansi) }}" 
                                                     class="mb-3" alt="Logo {{ $registration->institution }}" 
                                                     style="max-height: 60px; max-width: 100px;">
                                            @else
                                                <div class="bg-light rounded p-3 mb-3">
                                                    <i class="bi bi-building text-muted" style="font-size: 2rem;"></i>
                                                </div>
                                            @endif
                                            
                                            <h6 class="card-title text-primary">{{ $registration->team_name }}</h6>
                                            <p class="card-text text-muted small">{{ $registration->institution }}</p>
                                            
                                            @if($registration->team_members && count($registration->team_members) > 0)
                                                <div class="mt-3">
                                                    <small class="text-muted">Anggota Tim:</small>
                                                    @foreach($registration->team_members as $member)
                                                        <div class="small">{{ $member['name'] }}</div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mt-3">No participants registered yet</h5>
                                <p class="text-muted">Participants will appear after payment confirmation</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Rounds Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-trophy me-2"></i>Select Round
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($competition->rounds->count() > 0)
                            <div class="row">
                                <!-- Final Results Card -->
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm hover-card border-warning">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="bi bi-trophy-fill text-warning" style="font-size: 3rem;"></i>
                                            </div>
                                            
                                            <h5 class="card-title text-warning">Final Results</h5>
                                            <p class="card-text text-muted">View overall rankings and final scores</p>
                                            
                                            <a href="{{ route('matalomba.final', $competition->slug) }}" 
                                               class="btn btn-warning text-white">
                                                <i class="bi bi-trophy me-2"></i>View Final Results
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                @foreach($competition->rounds as $round)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm hover-card">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                @if($round->round_type == 'penyisihan')
                                                    <i class="bi bi-play-circle text-primary" style="font-size: 3rem;"></i>
                                                @elseif($round->round_type == 'semifinal')
                                                    <i class="bi bi-award text-warning" style="font-size: 3rem;"></i>
                                                @else
                                                    <i class="bi bi-trophy text-success" style="font-size: 3rem;"></i>
                                                @endif
                                            </div>
                                            
                                            <h5 class="card-title">{{ $round->name }}</h5>
                                            <p class="card-text text-muted">{{ $round->description }}</p>
                                            
                                            @if($round->start_date)
                                                <div class="mb-3">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        {{ $round->start_date->format('d M Y') }}
                                                        @if($round->end_date && $round->start_date->format('Y-m-d') != $round->end_date->format('Y-m-d'))
                                                            - {{ $round->end_date->format('d M Y') }}
                                                        @endif
                                                    </small>
                                                </div>
                                            @endif
                                            
                                            <div class="mb-3">
                                                <span class="badge bg-{{ $round->status == 'completed' ? 'success' : ($round->status == 'ongoing' ? 'warning' : 'secondary') }}">
                                                    {{ $round->status_name }}
                                                </span>
                                            </div>
                                            
                                            <a href="{{ route('matalomba.round', [$competition->slug, $round->round_type]) }}" 
                                               class="btn btn-primary">
                                                <i class="bi bi-eye me-2"></i>Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mt-3">Competition rounds not yet available</h5>
                                <p class="text-muted">Competition rounds will be announced after the registration period ends</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}
</style>
@endpush
