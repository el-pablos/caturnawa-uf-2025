<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubmissionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $submissions = Submission::with(['registration.competition'])
            ->whereHas('registration', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('peserta.submissions.index', compact('submissions'));
    }

    public function create(Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        // Check if registration is confirmed
        if ($registration->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Can only submit for confirmed registrations');
        }

        // Check if submission already exists
        if ($registration->submission) {
            return redirect()->route('peserta.submissions.show', $registration->submission);
        }

        return view('peserta.submissions.create', compact('registration'));
    }

    public function store(Request $request, Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to registration');
        }

        // Check if registration is confirmed
        if ($registration->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Can only submit for confirmed registrations');
        }

        // Check if submission already exists
        if ($registration->submission) {
            return redirect()->route('peserta.submissions.show', $registration->submission)
                ->with('error', 'Submission already exists for this registration');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'files.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar',
        ]);

        DB::beginTransaction();
        
        try {
            // Create submission
            $submission = Submission::create([
                'registration_id' => $registration->id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'draft',
                'submitted_at' => null,
            ]);

            // Handle file uploads
            if ($request->hasFile('files')) {
                $this->handleFileUploads($request->file('files'), $submission);
            }

            DB::commit();

            return redirect()->route('peserta.submissions.show', $submission)
                ->with('success', 'Submission created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create submission: ' . $e->getMessage());
        }
    }

    public function show(Submission $submission)
    {
        // Check ownership
        if ($submission->registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to submission');
        }

        $submission->load(['registration.competition']);

        return view('peserta.submissions.show', compact('submission'));
    }

    public function edit(Submission $submission)
    {
        // Check ownership
        if ($submission->registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to submission');
        }

        // Only allow editing for draft submissions
        if ($submission->status !== 'draft') {
            return redirect()->back()->with('error', 'Cannot edit submitted submission');
        }

        return view('peserta.submissions.edit', compact('submission'));
    }

    public function update(Request $request, Submission $submission)
    {
        // Check ownership
        if ($submission->registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to submission');
        }

        // Only allow editing for draft submissions
        if ($submission->status !== 'draft') {
            return redirect()->back()->with('error', 'Cannot edit submitted submission');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'files.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar',
        ]);

        DB::beginTransaction();
        
        try {
            // Update submission
            $submission->update([
                'title' => $request->title,
                'description' => $request->description,
                'updated_at' => now(),
            ]);

            // Handle file uploads
            if ($request->hasFile('files')) {
                $this->handleFileUploads($request->file('files'), $submission);
            }

            DB::commit();

            return redirect()->route('peserta.submissions.show', $submission)
                ->with('success', 'Submission updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update submission: ' . $e->getMessage());
        }
    }

    public function submit(Request $request, Submission $submission)
    {
        // Check ownership
        if ($submission->registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to submission');
        }

        // Only allow submitting for draft submissions
        if ($submission->status !== 'draft') {
            return redirect()->back()->with('error', 'Submission already submitted');
        }

        DB::beginTransaction();
        
        try {
            $submission->update([
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('peserta.submissions.show', $submission)
                ->with('success', 'Submission submitted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to submit submission: ' . $e->getMessage());
        }
    }

    public function uploadFile(Request $request, Submission $submission)
    {
        // Check ownership
        if ($submission->registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to submission');
        }

        $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar',
        ]);

        try {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('submissions/' . $submission->id, $filename, 'public');

            // Update submission files array
            $files = $submission->files ?? [];
            $files[] = [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_at' => now()->toISOString(),
            ];

            $submission->update(['files' => $files]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file' => [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteFile(Submission $submission, $filename)
    {
        // Check ownership
        if ($submission->registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to submission');
        }

        try {
            $files = $submission->files ?? [];
            $updatedFiles = [];

            foreach ($files as $file) {
                if ($file['filename'] !== $filename) {
                    $updatedFiles[] = $file;
                } else {
                    // Delete file from storage
                    Storage::disk('public')->delete($file['path']);
                }
            }

            $submission->update(['files' => $updatedFiles]);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }

    private function handleFileUploads($files, Submission $submission)
    {
        $uploadedFiles = $submission->files ?? [];

        foreach ($files as $file) {
            $filename = time() . '_' . rand(1000, 9999) . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('submissions/' . $submission->id, $filename, 'public');

            $uploadedFiles[] = [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_at' => now()->toISOString(),
            ];
        }

        $submission->update(['files' => $uploadedFiles]);
    }
}
