<?php

namespace App\Services;

use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateInvoice(Registration $registration)
    {
        // Use the custom template
        $templatePath = base_path('kebutuhan-it/INVOICE/invoice-template.svg');

        if (!file_exists($templatePath)) {
            throw new \Exception('Invoice template not found: ' . $templatePath);
        }

        // Generate invoice number if not exists
        $invoiceNumber = $this->generateInvoiceNumber($registration);

        // Prepare data for template
        $data = $this->prepareInvoiceData($registration, $invoiceNumber);

        // Create HTML with embedded SVG and dynamic content
        $html = $this->createInvoiceHtml($templatePath, $data);

        // Generate PDF
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }
    
    private function generateInvoiceNumber(Registration $registration)
    {
        return 'INV-' . $registration->competition->slug . '-' . 
               date('Y') . '-' . 
               str_pad($registration->id, 4, '0', STR_PAD_LEFT);
    }
    
    private function prepareInvoiceData(Registration $registration, $invoiceNumber)
    {
        $competition = $registration->competition;
        $user = $registration->user;
        
        $payment = $registration->payment;

        return [
            'invoice_number' => $invoiceNumber,
            'date' => $payment ? $payment->created_at->format('d F Y') : now()->format('d F Y'),
            'participant_name' => $user->name,
            'participant_email' => $user->email,
            'participant_phone' => $registration->phone ?? 'Tidak disebutkan',
            'participant_institution' => $registration->institution ?? 'Tidak disebutkan',
            'competition_name' => $competition->name,
            'competition_category' => ucfirst(str_replace('_', ' ', $competition->category)),
            'amount' => $registration->amount,
            'original_price' => $registration->original_price,
            'discount_amount' => $registration->original_price - $registration->amount,
            'status' => $payment ? 'LUNAS' : 'BELUM LUNAS',
            'payment_method' => $payment ? $payment->payment_method : 'Online Payment',
            'payment_date' => $registration->confirmed_at ? $registration->confirmed_at->format('d F Y H:i') : now()->format('d F Y H:i'),
            
            // Additional Info
            'NOTES' => 'Terima kasih telah mendaftar di ' . $competition->name . '. Simpan invoice ini sebagai bukti pembayaran yang sah.',
            'CONTACT_PERSON' => $competition->contact_person_name ?? 'Tim Panitia',
            'CONTACT_WHATSAPP' => $competition->contact_person_whatsapp ?? '+62 812-3456-7890',
            
            // QR Code (registration number as QR data)
            'QR_CODE_DATA' => $registration->registration_number,
            
            // Terms
            'TERMS_1' => '1. Invoice ini adalah bukti sah pembayaran pendaftaran',
            'TERMS_2' => '2. Simpan invoice ini untuk keperluan administrasi',
            'TERMS_3' => '3. Hubungi panitia jika ada pertanyaan terkait pembayaran',
            'TERMS_4' => '4. Pembayaran yang sudah dilakukan tidak dapat dikembalikan',
        ];
    }
    
    private function replacePlaceholders($svgTemplate, $data)
    {
        $processedSvg = $svgTemplate;
        
        foreach ($data as $placeholder => $value) {
            $processedSvg = str_replace('{{' . $placeholder . '}}', $value, $processedSvg);
        }
        
        return $processedSvg;
    }
    
    private function createInvoiceHtml($templatePath, $data)
    {
        // Read the SVG template
        $svgContent = file_get_contents($templatePath);

        // Replace placeholders in SVG with actual data
        $replacements = [
            '{{INVOICE_NUMBER}}' => $data['invoice_number'],
            '{{INVOICE_DATE}}' => $data['date'],
            '{{PAYMENT_STATUS}}' => strtoupper($data['status']),
            '{{PARTICIPANT_NAME}}' => $data['participant_name'],
            '{{PARTICIPANT_INSTITUTION}}' => $data['participant_institution'],
            '{{PARTICIPANT_EMAIL}}' => $data['participant_email'],
            '{{PARTICIPANT_PHONE}}' => $data['participant_phone'],
            '{{COMPETITION_NAME}}' => $data['competition_name'],
            '{{COMPETITION_CATEGORY}}' => $data['competition_category'],
            '{{ORIGINAL_PRICE}}' => 'Rp ' . number_format($data['original_price'], 0, ',', '.'),
            '{{FINAL_AMOUNT}}' => 'Rp ' . number_format($data['amount'], 0, ',', '.'),
            '{{DISCOUNT_AMOUNT}}' => 'Rp ' . number_format($data['discount_amount'], 0, ',', '.'),
            '{{PAYMENT_METHOD}}' => $data['payment_method'],
            '{{PAYMENT_DATE}}' => $data['payment_date'],
            '{{NOTES}}' => $data['NOTES'] ?? '',
            '{{CONTACT_PERSON}}' => $data['CONTACT_PERSON'] ?? '',
            '{{CONTACT_WHATSAPP}}' => $data['CONTACT_WHATSAPP'] ?? ''
        ];

        foreach ($replacements as $placeholder => $value) {
            $svgContent = str_replace($placeholder, htmlspecialchars($value), $svgContent);
        }

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Invoice - ' . $data['invoice_number'] . '</title>
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial, sans-serif;
                    background: white;
                }

                .invoice-container {
                    width: 100%;
                    max-width: 794px;
                    margin: 0 auto;
                    background: white;
                }

                svg {
                    width: 100%;
                    height: auto;
                    display: block;
                }

                @media print {
                    body {
                        padding: 0;
                        margin: 0;
                    }

                    .invoice-container {
                        max-width: none;
                        width: 100%;
                    }
                }
            </style>
        </head>
        <body>
            <div class="invoice-container">
                ' . $svgContent . '
            </div>
        </body>
        </html>';
    }
    
    public function downloadInvoice(Registration $registration)
    {
        $pdf = $this->generateInvoice($registration);
        $invoiceNumber = $this->generateInvoiceNumber($registration);
        $filename = 'Invoice-' . $invoiceNumber . '.pdf';
        
        return $pdf->download($filename);
    }
    
    public function streamInvoice(Registration $registration)
    {
        $pdf = $this->generateInvoice($registration);
        $invoiceNumber = $this->generateInvoiceNumber($registration);
        $filename = 'Invoice-' . $invoiceNumber . '.pdf';
        
        return $pdf->stream($filename);
    }

    /**
     * Get payment status text in Indonesian
     */
    private function getPaymentStatusText($payment): string
    {
        if (!$payment) {
            return 'BELUM DIBAYAR';
        }

        switch ($payment->transaction_status) {
            case 'settlement':
            case 'capture':
                return 'LUNAS';
            case 'pending':
                return 'MENUNGGU PEMBAYARAN';
            case 'expire':
                return 'KADALUARSA';
            case 'cancel':
                return 'DIBATALKAN';
            case 'deny':
                return 'DITOLAK';
            default:
                return 'TIDAK DIKETAHUI';
        }
    }

    /**
     * Get payment method text in Indonesian
     */
    private function getPaymentMethodText($payment): string
    {
        if (!$payment || !$payment->payment_type) {
            return 'Belum dipilih';
        }

        $methods = [
            'bank_transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'cstore' => 'Convenience Store',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'mandiri_va' => 'Mandiri Virtual Account',
            'permata_va' => 'Permata Virtual Account',
        ];

        return $methods[$payment->payment_type] ?? ucfirst(str_replace('_', ' ', $payment->payment_type));
    }

    /**
     * Get contact WhatsApp for competition
     */
    private function getContactWhatsApp($competition): string
    {
        // Default contact person WhatsApp
        $defaultWhatsApp = '+62812-3456-7890';

        // Competition-specific contacts
        $contacts = [
            'kdbi' => '+62812-1111-1111',
            'edc' => '+62812-2222-2222',
            'short-movie' => '+62812-3333-3333',
            'fotografi' => '+62812-4444-4444',
            'lkti' => '+62812-5555-5555',
        ];

        $slug = \Str::slug($competition->name);
        foreach ($contacts as $key => $whatsapp) {
            if (\Str::contains($slug, $key)) {
                return $whatsapp;
            }
        }

        return $defaultWhatsApp;
    }
}