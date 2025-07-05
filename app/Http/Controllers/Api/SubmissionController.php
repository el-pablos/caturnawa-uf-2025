<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Get submission details with draft status information
     *
     * @param Submission $submission
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Submission $submission)
    {
        // Check ownership for peserta
        if (Auth::user()->hasRole('peserta') && $submission->registration->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to submission'
            ], 403);
        }

        $submission->load(['registration.user', 'registration.competition']);

        // Determine if submission can be edited
        $canEdit = $this->canEditSubmission($submission);

        // Get status message
        $statusMessage = $this->getStatusMessage($submission);

        $data = [
            'id' => $submission->id,
            'title' => $submission->title,
            'description' => $submission->description,
            'video_demo_link' => $submission->video_demo_link,
            'social_media_link' => $submission->social_media_link,
            'status' => $submission->status,
            'status_label' => $submission->status_label,
            'status_class' => $submission->status_class,
            'is_final' => $submission->is_final,
            'submitted_at' => $submission->submitted_at?->toISOString(),
            'can_edit' => $canEdit,
            'status_message' => $statusMessage,
            'files' => $submission->files ?? [],
            'file_size_formatted' => $submission->file_size_formatted,
            'score' => $submission->score,
            'feedback' => $submission->feedback,
            'is_scored' => $submission->is_scored,
            'scored_at' => $submission->scored_at?->toISOString(),
            'registration' => [
                'id' => $submission->registration->id,
                'registration_number' => $submission->registration->registration_number,
                'competition' => [
                    'id' => $submission->registration->competition->id,
                    'name' => $submission->registration->competition->name,
                    'submission_deadline' => $submission->registration->competition->submission_deadline?->toISOString(),
                ],
                'user' => [
                    'id' => $submission->registration->user->id,
                    'name' => $submission->registration->user->name,
                    'email' => $submission->registration->user->email,
                ]
            ],
            'created_at' => $submission->created_at->toISOString(),
            'updated_at' => $submission->updated_at->toISOString(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Check if submission can be edited
     *
     * @param Submission $submission
     * @return bool
     */
    private function canEditSubmission(Submission $submission)
    {
        // Can't edit if already submitted (final)
        if ($submission->is_final || $submission->status === 'submitted') {
            return false;
        }

        // Can't edit if deadline has passed
        $competition = $submission->registration->competition;
        if ($competition->submission_deadline && now() > $competition->submission_deadline) {
            return false;
        }

        // Can edit if status is draft
        return $submission->status === 'draft';
    }

    /**
     * Get status message for submission
     *
     * @param Submission $submission
     * @return array
     */
    private function getStatusMessage(Submission $submission)
    {
        $competition = $submission->registration->competition;
        $deadline = $competition->submission_deadline;

        switch ($submission->status) {
            case 'draft':
                if ($deadline) {
                    $daysLeft = now()->diffInDays($deadline, false);

                    if ($daysLeft < 0) {
                        return [
                            'type' => 'danger',
                            'title' => 'Deadline Terlewat',
                            'message' => 'Deadline submission telah terlewat. Anda tidak dapat lagi mengedit atau submit karya ini.',
                            'icon' => 'bi-exclamation-triangle-fill'
                        ];
                    } elseif ($daysLeft == 0) {
                        return [
                            'type' => 'warning',
                            'title' => 'Draft - Deadline Hari Ini!',
                            'message' => 'Karya Anda masih dalam status draft. Deadline submission adalah HARI INI! Segera finalisasi dan submit karya Anda.',
                            'icon' => 'bi-clock-fill'
                        ];
                    } elseif ($daysLeft == 1) {
                        return [
                            'type' => 'warning',
                            'title' => 'Draft - Deadline Besok',
                            'message' => 'Karya Anda masih dalam status draft. Deadline submission tinggal 1 hari lagi. Jangan lupa untuk submit sebelum deadline.',
                            'icon' => 'bi-clock'
                        ];
                    } elseif ($daysLeft <= 3) {
                        return [
                            'type' => 'info',
                            'title' => 'Draft - Segera Submit',
                            'message' => "Karya Anda masih dalam status draft. Deadline submission tinggal {$daysLeft} hari lagi. Pastikan untuk submit sebelum deadline.",
                            'icon' => 'bi-info-circle'
                        ];
                    } else {
                        return [
                            'type' => 'primary',
                            'title' => 'Draft - Dapat Diedit',
                            'message' => "Karya Anda dalam status draft dan dapat diedit. Deadline submission: {$deadline->format('d M Y, H:i')}",
                            'icon' => 'bi-pencil-square'
                        ];
                    }
                } else {
                    return [
                        'type' => 'primary',
                        'title' => 'Draft - Dapat Diedit',
                        'message' => 'Karya Anda dalam status draft dan dapat diedit. Jangan lupa untuk submit ketika sudah siap.',
                        'icon' => 'bi-pencil-square'
                    ];
                }
                break;

            case 'submitted':
                return [
                    'type' => 'success',
                    'title' => 'Berhasil Disubmit',
                    'message' => 'Karya Anda telah berhasil disubmit dan sedang dalam proses penilaian oleh juri.',
                    'icon' => 'bi-check-circle-fill'
                ];

            case 'reviewed':
                return [
                    'type' => 'info',
                    'title' => 'Sedang Direview',
                    'message' => 'Karya Anda sedang dalam proses review oleh juri.',
                    'icon' => 'bi-eye-fill'
                ];

            case 'scored':
                return [
                    'type' => 'success',
                    'title' => 'Telah Dinilai',
                    'message' => 'Karya Anda telah dinilai oleh juri. Lihat skor dan feedback di bawah.',
                    'icon' => 'bi-award-fill'
                ];

            default:
                return [
                    'type' => 'secondary',
                    'title' => 'Status: ' . ucfirst($submission->status),
                    'message' => 'Status submission: ' . $submission->status_label,
                    'icon' => 'bi-info-circle'
                ];
        }
    }
}
