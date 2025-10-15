@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Submission Details</h1>
    
    <div class="card">
        <div class="card-body">
            <h5>Submission ID: {{ $submission->id }}</h5>
            <p><strong>Competition:</strong> {{ $submission->registration->competition->name ?? 'N/A' }}</p>
            <p><strong>Participant:</strong> {{ $submission->registration->user->name ?? 'N/A' }}</p>
            <p><strong>Submitted At:</strong> {{ $submission->created_at->format('Y-m-d H:i') }}</p>
            <p><strong>Status:</strong> {{ $submission->is_final ? 'Final' : 'Draft' }}</p>
            
            @if($submission->files)
            <div class="mt-3">
                <h6>Files:</h6>
                <ul>
                    @foreach($submission->files as $file)
                    <li>{{ $file['name'] ?? 'File' }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <div class="mt-3">
                <a href="{{ route('juri.scoring.score-form', $submission) }}" class="btn btn-primary">Score This Submission</a>
            </div>
        </div>
    </div>
</div>
@endsection

