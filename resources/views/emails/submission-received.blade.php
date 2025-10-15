@extends('emails.layout')

@section('title', 'Submission Received')
@section('header-subtitle', 'Submission Confirmed')

@section('content')
<h2>Hello, {{ $user->name }}!</h2>

<p>We have successfully received your submission for <strong>{{ $competition->name }}</strong>!</p>

<div class="alert alert-success">
    <strong>✓ Submission Received!</strong><br>
    Your work has been submitted and is now under review by our judges.
</div>

<div class="info-box">
    <h3 style="margin-top: 0; color: #667eea;">Submission Details</h3>
    <table>
        <tr>
            <td>Title</td>
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
            <td>Submitted At</td>
            <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : $submission->created_at->format('d M Y, H:i') }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td><span style="color: #28a745;">{{ ucfirst($submission->status) }}</span></td>
        </tr>
    </table>
</div>

<div class="alert alert-info">
    <strong>What Happens Next?</strong>
    <ol style="margin: 10px 0; padding-left: 20px;">
        <li>Your submission will be reviewed by our panel of judges</li>
        <li>Judging process may take several days</li>
        <li>You will receive an email notification when scores are published</li>
        <li>Results will be announced on the scheduled date</li>
    </ol>
</div>

<div style="text-align: center;">
    <a href="{{ route('peserta.submissions.show', $submission->id) }}" class="button">
        View Submission
    </a>
</div>

<div class="divider"></div>

<p><strong>Important Notes:</strong></p>
<ul>
    <li>You can still edit your submission before the deadline</li>
    <li>Make sure all required files are uploaded correctly</li>
    <li>Check your submission details for accuracy</li>
    <li>Keep track of the competition schedule</li>
</ul>

<p>Thank you for your participation and good luck!</p>

<p>Best regards,<br>
<strong>Caturnawa UNAS FEST 2025 Team</strong></p>
@endsection

