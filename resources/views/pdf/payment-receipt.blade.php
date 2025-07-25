<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran - {{ $payment->order_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .invoice-container {
            width: 794px;
            height: 1123px;
            margin: 0 auto;
            background: white;
            position: relative;
        }
        
        /* Header Section - exact match to SVG */
        .header {
            position: absolute;
            left: 50px;
            top: 50px;
            width: 694px;
            height: 120px;
            background: #2563eb;
        }
        
        .header-title {
            position: absolute;
            left: 20px;
            top: 40px;
            color: white;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        
        .header-subtitle {
            position: absolute;
            left: 20px;
            top: 65px;
            color: white;
            font-size: 14px;
            margin: 0;
        }
        
        .header-logo {
            position: absolute;
            right: 94px;
            top: 10px;
            width: 100px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
        }
        
        /* Invoice Info Section */
        .invoice-title {
            position: absolute;
            left: 70px;
            top: 220px;
            color: #1f2937;
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        
        .invoice-number {
            position: absolute;
            left: 70px;
            top: 245px;
            color: #6b7280;
            font-size: 12px;
            margin: 0;
        }
        
        .invoice-date {
            position: absolute;
            left: 70px;
            top: 265px;
            color: #6b7280;
            font-size: 12px;
            margin: 0;
        }
        
        .invoice-status {
            position: absolute;
            left: 70px;
            top: 285px;
            color: #6b7280;
            font-size: 12px;
            margin: 0;
        }
        
        /* Customer Info */
        .customer-title {
            position: absolute;
            left: 400px;
            top: 220px;
            color: #1f2937;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        
        .customer-name {
            position: absolute;
            left: 400px;
            top: 245px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .customer-institution {
            position: absolute;
            left: 400px;
            top: 265px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .customer-email {
            position: absolute;
            left: 400px;
            top: 285px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .customer-phone {
            position: absolute;
            left: 400px;
            top: 305px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        /* Line separator */
        .separator {
            position: absolute;
            left: 70px;
            top: 340px;
            width: 654px;
            height: 1px;
            background: #e5e7eb;
        }
        
        /* Table Header */
        .table-header {
            position: absolute;
            left: 70px;
            top: 360px;
            width: 654px;
            height: 40px;
            background: #f3f4f6;
        }
        
        .table-header-kompetisi {
            position: absolute;
            left: 20px;
            top: 25px;
            color: #374151;
            font-size: 12px;
            font-weight: bold;
            margin: 0;
        }
        
        .table-header-kategori {
            position: absolute;
            left: 330px;
            top: 25px;
            color: #374151;
            font-size: 12px;
            font-weight: bold;
            margin: 0;
        }
        
        .table-header-harga {
            position: absolute;
            left: 480px;
            top: 25px;
            color: #374151;
            font-size: 12px;
            font-weight: bold;
            margin: 0;
        }
        
        .table-header-total {
            position: absolute;
            left: 580px;
            top: 25px;
            color: #374151;
            font-size: 12px;
            font-weight: bold;
            margin: 0;
        }
        
        /* Table Row */
        .table-row {
            position: absolute;
            left: 70px;
            top: 400px;
            width: 654px;
            height: 40px;
            background: white;
            border: 1px solid #e5e7eb;
        }
        
        .table-row-kompetisi {
            position: absolute;
            left: 20px;
            top: 25px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .table-row-kategori {
            position: absolute;
            left: 330px;
            top: 25px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .table-row-harga {
            position: absolute;
            left: 480px;
            top: 25px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .table-row-total {
            position: absolute;
            left: 580px;
            top: 25px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        /* Subtotal Section */
        .subtotal-line {
            position: absolute;
            left: 450px;
            top: 470px;
            width: 274px;
            height: 1px;
            background: #e5e7eb;
        }
        
        .subtotal-label {
            position: absolute;
            left: 550px;
            top: 495px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .subtotal-amount {
            position: absolute;
            left: 650px;
            top: 495px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .discount-label {
            position: absolute;
            left: 550px;
            top: 515px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .discount-amount {
            position: absolute;
            left: 650px;
            top: 515px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .total-section {
            position: absolute;
            left: 450px;
            top: 525px;
            width: 274px;
            height: 30px;
            background: #2563eb;
        }
        
        .total-label {
            position: absolute;
            left: 100px;
            top: 20px;
            color: white;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        
        .total-amount {
            position: absolute;
            left: 200px;
            top: 20px;
            color: white;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        
        /* Payment Method */
        .payment-method-title {
            position: absolute;
            left: 70px;
            top: 600px;
            color: #1f2937;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        
        .payment-method-value {
            position: absolute;
            left: 70px;
            top: 625px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        /* Instructions */
        .instructions-title {
            position: absolute;
            left: 70px;
            top: 680px;
            color: #1f2937;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        
        .instruction-1 {
            position: absolute;
            left: 70px;
            top: 705px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .instruction-2 {
            position: absolute;
            left: 70px;
            top: 725px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .instruction-3 {
            position: absolute;
            left: 70px;
            top: 745px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        .instruction-4 {
            position: absolute;
            left: 70px;
            top: 765px;
            color: #374151;
            font-size: 12px;
            margin: 0;
        }
        
        /* Footer */
        .footer {
            position: absolute;
            left: 50px;
            top: 950px;
            width: 694px;
            height: 80px;
            background: #f3f4f6;
        }
        
        .footer-line-1 {
            position: absolute;
            left: 20px;
            top: 30px;
            color: #6b7280;
            font-size: 12px;
            margin: 0;
        }
        
        .footer-line-2 {
            position: absolute;
            left: 20px;
            top: 50px;
            color: #6b7280;
            font-size: 12px;
            margin: 0;
        }
        
        .footer-line-3 {
            position: absolute;
            left: 20px;
            top: 70px;
            color: #6b7280;
            font-size: 12px;
            margin: 0;
        }
        
        @media print {
            body { margin: 0; padding: 0; }
            .invoice-container { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <h1 class="header-title">UNAS FEST 2025</h1>
            <p class="header-subtitle">Invoice Pembayaran Kompetisi</p>
            <div class="header-logo">LOGO</div>
        </div>
        
        <!-- Invoice Info -->
        <h2 class="invoice-title">INVOICE</h2>
        <p class="invoice-number">Invoice No: {{ $payment->order_id }}</p>
        <p class="invoice-date">Tanggal: {{ $payment->created_at->format('d M Y') }}</p>
        <p class="invoice-status">Status: {{ $payment->status_label }}</p>
        
        <!-- Customer Info -->
        <h3 class="customer-title">Peserta:</h3>
        <p class="customer-name">{{ $registration->user->name }}</p>
        <p class="customer-institution">{{ $registration->user->institution ?? 'Tidak diisi' }}</p>
        <p class="customer-email">{{ $registration->user->email }}</p>
        <p class="customer-phone">{{ $registration->phone ?: $registration->user->phone }}</p>
        
        <!-- Line separator -->
        <div class="separator"></div>
        
        <!-- Table Header -->
        <div class="table-header">
            <p class="table-header-kompetisi">Kompetisi</p>
            <p class="table-header-kategori">Kategori</p>
            <p class="table-header-harga">Harga</p>
            <p class="table-header-total">Total</p>
        </div>
        
        <!-- Table Row -->
        <div class="table-row">
            <p class="table-row-kompetisi">{{ $registration->competition->name }}</p>
            <p class="table-row-kategori">{{ $registration->competition->category }}</p>
            <p class="table-row-harga">Rp {{ number_format($registration->original_price ?? $registration->amount, 0, ',', '.') }}</p>
            <p class="table-row-total">Rp {{ number_format($registration->amount, 0, ',', '.') }}</p>
        </div>
        
        <!-- Subtotal Section -->
        <div class="subtotal-line"></div>
        <p class="subtotal-label">Subtotal:</p>
        <p class="subtotal-amount">Rp {{ number_format($registration->original_price ?? $registration->amount, 0, ',', '.') }}</p>
        <p class="discount-label">Discount:</p>
        <p class="discount-amount">-Rp {{ number_format(($registration->original_price ?? $registration->amount) - $registration->amount, 0, ',', '.') }}</p>
        
        <div class="total-section">
            <p class="total-label">TOTAL:</p>
            <p class="total-amount">Rp {{ number_format($registration->amount, 0, ',', '.') }}</p>
        </div>
        
        <!-- Payment Method -->
        <h3 class="payment-method-title">Metode Pembayaran:</h3>
        <p class="payment-method-value">{{ $payment->payment_method_label ?? 'Midtrans Payment Gateway' }}</p>
        
        <!-- Instructions -->
        <h3 class="instructions-title">Instruksi:</h3>
        <p class="instruction-1">1. Simpan invoice ini sebagai bukti pembayaran</p>
        <p class="instruction-2">2. Hubungi Contact Person di WhatsApp: {{ $registration->competition->contact_person_whatsapp ?? '081234567890' }}</p>
        <p class="instruction-3">3. Kirimkan screenshot invoice dan bukti pembayaran</p>
        <p class="instruction-4">4. Tunggu konfirmasi dari panitia</p>
        
        <!-- Footer -->
        <div class="footer">
            <p class="footer-line-1">UNAS Fest 2025 - Festival Kompetisi Universitas Nasional</p>
            <p class="footer-line-2">Website: https://uf25.tams.my.id | Email: info@unasfest.com</p>
            <p class="footer-line-3">WhatsApp: {{ $registration->competition->contact_person_whatsapp ?? '081234567890' }} | Instagram: @unasfest</p>
        </div>
    </div>
</body>
</html>