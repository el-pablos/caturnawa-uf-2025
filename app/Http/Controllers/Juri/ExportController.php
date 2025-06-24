<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Score;
use App\Models\Submission;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

/**
 * Controller untuk export dan laporan juri
 */
class ExportController extends Controller
{
    /**
     * Export penilaian dalam format CSV
     */
    public function exportScores(Request $request)
    {
        $jury = Auth::user();
        
        $query = Score::with(['competition', 'registration.user'])
            ->where('jury_id', $jury->id);
            
        // Filter by competition if specified
        if ($request->filled('competition_id')) {
            $query->where('competition_id', $request->competition_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'final') {
                $query->where('is_final', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_final', false);
            }
        }
        
        $scores = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'penilaian_juri_' . $jury->name . '_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($scores) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'No',
                'Kompetisi',
                'Peserta',
                'Email',
                'Institusi',
                'Total Skor',
                'Status',
                'Komentar',
                'Tanggal Penilaian',
                'Tanggal Submit'
            ]);
            
            // Data
            foreach ($scores as $index => $score) {
                fputcsv($file, [
                    $index + 1,
                    $score->competition->name,
                    $score->registration->user->name,
                    $score->registration->user->email,
                    $score->registration->institution ?: '-',
                    number_format($score->total_score, 2),
                    $score->is_final ? 'Final' : 'Draft',
                    $score->comments ?: '-',
                    $score->created_at->format('d/m/Y H:i'),
                    $score->submitted_at ? $score->submitted_at->format('d/m/Y H:i') : '-'
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Export laporan detail penilaian
     */
    public function exportDetailedReport(Request $request)
    {
        $jury = Auth::user();
        
        $query = Score::with(['competition', 'registration.user'])
            ->where('jury_id', $jury->id)
            ->where('is_final', true);
            
        if ($request->filled('competition_id')) {
            $query->where('competition_id', $request->competition_id);
        }
        
        $scores = $query->orderBy('total_score', 'desc')->get();
        
        $filename = 'laporan_detail_penilaian_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($scores) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            $headerRow = [
                'Ranking',
                'Kompetisi',
                'Peserta',
                'Email',
                'Institusi',
                'Total Skor'
            ];
            
            // Add criteria headers
            if ($scores->isNotEmpty() && $scores->first()->criteria_scores) {
                $criteria = Score::getDefaultCriteria();
                foreach ($criteria as $key => $name) {
                    $headerRow[] = $name;
                }
            }
            
            $headerRow[] = 'Komentar';
            $headerRow[] = 'Tanggal Submit';
            
            fputcsv($file, $headerRow);
            
            // Data
            foreach ($scores as $index => $score) {
                $row = [
                    $index + 1,
                    $score->competition->name,
                    $score->registration->user->name,
                    $score->registration->user->email,
                    $score->registration->institution ?: '-',
                    number_format($score->total_score, 2)
                ];
                
                // Add criteria scores
                if ($score->criteria_scores) {
                    $criteria = Score::getDefaultCriteria();
                    foreach (array_keys($criteria) as $key) {
                        $row[] = $score->criteria_scores[$key] ?? 0;
                    }
                }
                
                $row[] = $score->comments ?: '-';
                $row[] = $score->submitted_at ? $score->submitted_at->format('d/m/Y H:i') : '-';
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Export statistik penilaian
     */
    public function exportStatistics(Request $request)
    {
        $jury = Auth::user();
        
        // Get competitions assigned to this jury
        $competitions = Competition::whereHas('juries', function($query) use ($jury) {
            $query->where('user_id', $jury->id);
        })->get();
        
        $filename = 'statistik_penilaian_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($competitions, $jury) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'Kompetisi',
                'Total Peserta',
                'Sudah Dinilai',
                'Belum Dinilai',
                'Persentase Selesai',
                'Rata-rata Skor',
                'Skor Tertinggi',
                'Skor Terendah'
            ]);
            
            // Data
            foreach ($competitions as $competition) {
                $totalParticipants = Registration::where('competition_id', $competition->id)
                    ->where('status', 'confirmed')
                    ->count();
                    
                $scoredCount = Score::where('competition_id', $competition->id)
                    ->where('jury_id', $jury->id)
                    ->where('is_final', true)
                    ->count();
                    
                $unscored = $totalParticipants - $scoredCount;
                $percentage = $totalParticipants > 0 ? ($scoredCount / $totalParticipants) * 100 : 0;
                
                $avgScore = Score::where('competition_id', $competition->id)
                    ->where('jury_id', $jury->id)
                    ->where('is_final', true)
                    ->avg('total_score') ?: 0;
                    
                $maxScore = Score::where('competition_id', $competition->id)
                    ->where('jury_id', $jury->id)
                    ->where('is_final', true)
                    ->max('total_score') ?: 0;
                    
                $minScore = Score::where('competition_id', $competition->id)
                    ->where('jury_id', $jury->id)
                    ->where('is_final', true)
                    ->min('total_score') ?: 0;
                
                fputcsv($file, [
                    $competition->name,
                    $totalParticipants,
                    $scoredCount,
                    $unscored,
                    number_format($percentage, 1) . '%',
                    number_format($avgScore, 2),
                    number_format($maxScore, 2),
                    number_format($minScore, 2)
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Generate laporan PDF (placeholder for future implementation)
     */
    public function generatePDFReport(Request $request)
    {
        // TODO: Implement PDF generation using DomPDF or similar
        return back()->with('info', 'Fitur export PDF akan segera tersedia.');
    }
}
