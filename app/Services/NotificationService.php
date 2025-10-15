<?php

namespace App\Services;

use App\Mail\RegistrationConfirmation;
use App\Mail\PaymentConfirmation;
use App\Mail\ScoreNotification;
use App\Mail\SubmissionReceived;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Score;
use App\Models\Submission;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Check if user should receive notification
     *
     * @param \App\Models\User $user
     * @param string $type
     * @return bool
     */
    protected function shouldSendNotification($user, string $type): bool
    {
        $preference = $user->notificationPreference;

        // If no preference exists, create default and allow notification
        if (!$preference) {
            \App\Models\NotificationPreference::createDefault($user->id);
            return true;
        }

        // Check if notification type is enabled
        if (!$preference->isEnabled($type)) {
            return false;
        }

        // Check if email is enabled
        if (!$preference->email_enabled) {
            return false;
        }

        // Check email frequency
        if ($preference->notificationsDisabled()) {
            return false;
        }

        // For digest modes, queue for later (not implemented in this version)
        if ($preference->prefersDailyDigest() || $preference->prefersWeeklyDigest()) {
            // TODO: Queue for digest sending
            return false;
        }

        return true;
    }

    /**
     * Send registration confirmation email
     *
     * @param Registration $registration
     * @return bool
     */
    public function sendRegistrationConfirmation(Registration $registration): bool
    {
        // Check user preferences
        if (!$this->shouldSendNotification($registration->user, 'registration')) {
            Log::info('Registration notification skipped due to user preferences', [
                'registration_id' => $registration->id,
                'user_id' => $registration->user->id,
            ]);
            return false;
        }

        try {
            Mail::to($registration->user->email)
                ->send(new RegistrationConfirmation($registration));

            Log::info('Registration confirmation email sent', [
                'registration_id' => $registration->id,
                'user_email' => $registration->user->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send registration confirmation email', [
                'registration_id' => $registration->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send payment confirmation email
     *
     * @param Payment $payment
     * @return bool
     */
    public function sendPaymentConfirmation(Payment $payment): bool
    {
        // Check user preferences
        if (!$this->shouldSendNotification($payment->registration->user, 'payment')) {
            Log::info('Payment notification skipped due to user preferences', [
                'payment_id' => $payment->id,
                'user_id' => $payment->registration->user->id,
            ]);
            return false;
        }

        try {
            Mail::to($payment->registration->user->email)
                ->send(new PaymentConfirmation($payment));

            Log::info('Payment confirmation email sent', [
                'payment_id' => $payment->id,
                'user_email' => $payment->registration->user->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmation email', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send submission received email
     *
     * @param Submission $submission
     * @return bool
     */
    public function sendSubmissionReceived(Submission $submission): bool
    {
        // Check user preferences
        if (!$this->shouldSendNotification($submission->registration->user, 'submission')) {
            Log::info('Submission notification skipped due to user preferences', [
                'submission_id' => $submission->id,
                'user_id' => $submission->registration->user->id,
            ]);
            return false;
        }

        try {
            Mail::to($submission->registration->user->email)
                ->send(new SubmissionReceived($submission));

            Log::info('Submission received email sent', [
                'submission_id' => $submission->id,
                'user_email' => $submission->registration->user->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send submission received email', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send score notification email
     *
     * @param Score $score
     * @return bool
     */
    public function sendScoreNotification(Score $score): bool
    {
        // Check user preferences
        if (!$this->shouldSendNotification($score->submission->registration->user, 'scoring')) {
            Log::info('Score notification skipped due to user preferences', [
                'score_id' => $score->id,
                'user_id' => $score->submission->registration->user->id,
            ]);
            return false;
        }

        try {
            Mail::to($score->submission->registration->user->email)
                ->send(new ScoreNotification($score));

            Log::info('Score notification email sent', [
                'score_id' => $score->id,
                'user_email' => $score->submission->registration->user->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send score notification email', [
                'score_id' => $score->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send bulk notifications to multiple users
     *
     * @param array $users
     * @param string $subject
     * @param string $message
     * @return array
     */
    public function sendBulkNotification(array $users, string $subject, string $message): array
    {
        $sent = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                Mail::raw($message, function ($mail) use ($user, $subject) {
                    $mail->to($user->email)
                        ->subject($subject);
                });

                $sent++;
            } catch (\Exception $e) {
                $failed++;
                Log::error('Failed to send bulk notification', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'sent' => $sent,
            'failed' => $failed,
            'total' => count($users),
        ];
    }

    /**
     * Send notification to admin users
     *
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function notifyAdmins(string $subject, string $message): bool
    {
        try {
            $admins = \App\Models\User::role(['admin', 'superadmin'])->get();

            foreach ($admins as $admin) {
                Mail::raw($message, function ($mail) use ($admin, $subject) {
                    $mail->to($admin->email)
                        ->subject($subject);
                });
            }

            Log::info('Admin notification sent', [
                'subject' => $subject,
                'admin_count' => $admins->count(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send notification to judges
     *
     * @param string $subject
     * @param string $message
     * @param int|null $competitionId
     * @return bool
     */
    public function notifyJudges(string $subject, string $message, ?int $competitionId = null): bool
    {
        try {
            $query = \App\Models\User::role('juri');

            // If competition ID is provided, filter judges assigned to that competition
            if ($competitionId) {
                $query->whereHas('judgeAssignments', function ($q) use ($competitionId) {
                    $q->where('competition_id', $competitionId);
                });
            }

            $judges = $query->get();

            foreach ($judges as $judge) {
                Mail::raw($message, function ($mail) use ($judge, $subject) {
                    $mail->to($judge->email)
                        ->subject($subject);
                });
            }

            Log::info('Judge notification sent', [
                'subject' => $subject,
                'judge_count' => $judges->count(),
                'competition_id' => $competitionId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send judge notification', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}

