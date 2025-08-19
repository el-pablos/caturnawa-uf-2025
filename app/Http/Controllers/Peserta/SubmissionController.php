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

        // Check if registration is confirmed (auto-confirmed after payment)
        if (!in_array($registration->status, ['confirmed', 'paid'])) {
            return redirect()->back()->with('error', 'Hanya bisa submit untuk pendaftaran yang sudah dikonfirmasi');
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

        // Check if registration is confirmed (auto-confirmed after payment)
        if (!in_array($registration->status, ['confirmed', 'paid'])) {
            return redirect()->back()->with('error', 'Hanya bisa submit untuk pendaftaran yang sudah dikonfirmasi');
        }

        // Check if submission already exists
        if ($registration->submission) {
            return redirect()->route('peserta.submissions.show', $registration->submission)
                ->with('error', 'Submission already exists for this registration');
        }

        // Competition-specific validation
        if ($registration->competition->isSpcCompetition()) {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'file_karya' => 'required|file|mimes:pdf|max:51200', // 50MB for SPC
                'teknologi_yang_digunakan' => 'required|string|max:500',
                'surat_orisinalitas' => 'required|file|mimes:pdf|max:10240', // 10MB
                'surat_pengalihan_hak_cipta' => 'required|file|mimes:pdf|max:10240', // 10MB
            ]);
        } else {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'files.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar',
            ]);
        }

        DB::beginTransaction();
        
        try {
            // Prepare submission data
            $submissionData = [
                'registration_id' => $registration->id,
                'title' => $request->title,
                'description' => $request->description,
                'video_demo_link' => $request->video_demo_link,
                'social_media_link' => $request->social_media_link,
                'status' => 'draft',
                'submitted_at' => null,
            ];

            // Add SPC-specific data
            if ($registration->competition->isSpcCompetition()) {
                $submissionData['teknologi_yang_digunakan'] = $request->teknologi_yang_digunakan;
            }

            // Create submission
            $submission = Submission::create($submissionData);

            // Handle file uploads based on competition type
            if ($registration->competition->isSpcCompetition()) {
                $this->handleSpcFileUploads($request, $submission);
            } else {
                if ($request->hasFile('files')) {
                    $this->handleFileUploads($request->file('files'), $submission);
                }
            }

            DB::commit();

            return redirect()->route('peserta.submissions.show', $submission)
                ->with('success', 'Submission created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create submission: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'registration_id' => $registration->id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Gagal membuat submission. Silakan coba lagi atau hubungi administrator.');
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
                'video_demo_link' => $request->video_demo_link,
                'social_media_link' => $request->social_media_link,
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
            return redirect()->back()->with('error', 'Submission sudah di-submit sebelumnya');
        }

        // Validasi minimal requirements
        if (empty($submission->title) || empty($submission->description)) {
            return redirect()->back()->with('error', 'Judul dan deskripsi harus diisi sebelum submit final');
        }

        // Validasi minimal ada file
        if (empty($submission->files) || count($submission->files) === 0) {
            return redirect()->back()->with('error', 'Minimal harus ada 1 file sebelum submit final');
        }

        DB::beginTransaction();
        
        try {
            $submission->update([
                'is_final' => true,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            // Log submission activity
            \Log::info('Submission submitted successfully', [
                'submission_id' => $submission->id,
                'user_id' => Auth::id(),
                'submitted_at' => now(),
                'is_final' => true
            ]);

            DB::commit();

            return redirect()->route('peserta.submissions.show', $submission)
                ->with('success', 'Karya berhasil di-submit! Status telah berubah menjadi "Submitted" dan dapat dilihat oleh juri.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to submit submission: ' . $e->getMessage(), [
                'submission_id' => $submission->id,
                'user_id' => Auth::id()
            ]);
            return redirect()->back()->with('error', 'Gagal submit karya: ' . $e->getMessage());
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

            // Generate secure filename to prevent directory traversal
            $secureFilename = $this->generateSecureFilename($file->getClientOriginalExtension());
            $path = $file->storeAs('submissions/' . $submission->id, $secureFilename, 'public');

            // Update submission files array
            $files = $submission->files ?? [];
            $files[] = [
                'filename' => $secureFilename,
                'original_name' => $this->sanitizeFilename($file->getClientOriginalName()),
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
                    'filename' => $secureFilename,
                    'original_name' => $this->sanitizeFilename($file->getClientOriginalName()),
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

        // Validate filename format (UUID + extension)
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.[a-zA-Z]{2,5}$/', $filename)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid filename format'
            ], 400);
        }

        try {
            $files = $submission->files ?? [];
            $updatedFiles = [];
            $fileFound = false;

            foreach ($files as $file) {
                if ($file['filename'] !== $filename) {
                    $updatedFiles[] = $file;
                } else {
                    $fileFound = true;
                    // Validate file path before deletion
                    $filePath = $file['path'];
                    
                    // Ensure the file path is within the expected submissions directory
                    if (!str_starts_with($filePath, 'submissions/')) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid file path'
                        ], 400);
                    }

                    // Delete file from storage
                    Storage::disk('public')->delete($filePath);
                }
            }

            if (!$fileFound) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found in submission'
                ], 404);
            }

            $submission->update(['files' => $updatedFiles]);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file'
            ], 500);
        }
    }

    private function handleFileUploads($files, Submission $submission)
    {
        $uploadedFiles = $submission->files ?? [];
        $maxFileSize = 64 * 1024 * 1024; // 64MB in bytes
        $allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/zip',
            'application/x-zip-compressed',
            'image/jpeg',
            'image/png',
            'image/gif',
            'video/mp4',
            'video/avi',
            'video/quicktime',
            'video/x-msvideo'
        ];
        
        $allowedExtensions = [
            'pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'rar', 
            'jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mov'
        ];

        foreach ($files as $file) {
            // Validate file size
            if ($file->getSize() > $maxFileSize) {
                throw new \Exception("File {$file->getClientOriginalName()} exceeds maximum size of 64MB");
            }

            // Validate file type
            $mimeType = $file->getMimeType();
            if (!in_array($mimeType, $allowedMimeTypes)) {
                throw new \Exception("File type {$mimeType} is not allowed for file {$file->getClientOriginalName()}");
            }

            // Validate file extension
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $allowedExtensions)) {
                throw new \Exception("File extension {$extension} is not allowed for file {$file->getClientOriginalName()}");
            }

            // Generate secure filename
            $secureFilename = $this->generateSecureFilename($file->getClientOriginalExtension());
            
            // Store file with secure name
            $path = $file->storeAs('submissions/' . $submission->id, $secureFilename, 'public');

            $uploadedFiles[] = [
                'filename' => $secureFilename,
                'original_name' => $this->sanitizeFilename($file->getClientOriginalName()),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $mimeType,
                'uploaded_at' => now()->toISOString(),
            ];
        }

        $submission->update(['files' => $uploadedFiles]);
    }

    private function generateSecureFilename($extension)
    {
        return \Str::uuid() . '.' . $extension;
    }

    private function sanitizeFilename($filename)
    {
        // Remove potentially dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        // Limit filename length
        return substr($filename, 0, 100);
    }

    /**
     * Handle SPC-specific file uploads
     */
    private function handleSpcFileUploads(Request $request, Submission $submission)
    {
        $uploadedFiles = [];

        // Handle file_karya (main submission file)
        if ($request->hasFile('file_karya')) {
            $file = $request->file('file_karya');
            $secureFilename = $this->generateSecureFilename($file->getClientOriginalExtension());
            $path = $file->storeAs('submissions/' . $submission->id, 'karya_' . $secureFilename, 'public');
            
            $uploadedFiles[] = [
                'type' => 'file_karya',
                'filename' => 'karya_' . $secureFilename,
                'original_name' => $this->sanitizeFilename($file->getClientOriginalName()),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_at' => now()->toISOString(),
            ];
        }

        // Handle surat_orisinalitas
        if ($request->hasFile('surat_orisinalitas')) {
            $file = $request->file('surat_orisinalitas');
            $secureFilename = $this->generateSecureFilename($file->getClientOriginalExtension());
            $path = $file->storeAs('submissions/' . $submission->id, 'orisinalitas_' . $secureFilename, 'public');
            
            $uploadedFiles[] = [
                'type' => 'surat_orisinalitas',
                'filename' => 'orisinalitas_' . $secureFilename,
                'original_name' => $this->sanitizeFilename($file->getClientOriginalName()),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_at' => now()->toISOString(),
            ];
        }

        // Handle surat_pengalihan_hak_cipta
        if ($request->hasFile('surat_pengalihan_hak_cipta')) {
            $file = $request->file('surat_pengalihan_hak_cipta');
            $secureFilename = $this->generateSecureFilename($file->getClientOriginalExtension());
            $path = $file->storeAs('submissions/' . $submission->id, 'hak_cipta_' . $secureFilename, 'public');
            
            $uploadedFiles[] = [
                'type' => 'surat_pengalihan_hak_cipta',
                'filename' => 'hak_cipta_' . $secureFilename,
                'original_name' => $this->sanitizeFilename($file->getClientOriginalName()),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_at' => now()->toISOString(),
            ];
        }

        $submission->update(['files' => $uploadedFiles]);
    }
}
