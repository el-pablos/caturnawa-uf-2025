@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Scoring Form</h1>
    
    <div class="card">
        <div class="card-body">
            <h5>Competition: {{ $competition->name }}</h5>
            <h6>Submission ID: {{ $submission->id }}</h6>
            
            <form method="POST" action="{{ route('juri.scoring.score-store', $submission) }}">
                @csrf
                
                <input type="hidden" name="registration_id" value="{{ $submission->registration_id }}">
                
                <div class="mb-3">
                    <label for="total_score" class="form-label">Total Score</label>
                    <input type="number" class="form-control" id="total_score" name="total_score" min="0" max="100" required>
                </div>
                
                <div class="mb-3">
                    <label for="comments" class="form-label">Comments</label>
                    <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit Score</button>
            </form>
        </div>
    </div>
</div>
@endsection

