@extends('emails.layout')

@section('title', 'Score Published')
@section('header-subtitle', 'Your Score is Ready')

@section('content')
<h2>Hello, {{ $user->name }}!</h2>

<p>Your score for <strong>{{ $competition->name }}</strong> has been published!</p>

<div class="alert alert-success">
    <strong>✓ Score Published!</strong><br>
    The judges have completed their evaluation of your submission.
</div>

<div class="info-box">
    <h3 style="margin-top: 0; color: #667eea;">Score Details</h3>
    <table>
        <tr>
            <td>Submission Title</td>
            <td><strong>{{ $submission->title }}</strong></td>
        </tr>
        <tr>
            <td>Competition</td>
            <td>{{ $competition->name }}</td>
        </tr>
        @if($registration->team_name)
        <tr>
            <td>Team Name</td>
            <td>{{ $registration->team_name }}</td>
        </tr>
        @endif
        <tr>
            <td>Total Score</td>
            <td><strong style="font-size: 18px; color: #667eea;">{{ number_format($score->total_score, 2) }}</strong></td>
        </tr>
        <tr>
            <td>Scored By</td>
            <td>{{ $score->jury->name }}</td>
        </tr>
        <tr>
            <td>Scored At</td>
            <td>{{ $score->created_at->format('d M Y, H:i') }}</td>
        </tr>
    </table>
</div>

@if($score->comments)
<div class="info-box">
    <h3 style="margin-top: 0; color: #667eea;">Judge's Feedback</h3>
    <p style="white-space: pre-wrap;">{{ $score->comments }}</p>
</div>
@endif

@if($score->criteria_scores && is_array($score->criteria_scores))
<div class="info-box">
    <h3 style="margin-top: 0; color: #667eea;">Detailed Scores</h3>
    <table>
        @foreach($score->criteria_scores as $criterion => $criterionScore)
        <tr>
            <td>{{ ucwords(str_replace('_', ' ', $criterion)) }}</td>
            <td><strong>{{ number_format($criterionScore, 2) }}</strong></td>
        </tr>
        @endforeach
    </table>
</div>
@endif

<div style="text-align: center;">
    <a href="{{ route('peserta.submissions.show', $submission->id) }}" class="button">
        View Full Details
    </a>
</div>

<div class="divider"></div>

<p><strong>What's Next?</strong></p>
<ul>
    <li>Check the leaderboard to see your ranking</li>
    <li>Wait for the final results announcement</li>
    <li>Winners will be contacted separately</li>
    <li>Certificates will be available for download</li>
</ul>

<p>Thank you for your participation!</p>

<p>Best regards,<br>
<strong>Caturnawa UNAS FEST 2025 Team</strong></p>
@endsection

