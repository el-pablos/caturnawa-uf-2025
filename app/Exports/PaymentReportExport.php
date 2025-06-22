<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->payments;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Order ID',
            'No. Registrasi',
            'Nama Peserta',
            'Email',
            'Kompetisi',
            'Jumlah Bayar',
            'Metode Pembayaran',
            'Status Transaksi',
            'Status Pembayaran',
            'Tanggal Transaksi',
            'Tanggal Bayar',
            'Bank/Provider',
            'VA Number',
        ];
    }

    /**
     * @param mixed $payment
     * @return array
     */
    public function map($payment): array
    {
        return [
            $payment->order_id,
            $payment->registration->registration_number,
            $payment->registration->user->name,
            $payment->registration->user->email,
            $payment->registration->competition->name,
            'Rp ' . number_format($payment->gross_amount, 0, ',', '.'),
            $payment->payment_type ?: '-',
            $payment->transaction_status ?: '-',
            ucfirst($payment->status),
            $payment->created_at->format('d/m/Y H:i'),
            $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-',
            $payment->bank ?: '-',
            $payment->va_number ?: '-',
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
            'A1:M1' => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFC107'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
