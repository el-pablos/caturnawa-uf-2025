<?php

namespace App\Exports;

use App\Models\Payment;

class PaymentReportExport
{
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    /**
     * Generate Excel file for payments
     */
    public function export()
    {
        $data = [];

        // Headers
        $data[] = [
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

        // Data rows
        foreach ($this->payments as $payment) {
            $data[] = [
                $payment->order_id ?? '-',
                $payment->registration->registration_number ?? '-',
                $payment->registration->user->name ?? '-',
                $payment->registration->user->email ?? '-',
                $payment->registration->competition->name ?? '-',
                'Rp ' . number_format($payment->amount ?? 0, 0, ',', '.'),
                $payment->payment_method ?? '-',
                $payment->transaction_status ?? '-',
                ucfirst($payment->status ?? '-'),
                $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : '-',
                $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-',
                $payment->bank ?? '-',
                $payment->va_number ?? '-',
            ];
        }

        return $data;
    }
}
