@extends('layouts.admin')

@section('title', 'Riwayat Check-in')
@section('page-title', 'Riwayat Check-in')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.qr-scanner.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Scanner
        </a>
        <button type="button" class="btn btn-primary" onclick="exportHistory()">
            <i class="bi bi-download me-2"></i>Export Data
        </button>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Check-in</h6>
                                <h3 class="mb-0">{{ $checkedInRegistrations->total() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Hari Ini</h6>
                                <h3 class="mb-0">{{ $checkedInRegistrations->where('checked_in_at', '>=', today())->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Minggu Ini</h6>
                                <h3 class="mb-0">{{ $checkedInRegistrations->where('checked_in_at', '>=', now()->startOfWeek())->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-week" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Bulan Ini</h6>
                                <h3 class="mb-0">{{ $checkedInRegistrations->where('checked_in_at', '>=', now()->startOfMonth())->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-month" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Check-in Peserta
                </h6>
            </div>
            <div class="card-body">
                @if($checkedInRegistrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="60">#</th>
                                    <th>Peserta</th>
                                    <th>Kompetisi</th>
                                    <th>No. Registrasi</th>
                                    <th>Institusi</th>
                                    <th>Check-in</th>
                                    <th>Petugas</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($checkedInRegistrations as $registration)
                                <tr>
                                    <td>{{ $loop->iteration + ($checkedInRegistrations->currentPage() - 1) * $checkedInRegistrations->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2">
                                                @if($registration->user->profile_photo)
                                                    <img src="{{ asset('storage/' . $registration->user->profile_photo) }}" 
                                                         width="32" height="32" class="rounded-circle" alt="Avatar">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 32px; height: 32px;">
                                                        <i class="bi bi-person-fill text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $registration->user->name }}</strong>
                                                <br><small class="text-muted">{{ $registration->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $registration->competition->name }}</strong>
                                        <br><small class="text-muted">{{ $registration->competition->category }}</small>
                                    </td>
                                    <td>
                                        <code>{{ $registration->registration_number }}</code>
                                    </td>
                                    <td>{{ $registration->institution ?: '-' }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $registration->checked_in_at->format('d M Y') }}</strong>
                                            <br><small class="text-muted">{{ $registration->checked_in_at->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($registration->checkedInBy)
                                            <small>{{ $registration->checkedInBy->name }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.registrations.show', $registration) }}" 
                                               class="btn btn-outline-info" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('peserta.registrations.ticket', $registration) }}" 
                                               class="btn btn-outline-primary" title="Lihat E-Ticket">
                                                <i class="bi bi-ticket-perforated"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $checkedInRegistrations->firstItem() }} - {{ $checkedInRegistrations->lastItem() }} 
                            dari {{ $checkedInRegistrations->total() }} check-in
                        </div>
                        {{ $checkedInRegistrations->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Riwayat Check-in</h5>
                        <p class="text-muted">Riwayat check-in peserta akan muncul di sini setelah ada yang melakukan check-in.</p>
                        <a href="{{ route('admin.qr-scanner.index') }}" class="btn btn-primary">
                            <i class="bi bi-qr-code-scan me-2"></i>Mulai Scanner
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportHistory() {
    // Implement export functionality
    window.open('/admin/qr-scanner/export', '_blank');
}
</script>
@endpush
