<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * Controller for managing certificates
 */
class CertificateController extends Controller
{
    protected CertificateService $certificateService;
    
    public function __construct(CertificateService $certificateService)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->certificateService = $certificateService;
    }
    
    /**
     * Display certificate management page
     */
    public function index(Request $request)
    {
        $competitions = Competition::orderBy('start_date', 'desc')->get();
        
        $selectedCompetition = null;
        $registrations = collect();
        
        if ($request->has('competition_id')) {
            $selectedCompetition = Competition::findOrFail($request->competition_id);
            
            $registrations = $selectedCompetition->registrations()
                ->with('user')
                ->where('status', 'confirmed')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('admin.certificates.index', compact(
            'competitions',
            'selectedCompetition',
            'registrations'
        ));
    }
    
    /**
     * Generate winner certificate
     */
    public function generateWinner(Request $request, Registration $registration)
    {
        $request->validate([
            'rank' => 'required|integer|min:1|max:10',
        ]);
        
        try {
            return $this->certificateService->generateWinnerCertificate(
                $registration,
                $request->rank
            );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to generate certificate: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate participation certificate
     */
    public function generateParticipation(Registration $registration)
    {
        try {
            return $this->certificateService->generateParticipationCertificate($registration);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to generate certificate: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate bulk certificates
     */
    public function generateBulk(Request $request)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'type' => 'required|in:winner,participation',
            'registration_ids' => 'nullable|array',
            'registration_ids.*' => 'exists:registrations,id',
        ]);
        
        try {
            $competition = Competition::findOrFail($request->competition_id);
            
            $zipPath = $this->certificateService->generateBulkCertificates(
                $competition,
                $request->type,
                $request->registration_ids ?? []
            );
            
            return Response::download($zipPath, basename($zipPath))->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to generate bulk certificates: ' . $e->getMessage());
        }
    }
    
    /**
     * Preview certificate
     */
    public function preview(Request $request, Registration $registration)
    {
        $request->validate([
            'type' => 'required|in:winner,participation',
            'rank' => 'nullable|integer|min:1|max:10',
        ]);
        
        try {
            if ($request->type === 'winner') {
                $rank = $request->rank ?? 1;
                $pdf = $this->certificateService->generateWinnerCertificate(
                    $registration,
                    $rank,
                    false
                );
            } else {
                $pdf = $this->certificateService->generateParticipationCertificate(
                    $registration,
                    false
                );
            }
            
            return $pdf->stream();
            
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to preview certificate: ' . $e->getMessage());
        }
    }
}

