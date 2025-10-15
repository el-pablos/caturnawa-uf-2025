<?php

namespace App\Services;

use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Submission;
use App\Models\Score;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

/**
 * Service for exporting data to various formats
 * 
 * Handles Excel/CSV exports for registrations, payments,
 * submissions, scores, and comprehensive reports
 */
class ExportService
{
    /**
     * Export registrations to CSV
     *
     * @param Competition|null $competition
     * @param array $filters
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportRegistrations(?Competition $competition = null, array $filters = [])
    {
        $query = Registration::with(['user', 'competition', 'payment']);
        
        if ($competition) {
            $query->where('competition_id', $competition->id);
        }
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['date_from'])) {
            $query->whereDate('registered_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->whereDate('registered_at', '<=', $filters['date_to']);
        }
        
        $registrations = $query->orderBy('registered_at', 'desc')->get();
        
        $filename = 'registrations_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'No',
                'Registration Number',
                'Competition',
                'Participant Name',
                'Email',
                'Phone',
                'Institution',
                'Team Name',
                'Status',
                'Amount',
                'Payment Status',
                'Registered At',
                'Confirmed At',
            ]);
            
            // Data
            foreach ($registrations as $index => $registration) {
                fputcsv($file, [
                    $index + 1,
                    $registration->registration_number,
                    $registration->competition->name,
                    $registration->user->name,
                    $registration->user->email,
                    $registration->phone ?? $registration->user->phone,
                    $registration->institution ?? $registration->user->institution,
                    $registration->team_name ?? '-',
                    ucfirst($registration->status),
                    'Rp ' . number_format($registration->amount, 0, ',', '.'),
                    $registration->payment ? ucfirst($registration->payment->status) : 'Unpaid',
                    $registration->registered_at->format('d/m/Y H:i'),
                    $registration->confirmed_at ? $registration->confirmed_at->format('d/m/Y H:i') : '-',
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Export payments to CSV
     *
     * @param Competition|null $competition
     * @param array $filters
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportPayments(?Competition $competition = null, array $filters = [])
    {
        $query = Payment::with(['registration.user', 'registration.competition']);
        
        if ($competition) {
            $query->whereHas('registration', function ($q) use ($competition) {
                $q->where('competition_id', $competition->id);
            });
        }
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        $payments = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'payments_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'No',
                'Order ID',
                'Registration Number',
                'Competition',
                'Participant Name',
                'Email',
                'Amount',
                'Payment Method',
                'Status',
                'Created At',
                'Paid At',
                'Confirmed At',
            ]);
            
            // Data
            foreach ($payments as $index => $payment) {
                fputcsv($file, [
                    $index + 1,
                    $payment->order_id,
                    $payment->registration->registration_number,
                    $payment->registration->competition->name,
                    $payment->registration->user->name,
                    $payment->registration->user->email,
                    'Rp ' . number_format($payment->amount, 0, ',', '.'),
                    $payment->payment_method ?? '-',
                    ucfirst($payment->status),
                    $payment->created_at->format('d/m/Y H:i'),
                    $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-',
                    $payment->confirmed_at ? $payment->confirmed_at->format('d/m/Y H:i') : '-',
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Export submissions to CSV
     *
     * @param Competition|null $competition
     * @param array $filters
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportSubmissions(?Competition $competition = null, array $filters = [])
    {
        $query = Submission::with(['registration.user', 'registration.competition']);
        
        if ($competition) {
            $query->whereHas('registration', function ($q) use ($competition) {
                $q->where('competition_id', $competition->id);
            });
        }
        
        if (isset($filters['is_final'])) {
            $query->where('is_final', $filters['is_final']);
        }
        
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        $submissions = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'submissions_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function() use ($submissions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'No',
                'Competition',
                'Participant Name',
                'Team Name',
                'Title',
                'Description',
                'Status',
                'Submitted At',
                'Files Count',
            ]);
            
            // Data
            foreach ($submissions as $index => $submission) {
                fputcsv($file, [
                    $index + 1,
                    $submission->registration->competition->name,
                    $submission->registration->user->name,
                    $submission->registration->team_name ?? '-',
                    $submission->title,
                    $submission->description ?? '-',
                    $submission->is_final ? 'Final' : 'Draft',
                    $submission->created_at->format('d/m/Y H:i'),
                    is_array($submission->files) ? count($submission->files) : 0,
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Export scores to CSV
     *
     * @param Competition|null $competition
     * @param array $filters
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportScores(?Competition $competition = null, array $filters = [])
    {
        $query = Score::with(['registration.user', 'competition', 'jury']);
        
        if ($competition) {
            $query->where('competition_id', $competition->id);
        }
        
        if (isset($filters['is_final'])) {
            $query->where('is_final', $filters['is_final']);
        }
        
        $scores = $query->orderBy('total_score', 'desc')->get();
        
        $filename = 'scores_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function() use ($scores) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'No',
                'Competition',
                'Participant Name',
                'Team Name',
                'Judge Name',
                'Total Score',
                'Comments',
                'Status',
                'Submitted At',
            ]);
            
            // Data
            foreach ($scores as $index => $score) {
                fputcsv($file, [
                    $index + 1,
                    $score->competition->name,
                    $score->registration->user->name,
                    $score->registration->team_name ?? '-',
                    $score->jury->name,
                    number_format($score->total_score, 2),
                    $score->comments ?? '-',
                    $score->is_final ? 'Final' : 'Draft',
                    $score->submitted_at ? $score->submitted_at->format('d/m/Y H:i') : '-',
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}

