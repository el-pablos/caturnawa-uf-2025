<?php

namespace App\Services;

use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateInvoice(Registration $registration)
    {
        // Load SVG template
        $svgTemplate = Storage::get('kebutuhan-it/INVOICE/invoice-template.svg');
        
        if (!$svgTemplate) {
            throw new \Exception('Invoice template not found');
        }
        
        // Generate invoice number if not exists
        $invoiceNumber = $this->generateInvoiceNumber($registration);
        
        // Prepare data for template
        $data = $this->prepareInvoiceData($registration, $invoiceNumber);
        
        // Replace placeholders in SVG template
        $processedSvg = $this->replacePlaceholders($svgTemplate, $data);
        
        // Convert SVG to HTML for PDF generation
        $html = $this->svgToHtml($processedSvg);
        
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
        
        return [
            'INVOICE_NUMBER' => $invoiceNumber,
            'INVOICE_DATE' => now()->format('d F Y'),
            'DUE_DATE' => now()->addDays(1)->format('d F Y'),
            
            // Company Info
            'COMPANY_NAME' => 'UNAS FEST 2025',
            'COMPANY_ADDRESS' => 'Universitas Nasional Jakarta',
            'COMPANY_CITY' => 'Jakarta Selatan 12520',
            'COMPANY_PHONE' => '+62 21 7806700',
            'COMPANY_EMAIL' => 'info@unas.ac.id',
            
            // Participant Info
            'PARTICIPANT_NAME' => $user->name,
            'PARTICIPANT_EMAIL' => $user->email,
            'PARTICIPANT_PHONE' => $registration->phone ?? $user->phone,
            'PARTICIPANT_INSTITUTION' => $registration->institution ?? $user->institution,
            'PARTICIPANT_ADDRESS' => $user->address ?? 'Jakarta, Indonesia',
            
            // Registration Info
            'REGISTRATION_NUMBER' => $registration->registration_number,
            'COMPETITION_NAME' => $competition->name,
            'COMPETITION_CATEGORY' => ucfirst(str_replace('_', ' ', $competition->category)),
            'TEAM_NAME' => $registration->team_name ?? '-',
            'PARTICIPANT_TYPE' => ucfirst(str_replace('_', ' ', $registration->participant_category)),
            
            // Payment Info
            'ORIGINAL_PRICE' => 'Rp ' . number_format($registration->original_price, 0, ',', '.'),
            'DISCOUNT_AMOUNT' => 'Rp ' . number_format($registration->original_price - $registration->amount, 0, ',', '.'),
            'FINAL_AMOUNT' => 'Rp ' . number_format($registration->amount, 0, ',', '.'),
            'PAYMENT_STATUS' => 'PAID',
            'PAYMENT_DATE' => $registration->confirmed_at ? $registration->confirmed_at->format('d F Y H:i') : now()->format('d F Y H:i'),
            'PAYMENT_METHOD' => 'Online Payment',
            
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
    
    private function svgToHtml($svgContent)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Invoice</title>
            <style>
                body {
                    margin: 0;
                    padding: 20px;
                    font-family: Arial, sans-serif;
                    background: white;
                }
                
                .invoice-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                    box-shadow: 0 0 20px rgba(0,0,0,0.1);
                }
                
                svg {
                    width: 100%;
                    height: auto;
                }
                
                @media print {
                    body {
                        padding: 0;
                    }
                    
                    .invoice-container {
                        box-shadow: none;
                        max-width: none;
                    }
                }
                
                /* Custom styles for better PDF rendering */
                text {
                    font-family: Arial, sans-serif;
                }
                
                .header-text {
                    font-weight: bold;
                    font-size: 24px;
                }
                
                .company-name {
                    font-weight: bold;
                    font-size: 20px;
                    fill: #2c3e50;
                }
                
                .invoice-number {
                    font-weight: bold;
                    font-size: 16px;
                    fill: #e74c3c;
                }
                
                .amount-text {
                    font-weight: bold;
                    font-size: 18px;
                    fill: #27ae60;
                }
                
                .label-text {
                    font-weight: bold;
                    fill: #34495e;
                }
                
                .value-text {
                    fill: #2c3e50;
                }
                
                .notes-text {
                    font-size: 12px;
                    fill: #7f8c8d;
                }
                
                .footer-text {
                    font-size: 10px;
                    fill: #95a5a6;
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
}