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
            'No. Registrasi',
            'Nama Peserta',
            'Email',
            'Telepon',
            'Kompetisi',
            'Kategori Kompetisi',
            'Tim/Individu',
            'Institusi',
            'Status Registrasi',
            'Metode Pembayaran',
            'Status Pembayaran',
            'Jumlah Bayar',
            'Tanggal Daftar',
            'Tanggal Bayar',
            'Tanggal Konfirmasi',
        ];

        // Data rows
        foreach ($this->registrations as $registration) {
            $data[] = [
                $registration->registration_number ?? '-',
                $registration->user->name ?? '-',
                $registration->user->email ?? '-',
                $registration->phone ?? '-',
                $registration->competition->name ?? '-',
                ucfirst($registration->competition->category ?? '-'),
                $registration->team_name ?: 'Individu',
                $registration->institution ?? '-',
                ucfirst($registration->status ?? '-'),
                $registration->payment ? $registration->payment->payment_type : '-',
                $registration->payment ? ucfirst($registration->payment->status) : 'Belum Bayar',
                'Rp ' . number_format($registration->amount ?? 0, 0, ',', '.'),
                $registration->created_at ? $registration->created_at->format('d/m/Y H:i') : '-',
                $registration->payment && $registration->payment->paid_at ?
                    $registration->payment->paid_at->format('d/m/Y H:i') : '-',
                $registration->confirmed_at ? $registration->confirmed_at->format('d/m/Y H:i') : '-',
            ];
        }

        return $data;
    }
}
