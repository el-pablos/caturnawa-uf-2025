<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Submission;
use App\Models\Judging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JudgingController extends Controller
{
    /**
     * Get judging form configuration with tabulator setup
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getForm(Request $request)
    {
        $user = Auth::user();

        // Check if user is a jury
        if (!$user->hasRole('juri')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Only jury members can access this endpoint.'
            ], 403);
        }

        $competitionId = $request->query('competition_id');

        // Get competitions where user is assigned as jury
        $competitions = Competition::whereHas('juries', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['submissions.registration.user'])->get();

        if ($competitionId) {
            $competitions = $competitions->where('id', $competitionId);
        }

        // Prepare tabulator configuration
        $tabulatorConfig = [
            'layout' => 'fitColumns',
            'responsiveLayout' => 'hide',
            'pagination' => 'local',
            'paginationSize' => 20,
            'movableColumns' => true,
            'resizableRows' => true,
            'selectable' => 1,
            'columns' => [
                [
                    'title' => 'ID',
                    'field' => 'id',
                    'width' => 80,
                    'sorter' => 'number'
                ],
                [
                    'title' => 'Peserta',
                    'field' => 'participant_name',
                    'minWidth' => 150,
                    'sorter' => 'string'
                ],
                [
                    'title' => 'Judul Karya',
                    'field' => 'title',
                    'minWidth' => 200,
                    'sorter' => 'string'
                ],
                [
                    'title' => 'Kompetisi',
                    'field' => 'competition_name',
                    'minWidth' => 150,
                    'sorter' => 'string'
                ],
                [
                    'title' => 'Status',
                    'field' => 'status',
                    'width' => 120,
                    'formatter' => 'html',
                    'sorter' => 'string'
                ],
                [
                    'title' => 'Skor',
                    'field' => 'score',
                    'width' => 100,
                    'editor' => 'number',
                    'editorParams' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.01
                    ],
                    'formatter' => 'money',
                    'formatterParams' => [
                        'decimal' => '.',
                        'thousand' => '',
                        'symbol' => '',
                        'precision' => 2
                    ],
                    'sorter' => 'number'
                ],
                [
                    'title' => 'Feedback',
                    'field' => 'feedback',
                    'minWidth' => 200,
                    'editor' => 'textarea',
                    'formatter' => 'textarea'
                ],
                [
                    'title' => 'Aksi',
                    'field' => 'actions',
                    'width' => 120,
                    'formatter' => 'html',
                    'cellClick' => 'actionHandler'
                ]
            ]
        ];

        // Prepare submissions data
        $submissionsData = [];
        foreach ($competitions as $competition) {
            foreach ($competition->submissions as $submission) {
                if ($submission->status !== 'submitted') continue;

                // Get existing judging by this jury
                $judging = Judging::where('submission_id', $submission->id)
                    ->where('jury_id', $user->id)
                    ->first();

                $submissionsData[] = [
                    'id' => $submission->id,
                    'participant_name' => $submission->registration->user->name,
                    'title' => $submission->title,
                    'competition_name' => $competition->name,
                    'status' => $this->getStatusBadge($submission->status),
                    'score' => $judging ? $judging->score : null,
                    'feedback' => $judging ? $judging->feedback : '',
                    'judging_id' => $judging ? $judging->id : null,
                    'actions' => $this->getActionButtons($submission->id, $judging ? $judging->id : null)
                ];
            }
        }

        // Check for pending judgings (reminder logic)
        $pendingCount = count(array_filter($submissionsData, function($item) {
            return is_null($item['score']);
        }));

        $showReminder = $pendingCount > 0;

        return response()->json([
            'success' => true,
            'data' => [
                'tabulator_config' => $tabulatorConfig,
                'submissions' => $submissionsData,
                'competitions' => $competitions->map(function($comp) {
                    return [
                        'id' => $comp->id,
                        'name' => $comp->name,
                        'submissions_count' => $comp->submissions->where('status', 'submitted')->count()
                    ];
                }),
                'statistics' => [
                    'total_submissions' => count($submissionsData),
                    'pending_judgings' => $pendingCount,
                    'completed_judgings' => count($submissionsData) - $pendingCount
                ],
                'show_reminder' => $showReminder,
                'reminder_message' => $showReminder ?
                    "Anda memiliki {$pendingCount} submission yang belum dinilai. Mohon segera berikan penilaian." : null
            ]
        ]);
    }

    /**
     * Save or update judging score
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveScore(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('juri')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000'
        ]);

        $submission = Submission::findOrFail($request->submission_id);

        // Check if jury has access to this submission
        if (!$submission->registration->competition->juries->contains($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to judge this submission'
            ], 403);
        }

        // Create or update judging
        $judging = Judging::updateOrCreate(
            [
                'submission_id' => $submission->id,
                'jury_id' => $user->id
            ],
            [
                'score' => $request->score,
                'feedback' => $request->feedback,
                'judged_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Score saved successfully',
            'data' => [
                'judging_id' => $judging->id,
                'score' => $judging->score,
                'feedback' => $judging->feedback,
                'judged_at' => $judging->judged_at->toISOString()
            ]
        ]);
    }

    /**
     * Get status badge HTML
     *
     * @param string $status
     * @return string
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'submitted' => '<span class="badge bg-primary">Submitted</span>',
            'reviewed' => '<span class="badge bg-info">Reviewed</span>',
            'scored' => '<span class="badge bg-success">Scored</span>'
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
    }

    /**
     * Get action buttons HTML
     *
     * @param int $submissionId
     * @param int|null $judgingId
     * @return string
     */
    private function getActionButtons($submissionId, $judgingId = null)
    {
        return '<div class="btn-group btn-group-sm">' .
               '<button class="btn btn-outline-primary" onclick="viewSubmission(' . $submissionId . ')">' .
               '<i class="bi bi-eye"></i></button>' .
               '<button class="btn btn-outline-success" onclick="quickScore(' . $submissionId . ')">' .
               '<i class="bi bi-star"></i></button>' .
               '</div>';
    }
}
