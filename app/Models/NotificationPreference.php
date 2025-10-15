<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for managing user notification preferences
 */
class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_notifications',
        'payment_notifications',
        'submission_notifications',
        'scoring_notifications',
        'certificate_notifications',
        'announcement_notifications',
        'reminder_notifications',
        'admin_notifications',
        'email_frequency',
        'digest_time',
        'digest_day',
        'email_enabled',
        'sms_enabled',
        'push_enabled',
        'marketing_emails',
        'newsletter',
    ];

    protected $casts = [
        'registration_notifications' => 'boolean',
        'payment_notifications' => 'boolean',
        'submission_notifications' => 'boolean',
        'scoring_notifications' => 'boolean',
        'certificate_notifications' => 'boolean',
        'announcement_notifications' => 'boolean',
        'reminder_notifications' => 'boolean',
        'admin_notifications' => 'boolean',
        'email_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'marketing_emails' => 'boolean',
        'newsletter' => 'boolean',
    ];

    /**
     * Relationship: Belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a specific notification type is enabled
     *
     * @param string $type
     * @return bool
     */
    public function isEnabled(string $type): bool
    {
        $field = $type . '_notifications';
        
        if (!in_array($field, $this->fillable)) {
            return false;
        }

        return $this->{$field} ?? false;
    }

    /**
     * Check if user should receive notification immediately
     *
     * @return bool
     */
    public function shouldSendInstant(): bool
    {
        return $this->email_frequency === 'instant' && $this->email_enabled;
    }

    /**
     * Check if user prefers daily digest
     *
     * @return bool
     */
    public function prefersDailyDigest(): bool
    {
        return $this->email_frequency === 'daily';
    }

    /**
     * Check if user prefers weekly digest
     *
     * @return bool
     */
    public function prefersWeeklyDigest(): bool
    {
        return $this->email_frequency === 'weekly';
    }

    /**
     * Check if notifications are disabled
     *
     * @return bool
     */
    public function notificationsDisabled(): bool
    {
        return $this->email_frequency === 'disabled' || !$this->email_enabled;
    }

    /**
     * Get all notification types
     *
     * @return array
     */
    public static function getNotificationTypes(): array
    {
        return [
            'registration' => 'Registration Confirmations',
            'payment' => 'Payment Confirmations',
            'submission' => 'Submission Receipts',
            'scoring' => 'Score Notifications',
            'certificate' => 'Certificate Notifications',
            'announcement' => 'Announcements',
            'reminder' => 'Reminders',
            'admin' => 'Admin Notifications',
        ];
    }

    /**
     * Get email frequency options
     *
     * @return array
     */
    public static function getEmailFrequencyOptions(): array
    {
        return [
            'instant' => 'Instant (Receive emails immediately)',
            'daily' => 'Daily Digest (Once per day)',
            'weekly' => 'Weekly Digest (Once per week)',
            'disabled' => 'Disabled (No email notifications)',
        ];
    }

    /**
     * Get digest day options
     *
     * @return array
     */
    public static function getDigestDayOptions(): array
    {
        return [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];
    }

    /**
     * Create default preferences for a user
     *
     * @param int $userId
     * @return NotificationPreference
     */
    public static function createDefault(int $userId): NotificationPreference
    {
        return self::create([
            'user_id' => $userId,
            'registration_notifications' => true,
            'payment_notifications' => true,
            'submission_notifications' => true,
            'scoring_notifications' => true,
            'certificate_notifications' => true,
            'announcement_notifications' => true,
            'reminder_notifications' => true,
            'admin_notifications' => true,
            'email_frequency' => 'instant',
            'email_enabled' => true,
            'sms_enabled' => false,
            'push_enabled' => false,
            'marketing_emails' => false,
            'newsletter' => false,
        ]);
    }
}

