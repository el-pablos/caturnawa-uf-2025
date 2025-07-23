<?php

namespace App\Exports;

use App\Models\Registration;

class RegistrationReportExport
{
    protected $registrations;

    public function __construct($registrations)
    {
        $this->registrations = $registrations;
    }

    /**
     * Generate Excel file for registrations
     */
    public function export()
    {
        $data = [];

        // Headers
        $data[] = [
            'No',
            'Nama',
            'Tim/Peserta',
            'Universitas',
            'Sekolah',
            'Kompetisi',
            'Status Pembayaran',
        ];

        // Data rows
        foreach ($this->registrations as $index => $registration) {
            $isUniversity = false;
            $isSchool = false;
            $institution = $registration->institution ?? $registration->user->institution ?? '-';

            // Determine if it's university or school based on institution name
            $universityKeywords = ['universitas', 'institut', 'politeknik', 'akademi', 'sekolah tinggi', 'university', 'college'];
            $schoolKeywords = ['sma', 'smk', 'man', 'smp', 'mts', 'ma ', 'sekolah menengah'];

            foreach ($universityKeywords as $keyword) {
                if (stripos($institution, $keyword) !== false) {
                    $isUniversity = true;
                    break;
                }
            }

            if (!$isUniversity) {
                foreach ($schoolKeywords as $keyword) {
                    if (stripos($institution, $keyword) !== false) {
                        $isSchool = true;
                        break;
                    }
                }
            }

            $teamName = $registration->team_name ?? 'Individu';
            $paymentStatus = 'Belum Bayar';

            if ($registration->payment) {
                if (in_array($registration->payment->transaction_status, ['settlement', 'capture'])) {
                    $paymentStatus = 'Lunas';
                } elseif ($registration->payment->transaction_status == 'pending') {
                    $paymentStatus = 'Pending';
                } else {
                    $paymentStatus = 'Gagal';
                }
            }

            $data[] = [
                $index + 1,
                $registration->user->name ?? '-',
                $teamName,
                $isUniversity ? $institution : '-',
                $isSchool ? $institution : '-',
                $registration->competition->name ?? '-',
                $paymentStatus,
            ];
        }

        return $data;
    }
}
