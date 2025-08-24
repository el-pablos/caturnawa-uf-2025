<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationDocument;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     */
    public function index()
    {
        $competitions = Competition::select('id', 'name')->get();
        $statistics = $this->getStatistics();
        
        return view('admin.documents.index', compact('competitions', 'statistics'));
    }

    /**
     * Get documents data for DataTables
     */
    public function datatable(Request $request)
    {
        $query = RegistrationDocument::with([
            'registration.user:id,name',
            'registration.competition:id,name',
            'verifiedBy:id,name'
        ]);

        // Apply filters
        if ($request->filled('competition_id')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('competition_id', $request->competition_id);
            });
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'verified':
                    $query->where('is_verified', true);
                    break;
                case 'rejected':
                    $query->where('is_verified', false)
                          ->whereNotNull('verification_notes');
                    break;
                case 'pending':
                    $query->where('is_verified', false)
                          ->whereNull('verification_notes');
                    break;
            }
        }

        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('registration.user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%");
                })->orWhereHas('registration', function ($regQuery) use ($searchTerm) {
                    $regQuery->where('registration_number', 'like', "%{$searchTerm}%");
                });
            });
        }

        return DataTables::of($query)
            ->addColumn('document_type_name', function ($document) {
                return RegistrationDocument::DOCUMENT_TYPES[$document->document_type] ?? $document->document_type;
            })
            ->addColumn('file_size', function ($document) {
                return $this->formatFileSize($document->file_size);
            })
            ->addColumn('is_image', function ($document) {
                return in_array(strtolower(pathinfo($document->original_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
            })
            ->addColumn('file_url', function ($document) {
                return asset('storage/' . $document->file_path);
            })
            ->make(true);
    }

    /**
     * Show the specified document.
     */
    public function show($id)
    {
        try {
            $document = RegistrationDocument::with([
                'registration.user:id,name',
                'registration.competition:id,name',
                'verifiedBy:id,name'
            ])->findOrFail($id);

            $document->document_type_name = RegistrationDocument::DOCUMENT_TYPES[$document->document_type] ?? $document->document_type;
            $document->file_size = $this->formatFileSize($document->file_size);
            $document->is_image = in_array(strtolower(pathinfo($document->original_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
            $document->file_url = asset('storage/' . $document->file_path);

            return response()->json([
                'success' => true,
                'data' => $document
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Verify a document
     */
    public function verify(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $document = RegistrationDocument::findOrFail($id);
            
            $document->update([
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'verification_notes' => $request->verification_notes
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diverifikasi'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a document
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'verification_notes' => 'required|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            $document = RegistrationDocument::findOrFail($id);
            
            $document->update([
                'is_verified' => false,
                'verified_at' => null,
                'verified_by' => null,
                'verification_notes' => $request->verification_notes
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen ditolak dan peserta akan diberitahu'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk verify documents
     */
    public function bulkVerify(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:registration_documents,id'
        ]);

        try {
            DB::beginTransaction();

            $verifiedCount = RegistrationDocument::whereIn('id', $request->document_ids)
                ->update([
                    'is_verified' => true,
                    'verified_at' => now(),
                    'verified_by' => auth()->id(),
                    'verification_notes' => null
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diverifikasi secara massal',
                'verified_count' => $verifiedCount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export documents data
     */
    public function export(Request $request)
    {
        $query = RegistrationDocument::with([
            'registration.user:id,name,email',
            'registration.competition:id,name',
            'verifiedBy:id,name'
        ]);

        // Apply same filters as datatable
        if ($request->filled('competition_id')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('competition_id', $request->competition_id);
            });
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'verified':
                    $query->where('is_verified', true);
                    break;
                case 'rejected':
                    $query->where('is_verified', false)
                          ->whereNotNull('verification_notes');
                    break;
                case 'pending':
                    $query->where('is_verified', false)
                          ->whereNull('verification_notes');
                    break;
            }
        }

        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('registration.user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%");
                })->orWhereHas('registration', function ($regQuery) use ($searchTerm) {
                    $regQuery->where('registration_number', 'like', "%{$searchTerm}%");
                });
            });
        }

        $documents = $query->get();

        $filename = 'dokumen_registrasi_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($documents) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // CSV Headers
            fputcsv($file, [
                'No Registrasi',
                'Nama Peserta',
                'Email',
                'Kompetisi',
                'Jenis Dokumen',
                'Nama File',
                'Ukuran File',
                'Status Verifikasi',
                'Tanggal Upload',
                'Tanggal Verifikasi',
                'Diverifikasi Oleh',
                'Catatan'
            ]);

            foreach ($documents as $document) {
                $status = 'Menunggu Verifikasi';
                if ($document->is_verified) {
                    $status = 'Terverifikasi';
                } elseif ($document->verification_notes) {
                    $status = 'Perlu Revisi';
                }

                fputcsv($file, [
                    $document->registration->registration_number,
                    $document->registration->user->name,
                    $document->registration->user->email,
                    $document->registration->competition->name,
                    RegistrationDocument::DOCUMENT_TYPES[$document->document_type] ?? $document->document_type,
                    $document->original_name,
                    $this->formatFileSize($document->file_size),
                    $status,
                    $document->created_at->format('d/m/Y H:i'),
                    $document->verified_at ? $document->verified_at->format('d/m/Y H:i') : '',
                    $document->verifiedBy ? $document->verifiedBy->name : '',
                    $document->verification_notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get statistics for dashboard
     */
    public function statistics()
    {
        $statistics = $this->getStatistics();
        
        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Get document statistics
     */
    private function getStatistics()
    {
        $total = RegistrationDocument::count();
        $verified = RegistrationDocument::where('is_verified', true)->count();
        $rejected = RegistrationDocument::where('is_verified', false)
                                      ->whereNotNull('verification_notes')
                                      ->count();
        $pending = RegistrationDocument::where('is_verified', false)
                                     ->whereNull('verification_notes')
                                     ->count();

        return [
            'total' => $total,
            'verified' => $verified,
            'rejected' => $rejected,
            'pending' => $pending
        ];
    }

    /**
     * Format file size in human readable format
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
