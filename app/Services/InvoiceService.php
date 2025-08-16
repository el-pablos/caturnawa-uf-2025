<?php

namespace App\Services;

use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateInvoice(Registration $registration)
    {
        // Use the new custom template with wider header
        $templatePath = base_path('kebutuhan-it/INVOICE/invoice-template-new.svg');

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
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Invoice - ' . $data['invoice_number'] . '</title>
            <style>
                @page {
                    margin: 0;
                    size: A4;
                }

                body {
                    margin: 0;
                    padding: 20px;
                    font-family: "DejaVu Sans", Arial, sans-serif;
                    background: white;
                    color: #333;
                    line-height: 1.4;
                }

                .invoice-container {
                    width: 100%;
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                }

                .header {
                    background: #154C8C;
                    color: white;
                    padding: 30px;
                    margin-bottom: 30px;
                    border-radius: 8px;
                }

                .header h1 {
                    margin: 0 0 10px 0;
                    font-size: 36px;
                    font-weight: bold;
                }

                .header .subtitle {
                    margin: 0;
                    font-size: 16px;
                    opacity: 0.9;
                }

                .header .tagline {
                    margin: 5px 0 0 0;
                    font-size: 14px;
                    opacity: 0.8;
                }

                .invoice-details {
                    display: table;
                    width: 100%;
                    margin-bottom: 30px;
                }

                .invoice-details .left,
                .invoice-details .right {
                    display: table-cell;
                    width: 50%;
                    vertical-align: top;
                    padding: 0 15px;
                }

                .detail-group {
                    margin-bottom: 20px;
                }

                .detail-label {
                    font-size: 10px;
                    color: #6B7280;
                    font-weight: bold;
                    text-transform: uppercase;
                    margin-bottom: 5px;
                }

                .detail-value {
                    font-size: 12px;
                    color: #154C8C;
                    font-weight: bold;
                }

                .section-title {
                    font-size: 18px;
                    color: #154C8C;
                    font-weight: bold;
                    margin: 30px 0 15px 0;
                    border-bottom: 2px solid #154C8C;
                    padding-bottom: 5px;
                }

                .info-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }

                .info-table td {
                    padding: 8px 12px;
                    border: 1px solid #E5E7EB;
                    font-size: 12px;
                }

                .info-table .label {
                    background: #F9FAFB;
                    font-weight: bold;
                    color: #374151;
                    width: 30%;
                }

                .info-table .value {
                    color: #154C8C;
                    font-weight: 600;
                }

                .payment-summary {
                    background: #F8F9FA;
                    padding: 20px;
                    border-radius: 8px;
                    margin: 20px 0;
                    border-left: 4px solid #154C8C;
                }

                .payment-row {
                    display: table;
                    width: 100%;
                    margin-bottom: 10px;
                }

                .payment-row .label,
                .payment-row .amount {
                    display: table-cell;
                    padding: 5px 0;
                }

                .payment-row .label {
                    font-weight: 600;
                    color: #374151;
                }

                .payment-row .amount {
                    text-align: right;
                    font-weight: bold;
                    color: #154C8C;
                }

                .total-row {
                    border-top: 2px solid #154C8C;
                    padding-top: 10px;
                    margin-top: 10px;
                }

                .total-row .amount {
                    font-size: 16px;
                    color: #059669;
                }

                .status-badge {
                    display: inline-block;
                    padding: 6px 12px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: bold;
                    text-transform: uppercase;
                    background: #10B981;
                    color: white;
                }

                .notes {
                    background: #FEF3C7;
                    border: 1px solid #F59E0B;
                    border-radius: 8px;
                    padding: 15px;
                    margin: 20px 0;
                }

                .notes h4 {
                    margin: 0 0 10px 0;
                    color: #92400E;
                    font-size: 14px;
                }

                .notes p {
                    margin: 5px 0;
                    font-size: 12px;
                    color: #92400E;
                }

                .footer {
                    margin-top: 40px;
                    padding-top: 20px;
                    border-top: 1px solid #E5E7EB;
                    text-align: center;
                    font-size: 11px;
                    color: #6B7280;
                }

                .contact-info {
                    margin-top: 15px;
                }

                .contact-info p {
                    margin: 3px 0;
                }
            </style>
        </head>
        <body>
            <div class="invoice-container">
                <!-- Header -->
                <div class="header">
                    <h1>INVOICE</h1>
                    <p class="subtitle">Caturnawa UNAS FEST 2025</p>
                    <p class="tagline">Festival Kompetisi Nasional</p>
                </div>

                <!-- Invoice Details -->
                <div class="invoice-details">
                    <div class="left">
                        <div class="detail-group">
                            <div class="detail-label">Invoice Number</div>
                            <div class="detail-value">' . $data['invoice_number'] . '</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Status</div>
                            <div class="status-badge">' . strtoupper($data['status']) . '</div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="detail-group">
                            <div class="detail-label">Date</div>
                            <div class="detail-value">' . $data['date'] . '</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Payment Date</div>
                            <div class="detail-value">' . $data['payment_date'] . '</div>
                        </div>
                    </div>
                </div>

                <!-- Participant Information -->
                <h3 class="section-title">Participant Information</h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Name</td>
                        <td class="value">' . htmlspecialchars($data['participant_name']) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Institution</td>
                        <td class="value">' . htmlspecialchars($data['participant_institution']) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td class="value">' . htmlspecialchars($data['participant_email']) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Phone</td>
                        <td class="value">' . htmlspecialchars($data['participant_phone']) . '</td>
                    </tr>
                </table>

                <!-- Competition Information -->
                <h3 class="section-title">Competition Details</h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Competition</td>
                        <td class="value">' . htmlspecialchars($data['competition_name']) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Category</td>
                        <td class="value">' . htmlspecialchars($data['competition_category']) . '</td>
                    </tr>
                    <tr>
                        <td class="label">Payment Method</td>
                        <td class="value">' . htmlspecialchars($data['payment_method']) . '</td>
                    </tr>
                </table>

                <!-- Payment Summary -->
                <h3 class="section-title">Payment Summary</h3>
                <div class="payment-summary">
                    <div class="payment-row">
                        <div class="label">Original Price</div>
                        <div class="amount">Rp ' . number_format($data['original_price'], 0, ',', '.') . '</div>
                    </div>
                    <div class="payment-row">
                        <div class="label">Discount</div>
                        <div class="amount">- Rp ' . number_format($data['discount_amount'], 0, ',', '.') . '</div>
                    </div>
                    <div class="payment-row total-row">
                        <div class="label">Total Amount</div>
                        <div class="amount">Rp ' . number_format($data['amount'], 0, ',', '.') . '</div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="notes">
                    <h4>Important Notes</h4>
                    <p>' . htmlspecialchars($data['NOTES'] ?? 'Terima kasih telah mendaftar di kompetisi ini. Simpan invoice ini sebagai bukti pembayaran yang sah.') . '</p>
                </div>

                <!-- Footer -->
                <div class="footer">
                    <p><strong>Caturnawa UNAS FEST 2025 - Festival Kompetisi Universitas Nasional</strong></p>
                    <div class="contact-info">
                        <p>Website: https://uf25.tams.my.id | Email: info@unasfest.com</p>
                        <p>WhatsApp: ' . htmlspecialchars($data['CONTACT_WHATSAPP'] ?? '+62 812-3456-7890') . '</p>
                        <p>Contact Person: ' . htmlspecialchars($data['CONTACT_PERSON'] ?? 'Tim Panitia') . '</p>
                    </div>
                </div>
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