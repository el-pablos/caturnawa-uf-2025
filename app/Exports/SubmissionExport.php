<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SubmissionExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $submissions;

    public function __construct($submissions)
    {
        $this->submissions = $submissions;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->submissions;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Peserta',
            'Email',
            'Kompetisi',
            'Kategori',
            'Judul Karya',
            'Deskripsi',
            'Status',
            'Tanggal Submit',
            'Tanggal Update'
        ];
    }

    /**
     * @param mixed $submission
     * @return array
     */
    public function map($submission): array
    {
        return [
            $submission->id,
            $submission->registration->user->name ?? 'N/A',
            $submission->registration->user->email ?? 'N/A',
            $submission->registration->competition->name ?? 'N/A',
            $submission->registration->competition->getCategoryDisplayName() ?? 'N/A',
            $submission->title ?? 'N/A',
            $submission->description ?? 'N/A',
            $this->getStatusLabel($submission->status),
            $submission->created_at ? $submission->created_at->format('d/m/Y H:i') : 'N/A',
            $submission->updated_at ? $submission->updated_at->format('d/m/Y H:i') : 'N/A'
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
        ];
    }

    /**
     * Get status label
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision' => 'Perlu Revisi'
        ];

        return $labels[$status] ?? ucfirst($status);
    }
}
