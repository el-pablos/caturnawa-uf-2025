@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Scores</h1>
    
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Competition</th>
                        <th>Participant</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scores as $score)
                    <tr>
                        <td>{{ $score->registration->competition->name ?? 'N/A' }}</td>
                        <td>{{ $score->registration->user->name ?? 'N/A' }}</td>
                        <td>{{ $score->total_score }}</td>
                        <td>{{ $score->is_final ? 'Final' : 'Draft' }}</td>
                        <td>{{ $score->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No scores yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{ $scores->links() }}
        </div>
    </div>
</div>
@endsection

