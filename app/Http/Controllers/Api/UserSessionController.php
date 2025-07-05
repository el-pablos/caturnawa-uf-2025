<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserSessionController extends Controller
{
    /**
     * Get user session data including deadline reminders
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSession(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        // Check for deadline reminders
        $deadlineReminders = $this->checkDeadlineReminders($user);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleNames()->first(),
                ],
                'show_deadline_popup' => $deadlineReminders['show_popup'],
                'deadline_reminders' => $deadlineReminders['reminders'],
                'session_timestamp' => now()->toISOString(),
            ]
        ]);
    }

    /**
     * Check for deadline reminders for the user
     *
     * @param \App\Models\User $user
     * @return array
     */
    private function checkDeadlineReminders($user)
    {
        $reminders = [];
        $showPopup = false;

        // Get dismissed reminders from session
        $dismissedReminders = session('dismissed_deadline_reminders', []);

        // Get user's confirmed registrations
        $registrations = Registration::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->with(['competition', 'submissions'])
            ->get();

        foreach ($registrations as $registration) {
            // Check if this reminder is dismissed
            if (isset($dismissedReminders[$registration->id]) &&
                $dismissedReminders[$registration->id] > now()->timestamp) {
                continue;
            }
            $competition = $registration->competition;

            // Skip if no submission deadline
            if (!$competition->submission_deadline) {
                continue;
            }

            $deadline = $competition->submission_deadline;
            $now = now();

            // Check if deadline is within 7 days
            $daysUntilDeadline = $now->diffInDays($deadline, false);

            if ($daysUntilDeadline >= 0 && $daysUntilDeadline <= 7) {
                // Check if user has submitted
                $hasSubmission = $registration->submissions()
                    ->where('is_final', true)
                    ->exists();

                if (!$hasSubmission) {
                    $urgencyLevel = 'medium';
                    if ($daysUntilDeadline <= 1) {
                        $urgencyLevel = 'high';
                    } elseif ($daysUntilDeadline <= 3) {
                        $urgencyLevel = 'medium';
                    } else {
                        $urgencyLevel = 'low';
                    }

                    $reminders[] = [
                        'registration_id' => $registration->id,
                        'competition_name' => $competition->name,
                        'competition_slug' => $competition->slug,
                        'deadline' => $deadline->toISOString(),
                        'days_left' => $daysUntilDeadline,
                        'hours_left' => $now->diffInHours($deadline, false),
                        'urgency_level' => $urgencyLevel,
                        'message' => $this->getDeadlineMessage($daysUntilDeadline, $competition->name),
                    ];

                    $showPopup = true;
                }
            }
        }

        // Sort by urgency (high -> medium -> low) and days left
        usort($reminders, function ($a, $b) {
            $urgencyOrder = ['high' => 0, 'medium' => 1, 'low' => 2];

            if ($urgencyOrder[$a['urgency_level']] !== $urgencyOrder[$b['urgency_level']]) {
                return $urgencyOrder[$a['urgency_level']] <=> $urgencyOrder[$b['urgency_level']];
            }

            return $a['days_left'] <=> $b['days_left'];
        });

        return [
            'show_popup' => $showPopup,
            'reminders' => $reminders,
        ];
    }

    /**
     * Get deadline message based on days left
     *
     * @param int $daysLeft
     * @param string $competitionName
     * @return string
     */
    private function getDeadlineMessage($daysLeft, $competitionName)
    {
        if ($daysLeft == 0) {
            return "⚠️ Deadline submission {$competitionName} adalah HARI INI! Segera submit karya Anda.";
        } elseif ($daysLeft == 1) {
            return "🚨 Deadline submission {$competitionName} tinggal 1 hari lagi! Jangan sampai terlewat.";
        } elseif ($daysLeft <= 3) {
            return "⏰ Deadline submission {$competitionName} tinggal {$daysLeft} hari lagi. Segera selesaikan karya Anda.";
        } else {
            return "📅 Deadline submission {$competitionName} tinggal {$daysLeft} hari lagi. Mulai persiapkan karya Anda.";
        }
    }

    /**
     * Dismiss deadline reminder (store in session)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dismissReminder(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|integer',
            'dismiss_until' => 'nullable|in:1hour,6hours,1day,deadline',
        ]);

        $registrationId = $request->registration_id;
        $dismissUntil = $request->dismiss_until ?? '6hours';

        // Calculate dismiss until timestamp
        $dismissUntilTime = match($dismissUntil) {
            '1hour' => now()->addHour(),
            '6hours' => now()->addHours(6),
            '1day' => now()->addDay(),
            'deadline' => now()->addWeek(), // Dismiss until next week
            default => now()->addHours(6),
        };

        // Store in session
        $dismissedReminders = session('dismissed_deadline_reminders', []);
        $dismissedReminders[$registrationId] = $dismissUntilTime->timestamp;
        session(['dismissed_deadline_reminders' => $dismissedReminders]);

        return response()->json([
            'success' => true,
            'message' => 'Reminder dismissed successfully',
            'dismissed_until' => $dismissUntilTime->toISOString(),
        ]);
    }
}
