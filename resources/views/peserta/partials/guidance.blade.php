<!-- Welcome Guide -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #6f42c1 0%, #5a2d91 100%); box-shadow: 0 4px 12px rgba(111, 66, 193, 0.25);">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-3 fw-bold" style="color: #ffffff; text-shadow: 2px 2px 6px rgba(0,0,0,0.8); font-weight: 800;">
                            <i class="bi bi-star-fill me-2"></i>
                            Panduan Penggunaan Dashboard Caturnawa
                        </h4>
                        <p class="mb-3 lead" style="color: #ffffff; text-shadow: 1px 1px 4px rgba(0,0,0,0.7); font-weight: 500;">
                            Panduan lengkap alur website dari registrasi hingga upload karya untuk PIC/Team Leader.
                        </p>
                        <div class="d-flex gap-2">
                            <span class="badge p-2" style="background-color: rgba(255,255,255,0.95); color: #1f2937; font-weight: 600; text-shadow: none;">
                                <i class="bi bi-people me-1"></i>{{ App\Models\Registration::where('status', 'paid')->count() }}+ Peserta
                            </span>
                            <span class="badge p-2" style="background-color: rgba(255,255,255,0.95); color: #1f2937; font-weight: 600; text-shadow: none;">
                                <i class="bi bi-trophy me-1"></i>{{ App\Models\Competition::where('is_active', true)->count() }} Kompetisi
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-award display-1 opacity-75 text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightning-fill me-2 text-warning"></i>
                    Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('peserta.competitions.index') }}" class="btn w-100 h-100 d-flex flex-column justify-content-center text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none; min-height: 120px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(79, 70, 229, 0.35)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(79, 70, 229, 0.25)'">
                            <i class="bi bi-plus-circle fs-3 mb-2"></i>
                            <span>Daftar Kompetisi</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('peserta.registrations.index') }}" class="btn w-100 h-100 d-flex flex-column justify-content-center text-white" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none; min-height: 120px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(5, 150, 105, 0.35)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(5, 150, 105, 0.25)'">
                            <i class="bi bi-list-check fs-3 mb-2"></i>
                            <span>Lihat Pendaftaran</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('peserta.submissions.index') }}" class="btn w-100 h-100 d-flex flex-column justify-content-center text-white" style="background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%); border: none; min-height: 120px; box-shadow: 0 4px 12px rgba(8, 145, 178, 0.25); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(8, 145, 178, 0.35)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(8, 145, 178, 0.25)'">
                            <i class="bi bi-cloud-upload fs-3 mb-2"></i>
                            <span>Upload Karya</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('peserta.profile.edit') }}" class="btn w-100 h-100 d-flex flex-column justify-content-center text-white" style="background: linear-gradient(135deg, #7c2d12 0%, #6b1f0f 100%); border: none; min-height: 120px; box-shadow: 0 4px 12px rgba(124, 45, 18, 0.25); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(124, 45, 18, 0.35)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(124, 45, 18, 0.25)'">
                            <i class="bi bi-person-gear fs-3 mb-2"></i>
                            <span>Edit Profil</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Step-by-step Guide -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-map me-2 text-primary"></i>
                    Langkah-langkah Mengikuti Kompetisi
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);">
                                    <strong>1</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Registrasi Akun (PIC/Team Leader)</h6>
                                <p class="text-muted small mb-2">
                                    Sebagai perwakilan tim, buat akun baru dengan data umum. Anda akan menjadi PIC untuk tim Anda.
                                </p>
                                <span class="badge bg-primary">Langkah Pertama</span>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #059669 0%, #047857 100%); box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);">
                                    <strong>2</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Pilih dan Daftar Kompetisi</h6>
                                <p class="text-muted small mb-2">
                                    Pilih kompetisi yang ingin diikuti. Setiap lomba memiliki requirements berbeda sesuai kebutuhan-it.
                                </p>
                                <a href="{{ route('peserta.competitions.index') }}" class="btn btn-sm btn-outline-primary">
                                    Lihat Kompetisi
                                </a>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #d97706 0%, #b45309 100%); box-shadow: 0 2px 8px rgba(217, 119, 6, 0.3);">
                                    <strong>3</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Isi Data Sesuai Lomba</h6>
                                <p class="text-muted small mb-2">
                                    Setiap lomba memiliki requirements berbeda. Isi data tim, upload dokumen sesuai ketentuan masing-masing lomba.
                                </p>
                                <span class="badge bg-warning">Beda per Lomba</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%); box-shadow: 0 2px 8px rgba(8, 145, 178, 0.3);">
                                    <strong>4</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Lakukan Pembayaran</h6>
                                <p class="text-muted small mb-2">
                                    Bayar kapan pun selama belum timeout. Bisa ganti metode payment. Sekali bayar, selesai.
                                </p>
                                <span class="badge bg-info">Fleksibel</span>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #059669 0%, #047857 100%); box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);">
                                    <strong>5</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Konfirmasi ke Contact Person</h6>
                                <p class="text-muted small mb-2">
                                    Kirim invoice dan bukti pembayaran ke WhatsApp contact person untuk konfirmasi.
                                </p>
                                <span class="badge bg-success">Wajib</span>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);">
                                    <strong>6</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Upload Karya (Tab Upload Karya)</h6>
                                <p class="text-muted small mb-2">
                                    Setelah konfirmasi, buka tab "Upload Karya" di dashboard untuk upload karya sesuai ketentuan lomba.
                                </p>
                                <span class="badge bg-danger">Final Step</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaderboard Guide -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-trophy me-2 text-warning"></i>
                    Cara Melihat Nilai & Leaderboard
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Catatan:</strong> Nilai dan leaderboard akan tersedia setelah proses penilaian selesai oleh juri.
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);">
                                    <strong>1</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Tunggu Proses Penilaian</h6>
                                <p class="text-muted small mb-2">
                                    Setelah masa submit karya berakhir, juri akan melakukan proses penilaian. Proses ini membutuhkan waktu sesuai dengan kompleksitas kompetisi.
                                </p>
                                <span class="badge bg-secondary">Tahap Evaluasi</span>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #d97706 0%, #b45309 100%); box-shadow: 0 2px 8px rgba(217, 119, 6, 0.3);">
                                    <strong>2</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Akses Leaderboard</h6>
                                <p class="text-muted small mb-2">
                                    Untuk melihat ranking peserta, klik tombol "Lihat Leaderboard" atau akses melalui halaman publik website untuk melihat posisi Anda.
                                </p>
                                <a href="{{ route('leaderboard.index') }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-trophy me-1"></i>Lihat Leaderboard
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%); box-shadow: 0 2px 8px rgba(8, 145, 178, 0.3);">
                                    <strong>3</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Filter per Kompetisi</h6>
                                <p class="text-muted small mb-2">
                                    Pada halaman leaderboard, pilih kompetisi tertentu menggunakan dropdown filter untuk melihat ranking yang lebih spesifik.
                                </p>
                                <span class="badge bg-info">Multi Kategori</span>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);">
                                    <strong>4</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Pengumuman Pemenang</h6>
                                <p class="text-muted small mb-2">
                                    Pemenang resmi akan diumumkan melalui website, email, dan grup WhatsApp sesuai jadwal yang telah ditentukan.
                                </p>
                                <span class="badge bg-danger">Pengumuman Resmi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-graph-up-arrow fs-2 text-primary mb-2"></i>
                                <h6 class="card-title">Nilai Individual</h6>
                                <p class="card-text small text-muted">
                                    Lihat skor detail per kriteria penilaian untuk karya yang Anda submit.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-list-ol fs-2 text-warning mb-2"></i>
                                <h6 class="card-title">Ranking Sementara</h6>
                                <p class="card-text small text-muted">
                                    Pantau posisi Anda di leaderboard yang diupdate secara berkala.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-award fs-2 text-success mb-2"></i>
                                <h6 class="card-title">Hasil Final</h6>
                                <p class="card-text small text-muted">
                                    Hasil akhir dan pemenang akan diumumkan sesuai timeline kompetisi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Important Information -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Hal Penting yang Harus Diperhatikan
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Deadline:</strong> Upload karya sebelum batas waktu yang ditentukan
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Format File:</strong> Pastikan format sesuai dengan ketentuan kompetisi
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Originalitas:</strong> Karya harus asli dan bebas dari plagiarisme
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Komunikasi:</strong> Aktif di grup WhatsApp untuk mendapat update
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Backup:</strong> Simpan backup karya di tempat yang aman
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="bi bi-question-circle-fill me-2"></i>
                    Butuh Bantuan?
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-3">Jika mengalami kendala atau memiliki pertanyaan, jangan ragu untuk menghubungi kami:</p>
                
                <div class="d-grid gap-2">
                    <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-success">
                        <i class="bi bi-whatsapp me-2"></i>
                        WhatsApp Support
                    </a>
                    <a href="mailto:support@unasfest.com" class="btn btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>
                        Email Support
                    </a>
                    <a href="{{ route('public.faq') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-question-circle me-2"></i>
                        FAQ
                    </a>
                </div>

                <hr>
                <div class="text-center">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Jam Operasional: 08:00 - 22:00 WIB
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tips and Tricks -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2 text-warning"></i>
                    Tips & Trik untuk Sukses
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 border rounded">
                            <i class="bi bi-calendar-check fs-2 text-primary mb-2"></i>
                            <h6>Manajemen Waktu</h6>
                            <p class="small text-muted mb-0">
                                Buat timeline pengerjaan dan patuhi deadline untuk menghindari terburu-buru.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 border rounded">
                            <i class="bi bi-people fs-2 text-success mb-2"></i>
                            <h6>Komunikasi Tim</h6>
                            <p class="small text-muted mb-0">
                                Untuk kompetisi tim, pastikan koordinasi yang baik antar anggota tim.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 border rounded">
                            <i class="bi bi-bookmark-star fs-2 text-warning mb-2"></i>
                            <h6>Kualitas Karya</h6>
                            <p class="small text-muted mb-0">
                                Fokus pada kualitas daripada kuantitas. Perhatikan detail dan finishing.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
