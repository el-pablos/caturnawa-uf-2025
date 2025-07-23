<!-- Welcome Guide -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-3">
                            <i class="bi bi-star-fill me-2"></i>
                            Selamat Datang di UNAS Fest 2025!
                        </h4>
                        <p class="mb-3 lead">
                            Panduan lengkap untuk membantu Anda berpartisipasi dalam kompetisi dengan sukses.
                        </p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-white bg-opacity-20 p-2">
                                <i class="bi bi-people me-1"></i>{{ App\Models\Registration::where('status', 'paid')->count() }}+ Peserta
                            </span>
                            <span class="badge bg-white bg-opacity-20 p-2">
                                <i class="bi bi-trophy me-1"></i>{{ App\Models\Competition::where('is_active', true)->count() }} Kompetisi
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-award display-1 opacity-75"></i>
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
                        <a href="{{ route('peserta.competitions.index') }}" class="btn btn-primary w-100 h-100 d-flex flex-column justify-content-center">
                            <i class="bi bi-plus-circle fs-3 mb-2"></i>
                            <span>Daftar Kompetisi</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('peserta.registrations.index') }}" class="btn btn-success w-100 h-100 d-flex flex-column justify-content-center">
                            <i class="bi bi-list-check fs-3 mb-2"></i>
                            <span>Lihat Pendaftaran</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('peserta.submissions.index') }}" class="btn btn-info w-100 h-100 d-flex flex-column justify-content-center">
                            <i class="bi bi-cloud-upload fs-3 mb-2"></i>
                            <span>Upload Karya</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('peserta.profile.edit') }}" class="btn btn-secondary w-100 h-100 d-flex flex-column justify-content-center">
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
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <strong>1</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Daftar Kompetisi</h6>
                                <p class="text-muted small mb-2">
                                    Pilih kompetisi yang ingin diikuti, isi form pendaftaran dengan lengkap, dan upload dokumen yang diperlukan.
                                </p>
                                <a href="{{ route('peserta.competitions.index') }}" class="btn btn-sm btn-outline-primary">
                                    Lihat Kompetisi
                                </a>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <strong>2</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Lakukan Pembayaran</h6>
                                <p class="text-muted small mb-2">
                                    Bayar biaya pendaftaran melalui berbagai metode pembayaran yang tersedia. Pembayaran akan diverifikasi otomatis.
                                </p>
                                <span class="badge bg-info">Otomatis</span>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <strong>3</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Bergabung dengan Grup WhatsApp</h6>
                                <p class="text-muted small mb-2">
                                    Hubungi contact person untuk mendapatkan link grup WhatsApp peserta dan mendapatkan informasi terkini.
                                </p>
                                <span class="badge bg-success">Wajib</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <strong>4</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Persiapkan Karya</h6>
                                <p class="text-muted small mb-2">
                                    Kerjakan karya sesuai dengan tema dan kriteria yang ditentukan. Pastikan mengikuti timeline yang ada.
                                </p>
                                <span class="badge bg-warning">Perhatikan Deadline</span>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <strong>5</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Upload Karya</h6>
                                <p class="text-muted small mb-2">
                                    Upload karya Anda melalui sistem sebelum deadline. Pastikan format file sesuai ketentuan.
                                </p>
                                <a href="{{ route('peserta.submissions.index') }}" class="btn btn-sm btn-outline-danger">
                                    Upload Sekarang
                                </a>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <strong>6</strong>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold">Tunggu Hasil Penjurian</h6>
                                <p class="text-muted small mb-2">
                                    Pantau dashboard untuk melihat status penjurian dan pengumuman hasil kompetisi.
                                </p>
                                <span class="badge bg-secondary">Bersabar</span>
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

<!-- Competition Categories -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-grid me-2 text-info"></i>
                    Kategori Kompetisi yang Tersedia
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $categories = [
                            'event_debate' => ['icon' => 'bi-chat-square-text', 'name' => 'Debate Competition', 'color' => 'primary'],
                            'event_dcc' => ['icon' => 'bi-camera-video', 'name' => 'Digital Content Competition', 'color' => 'warning'],
                            'event_scientific_paper' => ['icon' => 'bi-journal-text', 'name' => 'Scientific Paper Competition', 'color' => 'success']
                        ];
                    @endphp
                    
                    @foreach($categories as $key => $category)
                    <div class="col-md-4">
                        <div class="card border-{{ $category['color'] }} h-100">
                            <div class="card-body text-center">
                                <i class="bi {{ $category['icon'] }} fs-2 text-{{ $category['color'] }} mb-3"></i>
                                <h6 class="card-title">{{ $category['name'] }}</h6>
                                <p class="card-text small text-muted">
                                    @if($key === 'event_debate')
                                        Kompetisi debat dalam bahasa Indonesia dan Inggris dengan topik terkini.
                                    @elseif($key === 'event_dcc')
                                        Kompetisi pembuatan konten digital kreatif seperti video, animasi, dan desain.
                                    @else
                                        Kompetisi penulisan karya ilmiah untuk mahasiswa dan siswa.
                                    @endif
                                </p>
                                <a href="{{ route('peserta.competitions.index', ['category' => $key]) }}" class="btn btn-outline-{{ $category['color'] }} btn-sm">
                                    Lihat Kompetisi
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>