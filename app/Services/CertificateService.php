<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Competition;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Service for generating PDF certificates
 * 
 * Handles winner certificates, participation certificates,
 * and bulk certificate generation
 */
class CertificateService
{
    /**
     * Generate winner certificate
     *
     * @param Registration $registration
     * @param int $rank Position/rank (1 = Winner, 2 = Runner-up, 3 = Third place)
     * @param bool $download Whether to download or return PDF
     * @return mixed
     */
    public function generateWinnerCertificate(Registration $registration, int $rank, bool $download = true)
    {
        try {
            $competition = $registration->competition;
            $user = $registration->user;
            
            // Determine award title based on rank
            $awardTitle = $this->getAwardTitle($rank);
            
            $data = [
                'participant_name' => $user->name,
                'team_name' => $registration->team_name,
                'competition_name' => $competition->name,
                'competition_type' => $competition->type,
                'award_title' => $awardTitle,
                'rank' => $rank,
                'date' => Carbon::now()->format('d F Y'),
                'certificate_number' => $this->generateCertificateNumber($registration, 'WINNER'),
                'is_team' => $competition->is_team_competition,
            ];
            
            $pdf = Pdf::loadView('certificates.winner', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('margin-top', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0)
                ->setOption('margin-right', 0);
            
            $filename = $this->generateFilename($registration, 'winner');
            
            if ($download) {
                return $pdf->download($filename);
            }
            
            return $pdf;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate winner certificate', [
                'registration_id' => $registration->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Generate participation certificate
     *
     * @param Registration $registration
     * @param bool $download Whether to download or return PDF
     * @return mixed
     */
    public function generateParticipationCertificate(Registration $registration, bool $download = true)
    {
        try {
            $competition = $registration->competition;
            $user = $registration->user;
            
            $data = [
                'participant_name' => $user->name,
                'team_name' => $registration->team_name,
                'competition_name' => $competition->name,
                'competition_type' => $competition->type,
                'date' => Carbon::now()->format('d F Y'),
                'certificate_number' => $this->generateCertificateNumber($registration, 'PARTICIPATION'),
                'is_team' => $competition->is_team_competition,
            ];
            
            $pdf = Pdf::loadView('certificates.participation', $data)
                ->setPaper('a4', 'landscape')
                ->setOption('margin-top', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0)
                ->setOption('margin-right', 0);
            
            $filename = $this->generateFilename($registration, 'participation');
            
            if ($download) {
                return $pdf->download($filename);
            }
            
            return $pdf;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate participation certificate', [
                'registration_id' => $registration->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Generate bulk certificates for a competition
     *
     * @param Competition $competition
     * @param string $type 'winner' or 'participation'
     * @param array $registrationIds Optional specific registration IDs
     * @return string Path to ZIP file
     */
    public function generateBulkCertificates(Competition $competition, string $type = 'participation', array $registrationIds = []): string
    {
        try {
            $query = $competition->registrations()->where('status', 'confirmed');
            
            if (!empty($registrationIds)) {
                $query->whereIn('id', $registrationIds);
            }
            
            $registrations = $query->get();
            
            if ($registrations->isEmpty()) {
                throw new \Exception('No registrations found for certificate generation.');
            }
            
            // Create temporary directory for PDFs
            $tempDir = storage_path('app/temp/certificates/' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            // Generate individual certificates
            foreach ($registrations as $registration) {
                if ($type === 'winner') {
                    // For winners, you need to specify rank - default to 1
                    $pdf = $this->generateWinnerCertificate($registration, 1, false);
                } else {
                    $pdf = $this->generateParticipationCertificate($registration, false);
                }
                
                $filename = $this->generateFilename($registration, $type);
                $pdf->save($tempDir . '/' . $filename);
            }
            
            // Create ZIP file
            $zipFilename = 'certificates_' . $competition->slug . '_' . $type . '_' . date('YmdHis') . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFilename);
            
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                $files = glob($tempDir . '/*.pdf');
                foreach ($files as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();
            }
            
            // Clean up temporary directory
            array_map('unlink', glob($tempDir . '/*.pdf'));
            rmdir($tempDir);
            
            Log::info('Generated bulk certificates', [
                'competition_id' => $competition->id,
                'type' => $type,
                'count' => $registrations->count(),
                'zip_path' => $zipPath,
            ]);
            
            return $zipPath;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate bulk certificates', [
                'competition_id' => $competition->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Get award title based on rank
     *
     * @param int $rank
     * @return string
     */
    protected function getAwardTitle(int $rank): string
    {
        return match($rank) {
            1 => 'JUARA 1 / FIRST PLACE',
            2 => 'JUARA 2 / SECOND PLACE',
            3 => 'JUARA 3 / THIRD PLACE',
            default => 'FINALIS / FINALIST',
        };
    }
    
    /**
     * Generate certificate number
     *
     * @param Registration $registration
     * @param string $type
     * @return string
     */
    protected function generateCertificateNumber(Registration $registration, string $type): string
    {
        $competition = $registration->competition;
        $year = Carbon::now()->year;
        
        // Format: CERT/TYPE/COMP-TYPE/REG-ID/YEAR
        // Example: CERT/WINNER/SPC/12345/2025
        return sprintf(
            'CERT/%s/%s/%05d/%d',
            $type,
            $competition->type,
            $registration->id,
            $year
        );
    }
    
    /**
     * Generate filename for certificate
     *
     * @param Registration $registration
     * @param string $type
     * @return string
     */
    protected function generateFilename(Registration $registration, string $type): string
    {
        $competition = $registration->competition;
        $user = $registration->user;
        
        // Sanitize name for filename
        $name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $user->name);
        
        return sprintf(
            'Certificate_%s_%s_%s_%s.pdf',
            $type,
            $competition->type,
            $name,
            $registration->id
        );
    }
    
    /**
     * Save certificate to storage
     *
     * @param Registration $registration
     * @param string $type
     * @param int|null $rank
     * @return string Path to saved certificate
     */
    public function saveCertificate(Registration $registration, string $type, ?int $rank = null): string
    {
        try {
            if ($type === 'winner' && $rank) {
                $pdf = $this->generateWinnerCertificate($registration, $rank, false);
            } else {
                $pdf = $this->generateParticipationCertificate($registration, false);
            }
            
            $filename = $this->generateFilename($registration, $type);
            $path = 'certificates/' . $registration->competition->slug . '/' . $filename;
            
            Storage::disk('public')->put($path, $pdf->output());
            
            Log::info('Saved certificate to storage', [
                'registration_id' => $registration->id,
                'type' => $type,
                'path' => $path,
            ]);
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error('Failed to save certificate', [
                'registration_id' => $registration->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }
}

