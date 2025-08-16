<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Registration::with(['competition', 'payment'])
            ->where('user_id', $user->id);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by competition
        if ($request->has('competition_id')) {
            $query->where('competition_id', $request->competition_id);
        }

        $registrations = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function($registration) {
                return [
                    'id' => $registration->id,
                    'registration_number' => $registration->registration_number,
                    'competition' => [
                        'id' => $registration->competition->id,
                        'name' => $registration->competition->name,
                        'category' => $registration->competition->category,
                    ],
                    'status' => $registration->status,
                    'team_name' => $registration->team_name,
                    'payment_status' => $registration->payment?->status,
                    'created_at' => $registration->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $registrations
        ]);
    }

    public function datatable(Request $request)
    {
        $user = Auth::user();
        
        $query = Registration::with(['competition', 'payment'])
            ->where('user_id', $user->id);

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhere('team_name', 'like', "%{$search}%")
                  ->orWhereHas('competition', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Total records
        $totalRecords = $query->count();

        // Order
        if ($request->has('order')) {
            $orderColumn = $request->columns[$request->order[0]['column']]['data'] ?? 'created_at';
            $orderDirection = $request->order[0]['dir'] ?? 'desc';
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        if ($request->has('start') && $request->has('length')) {
            $query->offset($request->start)->limit($request->length);
        }

        $registrations = $query->get()->map(function($registration) {
            return [
                'id' => $registration->id,
                'registration_number' => $registration->registration_number,
                'competition_name' => $registration->competition->name,
                'competition_category' => $registration->competition->category,
                'status' => $registration->status,
                'team_name' => $registration->team_name ?? '-',
                'payment_status' => $registration->payment?->status ?? 'unpaid',
                'created_at' => $registration->created_at->format('d/m/Y H:i'),
                'actions' => view('peserta.registrations.actions', compact('registration'))->render(),
            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $registrations
        ]);
    }

    /**
     * Get registration documents
     */
    public function documents(Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $documents = $registration->documents()->get()->map(function($doc) {
            return [
                'id' => $doc->id,
                'document_type' => $doc->document_type,
                'original_name' => $doc->original_name,
                'file_size' => $doc->file_size,
                'mime_type' => $doc->mime_type,
                'is_verified' => $doc->is_verified,
                'verification_notes' => $doc->verification_notes,
                'created_at' => $doc->created_at->format('d/m/Y H:i'),
                'file_url' => $doc->file_url,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'registration' => [
                    'id' => $registration->id,
                    'registration_number' => $registration->registration_number,
                    'competition_name' => $registration->competition->name,
                ],
                'documents' => $documents,
                'required_documents' => RegistrationDocument::DOCUMENT_TYPES,
            ]
        ]);
    }

    /**
     * Upload document
     */
    public function uploadDocument(Request $request, Registration $registration)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:' . implode(',', array_keys(RegistrationDocument::DOCUMENT_TYPES)),
            'file' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120', // 5MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $documentType = $request->document_type;

            // Delete existing document if exists
            $existingDoc = $registration->documents()->where('document_type', $documentType)->first();
            if ($existingDoc) {
                Storage::disk('public')->delete($existingDoc->file_path);
                $existingDoc->delete();
            }

            // Store new file
            $fileName = time() . '_' . $documentType . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents/registrations/' . $registration->id, $fileName, 'public');

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
                'message' => 'Document uploaded successfully',
                'data' => [
                    'id' => $document->id,
                    'document_type' => $document->document_type,
                    'original_name' => $document->original_name,
                    'file_size' => $document->file_size,
                    'mime_type' => $document->mime_type,
                    'file_url' => $document->file_url,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete document
     */
    public function deleteDocument(Registration $registration, $type)
    {
        // Check ownership
        if ($registration->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $document = $registration->documents()->where('document_type', $type)->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }

        try {
            // Delete file from storage
            Storage::disk('public')->delete($document->file_path);

            // Delete database record
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage()
            ], 500);
        }
    }
}
