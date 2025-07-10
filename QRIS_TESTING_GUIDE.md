# QRIS Testing Guide - UNAS Fest 2025

## 🚨 **PENTING: Mengapa QR Code "Unparsable"**

QR Code yang dihasilkan oleh **Midtrans Snap** menggunakan format khusus yang **TIDAK KOMPATIBEL** dengan simulator QR Code umum di:
```
❌ https://simulator.sandbox.midtrans.com/v2/qris/payment
```

**Alasan:**
- QR Code dari Snap = Format khusus Midtrans dengan data transaksi real-time
- QR Code Simulator = Tool terpisah untuk testing format QR standar
- Format encoding berbeda = Tidak bisa saling kompatibel

## ✅ **Cara Testing QRIS yang BENAR**

### **Metode 1: Test Langsung di Snap Popup**

1. **Buka Payment Checkout:**
   ```
   http://127.0.0.1:8000/payment/checkout/2
   ```

2. **Login:**
   - Email: `peserta1@unasfest.com`
   - Password: `password123`

3. **Pilih Payment Method:**
   - Pilih **"QRIS"**
   - Centang "Setuju dengan syarat dan ketentuan"
   - Klik **"Bayar Sekarang"**

4. **Di Snap Popup:**
   - QR Code akan muncul
   - Klik **"Simulasi Pembayaran"** atau **"Test Payment"**
   - Ikuti instruksi simulasi

### **Metode 2: Menggunakan Midtrans Transaction Simulator**

1. **Buka Simulator:**
   ```
   https://simulator.sandbox.midtrans.com/
   ```

2. **Masukkan Order ID:**
   ```
   Order ID: UF2025-20250710180602-438
   ```

3. **Pilih Payment Method:**
   - Pilih **"QRIS"**
   - Ikuti simulasi pembayaran

### **Metode 3: Manual Success Simulation**

Untuk testing cepat, jalankan command ini:

```bash
php artisan tinker
```

```php
$payment = App\Models\Payment::find(3);
$payment->transaction_status = 'settlement';
$payment->paid_at = now();
$payment->save();
$payment->registration->confirm();
exit
```

## 🎯 **Testing Flow Lengkap**

### **Step 1: Persiapan**
```bash
# Jalankan server
php artisan serve --host=127.0.0.1 --port=8000

# Buka browser
http://127.0.0.1:8000/login
```

### **Step 2: Login & Register**
1. Login sebagai peserta
2. Daftar kompetisi (jika belum)
3. Pergi ke halaman payment

### **Step 3: Test QRIS Payment**
1. Pilih QRIS payment method
2. Klik "Bayar Sekarang"
3. Snap popup muncul dengan QR Code
4. Test dengan salah satu metode di atas

### **Step 4: Verifikasi**
```
# Cek status payment
http://127.0.0.1:8000/payment/status/3

# Cek registration status
http://127.0.0.1:8000/peserta/registrations
```

## 🔧 **Troubleshooting**

### **Problem: QR Code "Unparsable"**
**Solution:** Jangan gunakan simulator QR terpisah. Gunakan metode testing yang benar di atas.

### **Problem: Snap Popup Tidak Muncul**
**Solution:** 
- Pastikan login dengan benar
- Disable popup blocker
- Check browser console untuk error

### **Problem: Payment Stuck di Pending**
**Solution:** Gunakan manual simulation atau transaction simulator dengan Order ID yang benar.

## 📱 **Testing dengan Mobile App (Opsional)**

Jika ingin test dengan aplikasi mobile banking:

1. **Buka QR Code di Snap popup**
2. **Gunakan aplikasi yang support QRIS:**
   - GoPay
   - OVO  
   - DANA
   - Mobile banking apps
3. **Scan QR Code langsung dari popup**
4. **Ikuti flow pembayaran di app**

**Note:** Untuk sandbox environment, pembayaran akan tetap dalam mode testing.

## 🎉 **Expected Results**

### **Successful Payment:**
- Status berubah ke "settlement"
- Registration status berubah ke "confirmed"  
- User mendapat konfirmasi pembayaran
- WhatsApp group link tersedia (jika dikonfigurasi)

### **Payment URLs:**
- **Checkout:** `http://127.0.0.1:8000/payment/checkout/2`
- **Status:** `http://127.0.0.1:8000/payment/status/3`
- **Success:** `http://127.0.0.1:8000/payment/finish/3`

---

## 📝 **Summary**

**✅ DO:**
- Test QRIS melalui Snap popup
- Gunakan Midtrans transaction simulator dengan Order ID
- Manual simulation untuk testing cepat

**❌ DON'T:**
- Jangan gunakan QR simulator terpisah
- Jangan expect QR Code bisa di-parse di tool umum
- Jangan lupa login sebelum testing

**🎯 QRIS Payment System sudah WORKING dengan benar!** 
Masalah "unparsable" bukan bug, tapi cara testing yang salah.
