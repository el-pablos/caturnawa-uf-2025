@extends('layouts.peserta')

@section('title', 'Notification Preferences')

@section('page-title', 'Notification Preferences')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Notification Preferences Form -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-bell me-2"></i>Manage Your Notifications
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Customize how and when you receive notifications from Caturnawa UNAS FEST 2025.
                </p>

                <form method="POST" action="{{ route('peserta.notification-preferences.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Email Frequency Settings -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-clock-history me-2"></i>Email Frequency
                        </h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                @foreach($emailFrequencyOptions as $value => $label)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="email_frequency" 
                                               id="frequency_{{ $value }}" value="{{ $value }}"
                                               {{ $preferences->email_frequency === $value ? 'checked' : '' }}>
                                        <label class="form-check-label" for="frequency_{{ $value }}">
                                            <strong>{{ $label }}</strong>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Digest Settings (shown when daily or weekly is selected) -->
                        <div id="digest-settings" class="mt-3" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="digest_time" class="form-label">Digest Time</label>
                                    <input type="time" class="form-control" id="digest_time" name="digest_time" 
                                           value="{{ $preferences->digest_time ?? '09:00' }}">
                                    <small class="text-muted">Time to receive daily/weekly digest</small>
                                </div>
                                <div class="col-md-6" id="digest-day-container" style="display: none;">
                                    <label for="digest_day" class="form-label">Digest Day</label>
                                    <select class="form-select" id="digest_day" name="digest_day">
                                        <option value="">Select Day</option>
                                        @foreach($digestDayOptions as $value => $label)
                                            <option value="{{ $value }}" {{ $preferences->digest_day === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Day to receive weekly digest</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Notification Types -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-list-check me-2"></i>Notification Types
                        </h6>
                        <p class="text-muted small mb-3">
                            Choose which types of notifications you want to receive
                        </p>

                        <div class="row">
                            @foreach($notificationTypes as $key => $label)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="{{ $key }}_notifications" name="{{ $key }}_notifications"
                                                       {{ $preferences->{$key . '_notifications'} ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $key }}_notifications">
                                                    <strong>{{ $label }}</strong>
                                                </label>
                                            </div>
                                            <small class="text-muted">
                                                @switch($key)
                                                    @case('registration')
                                                        Receive confirmation when you register for a competition
                                                        @break
                                                    @case('payment')
                                                        Get notified when your payment is confirmed
                                                        @break
                                                    @case('submission')
                                                        Confirmation when you submit your work
                                                        @break
                                                    @case('scoring')
                                                        Get notified when your scores are published
                                                        @break
                                                    @case('certificate')
                                                        Notification when your certificate is ready
                                                        @break
                                                    @case('announcement')
                                                        Important announcements and updates
                                                        @break
                                                    @case('reminder')
                                                        Deadline and event reminders
                                                        @break
                                                    @case('admin')
                                                        Administrative notifications
                                                        @break
                                                @endswitch
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    <!-- Channel Preferences -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-send me-2"></i>Notification Channels
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   id="email_enabled" name="email_enabled"
                                                   {{ $preferences->email_enabled ? 'checked' : '' }}>
                                            <label class="form-check-label" for="email_enabled">
                                                <i class="bi bi-envelope me-1"></i><strong>Email</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">Receive notifications via email</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   id="sms_enabled" name="sms_enabled" disabled
                                                   {{ $preferences->sms_enabled ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sms_enabled">
                                                <i class="bi bi-phone me-1"></i><strong>SMS</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">Coming soon</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   id="push_enabled" name="push_enabled" disabled
                                                   {{ $preferences->push_enabled ? 'checked' : '' }}>
                                            <label class="form-check-label" for="push_enabled">
                                                <i class="bi bi-app-indicator me-1"></i><strong>Push</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">Coming soon</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Additional Preferences -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-gear me-2"></i>Additional Preferences
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   id="marketing_emails" name="marketing_emails"
                                                   {{ $preferences->marketing_emails ? 'checked' : '' }}>
                                            <label class="form-check-label" for="marketing_emails">
                                                <strong>Marketing Emails</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">Receive promotional emails and offers</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                   id="newsletter" name="newsletter"
                                                   {{ $preferences->newsletter ? 'checked' : '' }}>
                                            <label class="form-check-label" for="newsletter">
                                                <strong>Newsletter</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">Receive monthly newsletter</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#resetModal">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset to Default
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Preferences
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-2">
                    <i class="bi bi-info-circle me-2"></i>About Notifications
                </h6>
                <ul class="mb-0 small text-muted">
                    <li><strong>Instant:</strong> Receive emails immediately when events occur</li>
                    <li><strong>Daily Digest:</strong> Receive a summary of all notifications once per day</li>
                    <li><strong>Weekly Digest:</strong> Receive a summary of all notifications once per week</li>
                    <li><strong>Disabled:</strong> Do not receive any email notifications (not recommended)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset to Default?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reset all notification preferences to default settings?</p>
                <p class="text-muted small mb-0">This will enable all notification types and set email frequency to instant.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('peserta.notification-preferences.reset') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const frequencyRadios = document.querySelectorAll('input[name="email_frequency"]');
    const digestSettings = document.getElementById('digest-settings');
    const digestDayContainer = document.getElementById('digest-day-container');

    function updateDigestSettings() {
        const selectedFrequency = document.querySelector('input[name="email_frequency"]:checked').value;
        
        if (selectedFrequency === 'daily' || selectedFrequency === 'weekly') {
            digestSettings.style.display = 'block';
            
            if (selectedFrequency === 'weekly') {
                digestDayContainer.style.display = 'block';
            } else {
                digestDayContainer.style.display = 'none';
            }
        } else {
            digestSettings.style.display = 'none';
        }
    }

    frequencyRadios.forEach(radio => {
        radio.addEventListener('change', updateDigestSettings);
    });

    // Initialize on page load
    updateDigestSettings();
});
</script>
@endpush

