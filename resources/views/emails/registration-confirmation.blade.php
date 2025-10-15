@extends('emails.layout')

@section('title', 'Registration Confirmation')
@section('header-subtitle', 'Registration Successful')

@section('content')
<h2>Hello, {{ $user->name }}!</h2>

<p>Thank you for registering for <strong>{{ $competition->name }}</strong> at Caturnawa UNAS FEST 2025!</p>

<div class="alert alert-success">
    <strong>✓ Registration Successful!</strong><br>
    Your registration has been received and is being processed.
</div>

<div class="info-box">
    <h3 style="margin-top: 0; color: #667eea;">Registration Details</h3>
    <table>
        <tr>
            <td>Registration Number</td>
            <td><strong>{{ $registration->registration_number }}</strong></td>
        </tr>
        <tr>
            <td>Competition</td>
            <td>{{ $competition->name }}</td>
        </tr>
        <tr>
            <td>Category</td>
            <td>{{ ucfirst($competition->category) }}</td>
        </tr>
        @if($registration->team_name)
        <tr>
            <td>Team Name</td>
            <td>{{ $registration->team_name }}</td>
        </tr>
        @endif
        <tr>
            <td>Registration Fee</td>
            <td>Rp {{ number_format($registration->amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td><span style="color: #ffc107;">{{ ucfirst($registration->status) }}</span></td>
        </tr>
        <tr>
            <td>Registered At</td>
            <td>{{ $registration->created_at->format('d M Y, H:i') }}</td>
        </tr>
    </table>
</div>

<div class="alert alert-info">
    <strong>Next Steps:</strong>
    <ol style="margin: 10px 0; padding-left: 20px;">
        <li>Complete your payment of <strong>Rp {{ number_format($registration->amount, 0, ',', '.') }}</strong></li>
        <li>Wait for admin verification (usually within 24 hours)</li>
        <li>Once verified, you can submit your work</li>
        <li>Join the WhatsApp group for updates</li>
    </ol>
</div>

<div style="text-align: center;">
    <a href="{{ route('peserta.registrations.show', $registration->id) }}" class="button">
        View Registration Details
    </a>
</div>

<div class="divider"></div>

<p><strong>Important Information:</strong></p>
<ul>
    <li>Keep your registration number safe for future reference</li>
    <li>Payment must be completed within 24 hours</li>
    <li>Check your email regularly for updates</li>
    <li>Contact us if you have any questions</li>
</ul>

<p>If you have any questions, please don't hesitate to contact us.</p>

<p>Best regards,<br>
<strong>Caturnawa UNAS FEST 2025 Team</strong></p>
@endsection

