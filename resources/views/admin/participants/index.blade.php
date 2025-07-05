@extends('layouts.admin')

@section('title', 'Data Peserta')

@section('breadcrumb')
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0">Data Peserta</h1>
        <nav class="ms-auto">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Peserta</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-people fs-1 mb-2 text-primary"></i>
                <div class="unas-stats-number">{{ number_format($stats['total']) }}</div>
                <div class="unas-stats-label">Total Peserta</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-person-check fs-1 mb-2 text-success"></i>
                <div class="unas-stats-number text-success">{{ number_format($stats['active']) }}</div>
                <div class="unas-stats-label">Akun Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-shield-check fs-1 mb-2 text-info"></i>
                <div class="unas-stats-number text-info">{{ number_format($stats['verified']) }}</div>
                <div class="unas-stats-label">Email Verified</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="unas-stats-card">
            <div class="text-center">
                <i class="bi bi-trophy fs-1 mb-2 text-warning"></i>
                <div class="unas-stats-number text-warning">{{ number_format($stats['registered']) }}</div>
                <div class="unas-stats-label">Terdaftar Kompetisi</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filter Peserta
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-2">
                <label class="form-label fw-semibold">Kompetisi</label>
                <select name="competition_id" class="form-select">
                    <option value="">Semua Kompetisi</option>
                    @foreach($competitions as $competition)
                        <option value="{{ $competition->id }}" {{ request('competition_id') == $competition->id ? 'selected' : '' }}>
                            {{ $competition->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Email Verified</option>
                    <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Email Unverified</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Institusi</label>
                <select name="institution" class="form-select">
                    <option value="">Semua Institusi</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution }}" {{ request('institution') === $institution ? 'selected' : '' }}>
                            {{ $institution }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, email, institusi, atau NIM..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('admin.participants.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Participants Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-people me-2"></i>Data Peserta
        </h6>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.participants.export', request()->query()) }}" class="btn btn-success btn-sm">
                <i class="bi bi-download me-1"></i>Export CSV
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($registrations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Asal Instansi</th>
                            <th>Nama Team</th>
                            <th>Peserta 1</th>
                            <th>Peserta 2</th>
                            <th>Peserta 3</th>
                            <th>Peserta 4</th>
                            <th>Peserta 5</th>
                            <th>Email 1</th>
                            <th>Email 2</th>
                            <th>Email 3</th>
                            <th>Email 4</th>
                            <th>Email 5</th>
                            <th>No HP 1</th>
                            <th>No HP 2</th>
                            <th>No HP 3</th>
                            <th>No HP 4</th>
                            <th>No HP 5</th>
                            <th>Foto 1</th>
                            <th>Foto 2</th>
                            <th>Foto 3</th>
                            <th>Foto 4</th>
                            <th>Foto 5</th>
                            <th>Logo Instansi</th>
                            <th>Status Pembayaran</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $index => $registration)
                            @php
                                $teamMembers = $registration->teamMembers->take(5);
                                $leader = $registration->user;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $registration->institution ?? 'Tidak ada' }}</td>
                                <td>{{ $registration->team_name ?? 'Individual' }}</td>

                                <!-- Peserta 1 (Leader) -->
                                <td>{{ $leader->name }}</td>

                                <!-- Peserta 2-5 (Team Members) -->
                                @for($i = 0; $i < 4; $i++)
                                    <td>{{ $teamMembers->get($i)->name ?? '-' }}</td>
                                @endfor

                                <!-- Email 1 (Leader) -->
                                <td>{{ $leader->email }}</td>

                                <!-- Email 2-5 (Team Members) -->
                                @for($i = 0; $i < 4; $i++)
                                    <td>{{ $teamMembers->get($i)->email ?? '-' }}</td>
                                @endfor

                                <!-- No HP 1 (Leader) -->
                                <td>{{ $leader->phone ?? $registration->phone ?? '-' }}</td>

                                <!-- No HP 2-5 (Team Members) -->
                                @for($i = 0; $i < 4; $i++)
                                    <td>{{ $teamMembers->get($i)->phone ?? '-' }}</td>
                                @endfor

                                <!-- Foto 1 (Leader) -->
                                <td>
                                    @if($leader->avatar)
                                        <a href="{{ asset('storage/' . $leader->avatar) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-image"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <!-- Foto 2-5 (Team Members) - Placeholder for now -->
                                @for($i = 0; $i < 4; $i++)
                                    <td>
                                        <span class="text-muted">-</span>
                                    </td>
                                @endfor

                                <!-- Logo Instansi -->
                                <td>
                                    <img src="https://via.placeholder.com/40x40/007bff/ffffff?text={{ substr($registration->institution ?? 'N', 0, 2) }}"
                                         alt="{{ $registration->institution }}"
                                         class="rounded"
                                         style="width: 30px; height: 30px;">
                                </td>

                                <!-- Status Pembayaran -->
                                <td>
                                    @if($registration->payment && $registration->payment->transaction_status === 'settlement')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($registration->payment && $registration->payment->transaction_status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Belum Bayar</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.registrations.show', $registration) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $registration->id }})">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $registrations->firstItem() }} - {{ $registrations->lastItem() }} dari {{ $registrations->total() }} pendaftaran
                </div>
                {{ $registrations->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">Tidak ada data peserta ditemukan</h5>
                <p class="text-muted">Belum ada data peserta yang sesuai dengan filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>

<script>
function confirmDelete(registrationId) {
    if (confirm('Apakah Anda yakin ingin menghapus data peserta ini?')) {
        // Add delete functionality here
        console.log('Delete registration:', registrationId);
        // You can implement the actual delete functionality here
    }
}
</script>
@endsection
