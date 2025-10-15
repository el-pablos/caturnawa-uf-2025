<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->query) {
            return $this->query->with(['user', 'competition', 'payment'])->get();
        }
        
        return Registration::with(['user', 'competition', 'payment'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Registration Number',
            'Team Name',
            'User Name',
            'Email',
            'Competition',
            'Status',
            'Amount',
            'Payment Status',
            'Registered At',
        ];
    }

    /**
     * @param Registration $registration
     * @return array
     */
    public function map($registration): array
    {
        return [
            $registration->id,
            $registration->registration_number ?? '-',
            $registration->team_name ?? '-',
            $registration->user->name ?? '-',
            $registration->user->email ?? '-',
            $registration->competition->name ?? '-',
            $registration->status,
            $registration->amount ?? 0,
            $registration->payment->transaction_status ?? 'pending',
            $registration->created_at ? $registration->created_at->format('Y-m-d H:i:s') : '-',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}

