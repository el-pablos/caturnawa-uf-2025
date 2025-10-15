<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for managing user notification preferences
 */
class NotificationPreferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display notification preferences page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get or create notification preferences
        $preferences = $user->notificationPreference;
        
        if (!$preferences) {
            $preferences = NotificationPreference::createDefault($user->id);
        }

        $notificationTypes = NotificationPreference::getNotificationTypes();
        $emailFrequencyOptions = NotificationPreference::getEmailFrequencyOptions();
        $digestDayOptions = NotificationPreference::getDigestDayOptions();

        return view('peserta.notification-preferences.index', compact(
            'preferences',
            'notificationTypes',
            'emailFrequencyOptions',
            'digestDayOptions'
        ));
    }

    /**
     * Update notification preferences
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'registration_notifications' => 'boolean',
            'payment_notifications' => 'boolean',
            'submission_notifications' => 'boolean',
            'scoring_notifications' => 'boolean',
            'certificate_notifications' => 'boolean',
            'announcement_notifications' => 'boolean',
            'reminder_notifications' => 'boolean',
            'admin_notifications' => 'boolean',
            'email_frequency' => 'required|in:instant,daily,weekly,disabled',
            'digest_time' => 'nullable|date_format:H:i',
            'digest_day' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'email_enabled' => 'boolean',
            'sms_enabled' => 'boolean',
            'push_enabled' => 'boolean',
            'marketing_emails' => 'boolean',
            'newsletter' => 'boolean',
        ]);

        // Convert checkbox values (null = false, 'on' = true)
        $booleanFields = [
            'registration_notifications',
            'payment_notifications',
            'submission_notifications',
            'scoring_notifications',
            'certificate_notifications',
            'announcement_notifications',
            'reminder_notifications',
            'admin_notifications',
            'email_enabled',
            'sms_enabled',
            'push_enabled',
            'marketing_emails',
            'newsletter',
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->has($field);
        }

        // Get or create preferences
        $preferences = $user->notificationPreference;
        
        if (!$preferences) {
            $validated['user_id'] = $user->id;
            NotificationPreference::create($validated);
        } else {
            $preferences->update($validated);
        }

        return redirect()
            ->route('peserta.notification-preferences.index')
            ->with('success', 'Notification preferences updated successfully!');
    }

    /**
     * Reset to default preferences
     */
    public function reset()
    {
        $user = Auth::user();
        $preferences = $user->notificationPreference;

        if ($preferences) {
            $preferences->update([
                'registration_notifications' => true,
                'payment_notifications' => true,
                'submission_notifications' => true,
                'scoring_notifications' => true,
                'certificate_notifications' => true,
                'announcement_notifications' => true,
                'reminder_notifications' => true,
                'admin_notifications' => true,
                'email_frequency' => 'instant',
                'digest_time' => null,
                'digest_day' => null,
                'email_enabled' => true,
                'sms_enabled' => false,
                'push_enabled' => false,
                'marketing_emails' => false,
                'newsletter' => false,
            ]);
        } else {
            NotificationPreference::createDefault($user->id);
        }

        return redirect()
            ->route('peserta.notification-preferences.index')
            ->with('success', 'Notification preferences reset to default!');
    }
}

