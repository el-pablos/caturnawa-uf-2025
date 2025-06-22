<?php

namespace App\Exports;

use App\Models\Competition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CompetitionReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $competitions;

    public function __construct($competitions)
    {
        $this->competitions = $competitions;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->competitions;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
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
    }

    /**
     * @param mixed $competition
     * @return array
     */
    public function map($competition): array
    {
        $totalRevenue = $competition->registrations()
            ->whereHas('payment', function($query) {
                $query->where('status', 'paid');
            })
            ->sum('amount');

        return [
            $competition->name,
            ucfirst($competition->category),
            $competition->is_active ? 'Aktif' : 'Tidak Aktif',
            'Rp ' . number_format($competition->price, 0, ',', '.'),
            $competition->start_date->format('d/m/Y'),
            $competition->end_date->format('d/m/Y'),
            $competition->registration_deadline ? $competition->registration_deadline->format('d/m/Y H:i') : '-',
            $competition->registrations_count ?? 0,
            $competition->confirmed_registrations_count ?? 0,
            'Rp ' . number_format($totalRevenue, 0, ',', '.'),
            $competition->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
            
            // Style the header row
            'A1:K1' => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '28A745'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
