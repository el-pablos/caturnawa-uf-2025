<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegistrationDocumentController extends Controller
{
    /**
     * Get documents for a registration
     */
    public function index(Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to registration documents'
            ], 403);
        }

        $documents = $registration->documents()
            ->with('verifier')
            ->orderBy('document_type')
            ->get()
            ->map(function ($document) {
                return [
                    'id' => $document->id,
                    'document_type' => $document->document_type,
                    'document_type_name' => $document->document_type_name,
                    'original_name' => $document->original_name,
                    'file_url' => $document->file_url,
                    'file_size' => $document->file_size_formatted,
                    'mime_type' => $document->mime_type,
                    'is_image' => $document->isImage(),
                    'is_verified' => $document->is_verified,
                    'verification_notes' => $document->verification_notes,
                    'verified_by' => $document->verifier ? [
                        'id' => $document->verifier->id,
                        'name' => $document->verifier->name,
                    ] : null,
                    'verified_at' => $document->verified_at?->toISOString(),
                    'uploaded_at' => $document->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'registration_id' => $registration->id,
                'documents' => $documents,
                'required_types' => array_keys(RegistrationDocument::DOCUMENT_TYPES),
                'document_types' => RegistrationDocument::DOCUMENT_TYPES,
            ]
        ]);
    }

    /**
     * Upload a document
     */
    public function store(Request $request, Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to registration'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:' . implode(',', array_keys(RegistrationDocument::DOCUMENT_TYPES)),
            'file' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120', // 5MB max
        ], [
            'document_type.required' => 'Tipe dokumen harus dipilih',
            'document_type.in' => 'Tipe dokumen tidak valid',
            'file.required' => 'File harus dipilih',
            'file.file' => 'File tidak valid',
            'file.mimes' => 'File harus berformat JPEG, JPG, PNG, atau PDF',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $documentType = $request->document_type;

            // Check if document already exists for this type
            $existingDocument = $registration->documents()
                ->where('document_type', $documentType)
                ->first();

            if ($existingDocument) {
                // Delete old file
                if (Storage::exists($existingDocument->file_path)) {
                    Storage::delete($existingDocument->file_path);
                }
                $existingDocument->delete();
            }

            // Generate unique filename
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = "registration-documents/{$registration->id}/{$documentType}";

            // Store file
            $filePath = $file->storeAs($path, $filename, 'public');

            // Create document record
            $document = RegistrationDocument::create([
                'registration_id' => $registration->id,
                'document_type' => $documentType,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'is_verified' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload',
                'data' => [
                    'id' => $document->id,
                    'document_type' => $document->document_type,
                    'document_type_name' => $document->document_type_name,
                    'original_name' => $document->original_name,
                    'file_url' => $document->file_url,
                    'file_size' => $document->file_size_formatted,
                    'mime_type' => $document->mime_type,
                    'is_image' => $document->isImage(),
                    'is_verified' => $document->is_verified,
                    'uploaded_at' => $document->created_at->toISOString(),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Document upload failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a document
     */
    public function destroy(Registration $registration, RegistrationDocument $document)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to registration'
            ], 403);
        }

        // Check if document belongs to registration
        if ($document->registration_id !== $registration->id) {
            return response()->json([
                'success' => false,
                'message' => 'Document does not belong to this registration'
            ], 403);
        }

        try {
            // Delete file from storage
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }

            // Delete document record
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            \Log::error('Document deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}
