# Panduan Pengelolaan Kompetisi
## Sistem Manajemen Kompetisi UNAS Fest 2025

### Daftar Isi
1. [Pengenalan Sistem](#pengenalan-sistem)
2. [Hirarki Peran Pengguna](#hirarki-peran-pengguna)
3. [Mengelola Kompetisi](#mengelola-kompetisi)
4. [Manajemen Pendaftaran](#manajemen-pendaftaran)
5. [Sistem Pembayaran](#sistem-pembayaran)
6. [Sistem Penjurian](#sistem-penjurian)
7. [Laporan dan Analitik](#laporan-dan-analitik)
8. [Pemecahan Masalah](#pemecahan-masalah)

---

## Pengenalan Sistem

Sistem Manajemen Kompetisi UNAS Fest 2025 adalah platform komprehensif untuk mengelola berbagai jenis kompetisi akademik dan non-akademik. Sistem ini mendukung:

- **Event DCC (Data Challenge Competition)**: Kompetisi berbasis data dan teknologi
- **Event Debate**: Kompetisi debat dalam bahasa Indonesia dan Inggris
- **Event Scientific Paper**: Kompetisi karya tulis ilmiah

---

## Hirarki Peran Pengguna

### 1. Super Admin
**Akses Penuh Sistem**
- ✅ Mengelola semua aspek sistem
- ✅ Membuat dan menghapus pengguna
- ✅ Mengatur peran dan permission
- ✅ Akses ke semua fitur administratif
- ✅ Mengelola kategori kompetisi
- ✅ Konfigurasi sistem global

### 2. Admin
**Pengelolaan Operasional**
- ✅ Mengelola kompetisi dan pendaftaran
- ✅ Memverifikasi dan mengkonfirmasi pembayaran
- ✅ Mengelola peserta dan submissions
- ✅ Mengakses laporan dan statistik
- ✅ Mengelola pengaturan aplikasi
- ❌ Tidak dapat mengelola pengguna admin lain

### 3. Juri
**Penilaian dan Evaluasi**
- ✅ Mengakses kompetisi yang ditugaskan
- ✅ Menilai submissions peserta
- ✅ Memberikan feedback dan komentar
- ✅ Mengunduh laporan hasil penilaian
- ❌ Tidak dapat mengakses data administratif

### 4. Peserta
**Registrasi dan Partisipasi**
- ✅ Mendaftar kompetisi yang tersedia
- ✅ Melakukan pembayaran online
- ✅ Upload dokumen dan karya
- ✅ Melihat status pembayaran dan registrasi
- ✅ Mengunduh tiket dan sertifikat

---

## Mengelola Kompetisi

### Membuat Kompetisi Baru

1. **Akses Menu Admin**
   - Login sebagai Admin atau Super Admin
   - Navigasi ke `Admin → Kompetisi → Tambah Kompetisi`

2. **Mengisi Informasi Dasar**
   ```
   Nama Kompetisi: [Nama yang jelas dan deskriptif]
   Kategori: 
   - Event DCC (Data Challenge Competition)
   - Event Debate
   - Event Scientific Paper
   
   Deskripsi: [Penjelasan lengkap tentang kompetisi]
   ```

3. **Pengaturan Harga**
   ```
   Harga Pendaftaran: [Harga normal dalam Rupiah]
   Harga Early Bird: [Harga diskon untuk pendaftar awal]
   ```

4. **Jadwal Kompetisi**
   ```
   Mulai Pendaftaran: [Tanggal dan waktu dibuka]
   Selesai Pendaftaran: [Deadline pendaftaran]
   Mulai Kompetisi: [Tanggal pelaksanaan]
   Selesai Kompetisi: [Tanggal berakhir]
   ```

5. **Pengaturan Lanjutan**
   ```
   Jumlah Anggota Tim: [Min-Max anggota per tim]
   Batas Peserta: [Maksimal peserta yang dapat mendaftar]
   Link WhatsApp Group: [Untuk komunikasi peserta]
   ```

### Mengelola Status Kompetisi

- **Draft**: Kompetisi belum dipublikasikan
- **Active**: Kompetisi sedang berjalan dan dapat diakses peserta
- **Inactive**: Kompetisi dihentikan sementara
- **Completed**: Kompetisi telah selesai

### Mengatur Deskripsi Kompetisi

1. **Navigasi ke Deskripsi**
   - `Admin → Kompetisi → [Pilih Kompetisi] → Deskripsi`

2. **Jenis Deskripsi**
   - **Ringkasan**: Penjelasan singkat kompetisi
   - **Aturan**: Ketentuan dan regulasi
   - **Kriteria**: Kriteria penilaian
   - **Hadiah**: Informasi hadiah dan penghargaan
   - **Timeline**: Jadwal detail kompetisi

---

## Manajemen Pendaftaran

### Memantau Pendaftaran

1. **Dashboard Registrasi**
   - `Admin → Registrasi`
   - Lihat semua pendaftaran dengan status real-time

2. **Status Pendaftaran**
   - **Pending**: Menunggu pembayaran
   - **Paid**: Pembayaran berhasil, menunggu konfirmasi admin
   - **Confirmed**: Dikonfirmasi admin, peserta aktif
   - **Cancelled**: Dibatalkan oleh admin/peserta
   - **Expired**: Melewati batas waktu pembayaran

### Verifikasi dan Konfirmasi

1. **Verifikasi Dokumen**
   - Periksa kelengkapan dokumen peserta
   - Validasi informasi tim dan anggota
   - Cek foto profil dan logo institusi

2. **Konfirmasi Pembayaran**
   - Verifikasi status pembayaran dari Midtrans
   - Konfirmasi manual jika diperlukan
   - Generate QR code tiket untuk peserta

### Mengelola Data Peserta

1. **Edit Informasi Peserta**
   - Koreksi data yang salah
   - Update informasi kontak
   - Modifikasi komposisi tim

2. **Komunikasi dengan Peserta**
   - Kirim notifikasi melalui email
   - Tambahkan ke WhatsApp group
   - Berikan update status pendaftaran

---

## Sistem Pembayaran

### Integrasi Midtrans

Sistem menggunakan Midtrans sebagai payment gateway dengan dukungan:
- **Virtual Account**: BCA, BNI, BRI, Mandiri
- **E-Wallet**: GoPay, ShopeePay, OVO
- **Retail**: Indomaret, Alfamart
- **QRIS**: Pembayaran menggunakan QR code

### Alur Pembayaran

1. **Peserta Melakukan Pembayaran**
   - Pilih metode pembayaran
   - Sistem generate invoice otomatis
   - Redirect ke halaman pembayaran Midtrans

2. **Notifikasi Pembayaran**
   - Midtrans mengirim notifikasi ke sistem
   - Status pembayaran diupdate otomatis
   - Peserta menerima konfirmasi pembayaran

3. **Verifikasi Admin**
   - Admin memverifikasi pembayaran
   - Konfirmasi registrasi peserta
   - Generate QR code tiket

### Mengelola Pembayaran

1. **Dashboard Pembayaran**
   ```
   Admin → Pembayaran
   
   Statistik:
   - Total Pembayaran
   - Pembayaran Berhasil
   - Menunggu Konfirmasi
   - Pembayaran Gagal
   ```

2. **Aksi Pembayaran**
   - **Konfirmasi**: Setujui pembayaran dan aktifkan peserta
   - **Tolak**: Tolak pembayaran dengan alasan
   - **Refund**: Kembalikan pembayaran jika diperlukan

3. **Laporan Pembayaran**
   - Export laporan dalam format PDF/Excel
   - Filter berdasarkan periode dan status
   - Analisis pendapatan per kompetisi

---

## Sistem Penjurian

### Mengelola Juri

1. **Menambah Juri**
   - `Admin → Pengguna → Tambah Pengguna`
   - Pilih role "Juri"
   - Assign ke kompetisi tertentu

2. **Pengaturan Juri**
   - Tentukan kompetisi yang dapat dinilai
   - Atur bobot penilaian per juri
   - Konfigurasi kriteria penilaian

### Sistem Penilaian

1. **Kriteria Penilaian**
   - Buat kriteria penilaian per kompetisi
   - Tentukan bobot setiap kriteria
   - Atur skala penilaian (1-10, 1-100, dll)

2. **Proses Penilaian**
   - Juri login ke sistem
   - Akses kompetisi yang ditugaskan
   - Nilai submissions berdasarkan kriteria
   - Berikan feedback dan komentar

### Mengelola Submissions

1. **Monitoring Submissions**
   - `Admin → Submissions`
   - Lihat semua karya yang disubmit
   - Monitor progress penilaian

2. **Status Submissions**
   - **Draft**: Belum disubmit final
   - **Submitted**: Sudah disubmit, menunggu penilaian
   - **Under Review**: Sedang dinilai juri
   - **Scored**: Sudah dinilai, menunggu hasil final
   - **Completed**: Penilaian selesai

---

## Laporan dan Analitik

### Dashboard Analitik

1. **Statistik Utama**
   - Total kompetisi aktif
   - Jumlah peserta terdaftar
   - Pendapatan total
   - Tingkat konversi pembayaran

2. **Grafik dan Visualisasi**
   - Trend pendaftaran per hari
   - Distribusi peserta per kompetisi
   - Analisis demografi peserta
   - Performa pembayaran

### Laporan Komprehensif

1. **Laporan Kompetisi**
   - Daftar lengkap peserta
   - Status pembayaran dan registrasi
   - Hasil penilaian dan ranking
   - Analisis kompetisi

2. **Laporan Keuangan**
   - Pendapatan per kompetisi
   - Breakdown metode pembayaran
   - Trend pendapatan bulanan
   - Analisis refund dan cancellation

3. **Laporan Juri**
   - Performa penilaian juri
   - Konsistensi penilaian
   - Feedback dan komentar
   - Statistik penilaian

---

## Pemecahan Masalah

### Masalah Umum dan Solusi

#### 1. Pembayaran Tidak Terverifikasi
**Gejala**: Peserta sudah bayar tapi status masih pending
**Solusi**:
1. Cek status di Midtrans dashboard
2. Verifikasi webhook configuration
3. Manual konfirmasi pembayaran jika diperlukan
4. Periksa log sistem untuk error

#### 2. Peserta Tidak Bisa Login
**Gejala**: Error 500 atau login gagal
**Solusi**:
1. Periksa status akun (aktif/non-aktif)
2. Reset password jika diperlukan
3. Cek log aplikasi untuk error
4. Verify email jika belum dikonfirmasi

#### 3. Upload File Gagal
**Gejala**: Error 413 atau file tidak terupload
**Solusi**:
1. Periksa ukuran file (max 64MB)
2. Pastikan format file didukung
3. Cek permission folder storage
4. Verify disk space server

#### 4. Juri Tidak Bisa Akses Kompetisi
**Gejala**: Juri tidak melihat kompetisi yang ditugaskan
**Solusi**:
1. Verifikasi assignment juri ke kompetisi
2. Cek status kompetisi (aktif/tidak aktif)
3. Periksa permission role juri
4. Refresh session juri

### Maintenance dan Monitoring

#### 1. Monitoring Harian
- Cek status server dan database
- Monitor error logs aplikasi
- Verifikasi backup data
- Periksa performa sistem

#### 2. Maintenance Rutin
- Update keamanan sistem
- Optimasi database
- Cleanup file sementara
- Backup data reguler

#### 3. Monitoring Pembayaran
- Verifikasi webhook Midtrans
- Cek saldo dan settlement
- Monitor transaction failure rate
- Analisis fraud detection

### Kontak dan Dukungan

- **Technical Support**: [admin@uf25.tams.my.id]
- **Payment Issues**: [payment@uf25.tams.my.id]
- **General Inquiry**: [info@uf25.tams.my.id]

---

## Kesimpulan

Panduan ini memberikan gambaran lengkap tentang pengelolaan kompetisi dalam sistem UNAS Fest 2025. Untuk informasi lebih lanjut atau bantuan teknis, silakan hubungi tim support.

**Versi Dokumen**: 1.0
**Terakhir Diperbarui**: {{ date('Y-m-d H:i:s') }}
**Dibuat oleh**: Tim Pengembang UNAS Fest 2025