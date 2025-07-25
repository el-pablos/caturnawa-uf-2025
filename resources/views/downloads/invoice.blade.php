<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $payment->order_id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #374151;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        
        .invoice-container {
            width: 794px;
            min-height: 1123px;
            margin: 0 auto;
            background: white;
            position: relative;
        }
        
        /* Header Section */
        .header {
            background: #2563eb;
            height: 120px;
            margin: 50px 50px 0 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            color: white;
            position: relative;
        }
        
        .header-left h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .header-left h2 {
            font-size: 14px;
            margin: 0;
            font-weight: normal;
        }
        
        .header-logo {
            width: 100px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
        }
        
        /* Invoice Info Section */
        .invoice-info {
            padding: 50px;
            display: flex;
            justify-content: space-between;
        }
        
        .invoice-details h3 {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 15px 0;
        }
        
        .invoice-details p {
            font-size: 12px;
            color: #6b7280;
            margin: 5px 0;
        }
        
        .customer-info h4 {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 15px 0;
        }
        
        .customer-info p {
            font-size: 12px;
            color: #374151;
            margin: 5px 0;
        }
        
        /* Separator Line */
        .separator {
            height: 1px;
            background: #e5e7eb;
            margin: 0 70px;
        }
        
        /* Table Section */
        .table-container {
            margin: 20px 70px;
        }
        
        .table-header {
            background: #f3f4f6;
            height: 40px;
            display: flex;
            align-items: center;
            font-weight: bold;
            color: #374151;
            font-size: 12px;
        }
        
        .table-row {
            background: white;
            border: 1px solid #e5e7eb;
            height: 40px;
            display: flex;
            align-items: center;
            color: #374151;
            font-size: 12px;
        }
        
        .col-competition { width: 310px; padding-left: 20px; }
        .col-category { width: 150px; }
        .col-price { width: 100px; }
        .col-total { width: 74px; }
        
        /* Subtotal Section */
        .subtotal-container {
            margin: 30px 70px 0 450px;
        }
        
        .subtotal-line {
            height: 1px;
            background: #e5e7eb;
            margin-bottom: 20px;
        }
        
        .subtotal-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
            color: #374151;
        }
        
        .total-row {
            background: #2563eb;
            color: white;
            padding: 8px 15px;
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }
        
        /* Payment Method */
        .payment-method {
            margin: 40px 70px;
        }
        
        .payment-method h4 {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 10px 0;
        }
        
        .payment-method p {
            font-size: 12px;
            color: #374151;
            margin: 0;
        }
        
        /* Instructions */
        .instructions {
            margin: 40px 70px;
        }
        
        .instructions h4 {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 15px 0;
        }
        
        .instructions p {
            font-size: 12px;
            color: #374151;
            margin: 5px 0;
        }
        
        /* Footer */
        .footer {
            background: #f3f4f6;
            height: 80px;
            margin: 0 50px 50px 50px;
            padding: 20px;
            position: absolute;
            bottom: 50px;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .footer-info p {
            font-size: 12px;
            color: #6b7280;
            margin: 2px 0;
        }
        
        .footer-qr {
            width: 60px;
            height: 60px;
            background: #e5e7eb;
            border: 1px solid #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #6b7280;
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
            <div class="header-left">
                <h1>UNAS FEST 2025</h1>
                <h2>Invoice Pembayaran Kompetisi</h2>
            </div>
            <div class="header-logo">
                LOGO
            </div>
        </div>
        
        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="invoice-details">
                <h3>INVOICE</h3>
                <p>Invoice No: {{ $payment->order_id }}</p>
                <p>Tanggal: {{ $payment->created_at->format('d M Y') }}</p>
                <p>Status: {{ $payment->status === 'paid' ? 'LUNAS' : 'PENDING' }}</p>
            </div>
            
            <div class="customer-info">
                <h4>Peserta:</h4>
                <p>{{ $payment->registration->user->name }}</p>
                <p>{{ $payment->registration->user->institution ?? 'Tidak diisi' }}</p>
                <p>{{ $payment->registration->user->email }}</p>
                <p>{{ $payment->registration->phone ?: $payment->registration->user->phone }}</p>
            </div>
        </div>
        
        <!-- Separator Line -->
        <div class="separator"></div>
        
        <!-- Table -->
        <div class="table-container">
            <div class="table-header">
                <div class="col-competition">Kompetisi</div>
                <div class="col-category">Kategori</div>
                <div class="col-price">Harga</div>
                <div class="col-total">Total</div>
            </div>
            
            <div class="table-row">
                <div class="col-competition">{{ $payment->registration->competition->name }}</div>
                <div class="col-category">{{ $payment->registration->competition->category }}</div>
                <div class="col-price">Rp {{ number_format($payment->registration->original_price ?? $payment->amount, 0, ',', '.') }}</div>
                <div class="col-total">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
            </div>
        </div>
        
        <!-- Subtotal Section -->
        <div class="subtotal-container">
            <div class="subtotal-line"></div>
            
            <div class="subtotal-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($payment->registration->original_price ?? $payment->amount, 0, ',', '.') }}</span>
            </div>
            
            <div class="subtotal-row">
                <span>Discount:</span>
                <span>-Rp {{ number_format(($payment->registration->original_price ?? $payment->amount) - $payment->amount, 0, ',', '.') }}</span>
            </div>
            
            <div class="total-row">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <!-- Payment Method -->
        <div class="payment-method">
            <h4>Metode Pembayaran:</h4>
            <p>{{ $payment->payment_method_label ?? 'Midtrans Payment Gateway' }}</p>
        </div>
        
        <!-- Instructions -->
        <div class="instructions">
            <h4>Instruksi:</h4>
            <p>1. Simpan invoice ini sebagai bukti pembayaran</p>
            <p>2. Hubungi Contact Person di WhatsApp: {{ $payment->registration->competition->contact_person_whatsapp ?? '081234567890' }}</p>
            <p>3. Kirimkan screenshot invoice dan bukti pembayaran</p>
            <p>4. Tunggu konfirmasi dari panitia</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-info">
                <p>UNAS Fest 2025 - Festival Kompetisi Universitas Nasional</p>
                <p>Website: https://uf25.tams.my.id | Email: info@unasfest.com</p>
                <p>WhatsApp: {{ $payment->registration->competition->contact_person_whatsapp ?? '081234567890' }} | Instagram: @unasfest</p>
            </div>
            <div class="footer-qr">
                QR
            </div>
        </div>
    </div>
</body>
</html>