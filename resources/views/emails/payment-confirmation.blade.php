@extends('emails.layout')

@section('title', 'Payment Confirmed')
@section('header-subtitle', 'Payment Successful')

@section('content')
<h2>Hello, {{ $user->name }}!</h2>

<p>Great news! Your payment for <strong>{{ $competition->name }}</strong> has been confirmed!</p>

<div class="alert alert-success">
    <strong>✓ Payment Confirmed!</strong><br>
    Your registration is now active and you can proceed with your submission.
</div>

<div class="info-box">
    <h3 style="margin-top: 0; color: #667eea;">Payment Details</h3>
    <table>
        <tr>
            <td>Order ID</td>
            <td><strong>{{ $payment->order_id }}</strong></td>
        </tr>
        <tr>
            <td>Registration Number</td>
            <td>{{ $registration->registration_number }}</td>
        </tr>
        <tr>
            <td>Competition</td>
            <td>{{ $competition->name }}</td>
        </tr>
        <tr>
            <td>Amount Paid</td>
            <td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td>Payment Method</td>
            <td>{{ $payment->payment_type ?? 'Online Payment' }}</td>
        </tr>
        <tr>
            <td>Payment Status</td>
            <td><span style="color: #28a745;">{{ ucfirst($payment->status) }}</span></td>
        </tr>
        <tr>
            <td>Paid At</td>
            <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y, H:i') : 'N/A' }}</td>
        </tr>
    </table>
</div>

<div class="alert alert-info">
    <strong>What's Next?</strong>
    <ol style="margin: 10px 0; padding-left: 20px;">
        <li>Your registration status is now <strong>VERIFIED</strong></li>
        <li>You can now submit your work through the participant dashboard</li>
        <li>Join the competition WhatsApp group for updates and announcements</li>
        <li>Check the competition schedule and deadlines</li>
    </ol>
</div>

<div style="text-align: center;">
    <a href="{{ route('peserta.dashboard') }}" class="button">
        Go to Dashboard
    </a>
</div>

<div class="divider"></div>

<p><strong>Important Reminders:</strong></p>
<ul>
    <li>Submission deadline: <strong>{{ $competition->submission_deadline ? $competition->submission_deadline->format('d M Y, H:i') : 'TBA' }}</strong></li>
    <li>Make sure to submit your work before the deadline</li>
    <li>You can edit your submission until the deadline</li>
    <li>Download your invoice from the dashboard</li>
</ul>

@if($competition->whatsapp_group_link)
<div class="alert alert-warning">
    <strong>Join WhatsApp Group:</strong><br>
    Click here to join the competition WhatsApp group: 
    <a href="{{ $competition->whatsapp_group_link }}" style="color: #667eea; font-weight: 600;">Join Group</a>
</div>
@endif

<p>Thank you for your participation!</p>

<p>Best regards,<br>
<strong>Caturnawa UNAS FEST 2025 Team</strong></p>
@endsection

