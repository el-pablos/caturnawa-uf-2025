<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Competition;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * Display a listing of submissions
     */
    public function index(Request $request)
    {
        $query = Submission::with(['registration.user', 'registration.competition'])
            ->orderBy('created_at', 'desc');

        // Filter by competition
        if ($request->filled('competition_id')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('competition_id', $request->competition_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('registration.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $submissions = $query->paginate(20);
        $competitions = Competition::where('is_active', true)->get();

        // Statistics
        $stats = [
            'total' => Submission::count(),
            'pending' => Submission::where('status', 'pending')->count(),
            'approved' => Submission::where('status', 'approved')->count(),
            'rejected' => Submission::where('status', 'rejected')->count(),
        ];

        return view('admin.submissions.index', compact('submissions', 'competitions', 'stats'));
    }

    /**
     * Display the specified submission
     */
    public function show(Submission $submission)
    {
        $submission->load(['registration.user', 'registration.competition', 'files']);
        
        return view('admin.submissions.show', compact('submission'));
    }

    /**
     * Approve submission
     */
    public function approve(Submission $submission)
    {
        try {
            $submission->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Submission berhasil disetujui.'
                ]);
            }

            return back()->with('success', 'Submission berhasil disetujui.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyetujui submission: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Gagal menyetujui submission: ' . $e->getMessage());
        }
    }

    /**
     * Reject submission
     */
    public function reject(Request $request, Submission $submission)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $submission->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Submission berhasil ditolak.'
                ]);
            }

            return back()->with('success', 'Submission berhasil ditolak.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menolak submission: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Gagal menolak submission: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified submission
     */
    public function destroy(Submission $submission)
    {
        try {
            // Delete associated files if any
            if ($submission->files) {
                foreach ($submission->files as $file) {
                    $filePath = storage_path('app/public/submissions/' . $file);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            $submission->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Submission berhasil dihapus.'
                ]);
            }

            return back()->with('success', 'Submission berhasil dihapus.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus submission: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Gagal menghapus submission: ' . $e->getMessage());
        }
    }
}
