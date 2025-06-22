<?php

namespace App\Exports;

use App\Models\Competition;

class CompetitionReportExport
{
    protected $competitions;

    public function __construct($competitions)
    {
        $this->competitions = $competitions;
    }

    /**
     * Generate Excel file for competitions
     */
    public function export()
    {
        $data = [];

        // Headers
        $data[] = [
            'Nama Kompetisi',
            'Kategori',
            'Status',
            'Biaya Pendaftaran',
            'Tanggal Mulai',
            'Tanggal Berakhir',
            'Batas Pendaftaran',
            'Total Pendaftar',
            'Pendaftar Terkonfirmasi',
            'Total Pendapatan',
            'Tanggal Dibuat',
        ];

        // Data rows
        foreach ($this->competitions as $competition) {
            $totalRevenue = $competition->registrations()
                ->whereHas('payment', function($query) {
                    $query->where('status', 'paid');
                })
                ->sum('amount');

            $data[] = [
                $competition->name ?? '-',
                ucfirst($competition->category ?? '-'),
                $competition->is_active ? 'Aktif' : 'Tidak Aktif',
                'Rp ' . number_format($competition->registration_fee ?? 0, 0, ',', '.'),
                $competition->start_date ? $competition->start_date->format('d/m/Y') : '-',
                $competition->end_date ? $competition->end_date->format('d/m/Y') : '-',
                $competition->registration_deadline ? $competition->registration_deadline->format('d/m/Y H:i') : '-',
                $competition->registrations_count ?? 0,
                $competition->confirmed_registrations_count ?? 0,
                'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                $competition->created_at ? $competition->created_at->format('d/m/Y H:i') : '-',
            ];
        }

        return $data;
    }
}
