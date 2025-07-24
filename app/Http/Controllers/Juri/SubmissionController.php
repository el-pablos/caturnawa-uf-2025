<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Controller untuk mengelola review submission dari sisi juri
 * 
 * Juri dapat melihat dan memberikan komentar pada submission peserta
 */
class SubmissionController extends Controller
{
    /**
     * Tampilkan daftar submission yang perlu direview
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $jury = Auth::user();

            // Get all submissions that are submitted (remove jury filter for now)
            $query = Submission::with(['registration.user', 'registration.competition'])
                ->where(function($q) {
                    $q->where('status', 'submitted')
                      ->orWhere(function($subQ) {
                          $subQ->where('is_final', true)
                               ->whereNull('status');
                      });
                })
                ->whereNotNull('submitted_at')
                ->orderBy('submitted_at', 'desc');

        // Filter berdasarkan kompetisi
        if ($request->filled('competition_id')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('competition_id', $request->competition_id);
            });
        }

        // Filter berdasarkan status review
        if ($request->filled('review_status')) {
            if ($request->review_status === 'reviewed') {
                $query->whereHas('comments', function ($q) use ($jury) {
                    $q->where('jury_id', $jury->id);
                });
            } elseif ($request->review_status === 'not_reviewed') {
                $query->whereDoesntHave('comments', function ($q) use ($jury) {
                    $q->where('jury_id', $jury->id);
                });
            }
        }

            $submissions = $query->paginate(20);

            // Get all competitions for filter (remove jury filter)
            $competitions = Competition::orderBy('name')->get();

            // Add review status and score info to each submission
            foreach ($submissions as $submission) {
                try {
                    $submission->is_reviewed_by_me = $submission->comments()
                        ->where('jury_id', $jury->id)
                        ->exists();

                    // Add jury score information
                    $submission->jury_score = \App\Models\Score::where('competition_id', $submission->registration->competition_id)
                        ->where('registration_id', $submission->registration_id)
                        ->where('jury_id', $jury->id)
                        ->first();
                } catch (\Exception $e) {
                    $submission->is_reviewed_by_me = false;
                    $submission->jury_score = null;
                }
            }

            return view('juri.submissions.index', compact('submissions', 'competitions'));
        } catch (\Exception $e) {
            \Log::error('Juri submissions index error: ' . $e->getMessage());

            // Return empty data if error occurs
            $submissions = collect();
            $competitions = collect();
            return view('juri.submissions.index', compact('submissions', 'competitions'));
        }
    }

    /**
     * Tampilkan detail submission
     * 
     * @param \App\Models\Submission $submission
     * @return \Illuminate\View\View
     */
    public function show(Submission $submission)
    {
        try {
            $jury = Auth::user();

            // Skip jury access check for now (can be implemented later)
            // if (!$submission->registration->competition->juries->contains($jury->id)) {
            //     abort(403, 'Anda tidak memiliki akses ke submission ini.');
            // }

            $submission->load([
                'registration.user',
                'registration.competition',
                'comments.jury',
                'files'
            ]);

            // Get comments from this jury
            try {
                $myComments = $submission->comments()
                    ->where('jury_id', $jury->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } catch (\Exception $e) {
                $myComments = collect();
            }

            // Get all comments (for reference)
            try {
                $allComments = $submission->comments()
                    ->with('jury')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } catch (\Exception $e) {
                $allComments = collect();
            }

            // Get my score for this submission
            try {
                $myScore = \App\Models\Score::where('competition_id', $submission->registration->competition_id)
                    ->where('registration_id', $submission->registration_id)
                    ->where('jury_id', $jury->id)
                    ->first();
            } catch (\Exception $e) {
                $myScore = null;
            }

            return view('juri.submissions.show', compact('submission', 'myComments', 'allComments', 'myScore'));
        } catch (\Exception $e) {
            \Log::error('Juri submission show error: ' . $e->getMessage());

            // Return with empty data if error occurs
            $myComments = collect();
            $allComments = collect();
            $myScore = null;

            return view('juri.submissions.show', compact('submission', 'myComments', 'allComments', 'myScore'));
        }
    }

    /**
     * Tambahkan komentar pada submission
     * 
     * @param \App\Models\Submission $submission
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addComment(Submission $submission, Request $request)
    {
        try {
            $jury = Auth::user();

            // Skip jury access check for now
            // if (!$submission->registration->competition->juries->contains($jury->id)) {
            //     abort(403, 'Anda tidak memiliki akses ke submission ini.');
            // }

            $request->validate([
                'comment' => 'required|string|max:2000',
                'rating' => 'nullable|integer|min:1|max:5',
            ]);

            $submission->comments()->create([
                'jury_id' => $jury->id,
                'comment' => $request->comment,
                'rating' => $request->rating,
                'created_at' => now(),
            ]);

            // Update submission review status
            $submission->update([
                'reviewed_at' => now(),
                'review_status' => 'reviewed',
            ]);

            return back()->with('success', 'Komentar berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Juri add comment error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan komentar: ' . $e->getMessage());
        }
    }

    /**
     * Download file submission
     * 
     * @param \App\Models\Submission $submission
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadFile(Submission $submission, $filename)
    {
        try {
            $jury = Auth::user();

            // Skip jury access check for now
            // if (!$submission->registration->competition->juries->contains($jury->id)) {
            //     abort(403, 'Anda tidak memiliki akses ke file ini.');
            // }

            // Check if file exists in submission
            $file = $submission->files()->where('filename', $filename)->first();
            if (!$file) {
                abort(404, 'File tidak ditemukan.');
            }

            $filePath = 'submissions/' . $submission->id . '/' . $filename;

            if (!Storage::disk('private')->exists($filePath)) {
                abort(404, 'File tidak ditemukan di storage.');
            }

            return Storage::disk('private')->download($filePath, $file->original_name);
        } catch (\Exception $e) {
            \Log::error('Juri download file error: ' . $e->getMessage());
            abort(500, 'Gagal mengunduh file.');
        }
    }

    /**
     * Bulk review submissions
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkReview(Request $request)
    {
        $jury = Auth::user();

        $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'exists:submissions,id',
            'bulk_comment' => 'required|string|max:2000',
            'bulk_rating' => 'nullable|integer|min:1|max:5',
        ]);

        try {
            $submissions = Submission::whereIn('id', $request->submission_ids)
                ->whereHas('registration.competition.juries', function ($q) use ($jury) {
                    $q->where('user_id', $jury->id);
                })
                ->get();

            foreach ($submissions as $submission) {
                // Add comment
                $submission->comments()->create([
                    'jury_id' => $jury->id,
                    'comment' => $request->bulk_comment,
                    'rating' => $request->bulk_rating,
                    'created_at' => now(),
                ]);

                // Update submission status
                $submission->update([
                    'reviewed_at' => now(),
                    'review_status' => 'reviewed',
                ]);
            }

            return back()->with('success', 'Berhasil menambahkan komentar ke ' . $submissions->count() . ' submission.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan bulk review: ' . $e->getMessage());
        }
    }
}
