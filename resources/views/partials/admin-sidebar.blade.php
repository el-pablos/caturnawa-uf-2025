{{-- Admin Sidebar Menu --}}
<a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
    <i class="bi bi-speedometer2 me-2"></i>Dashboard
</a>

@can('users.view')
<a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
    <i class="bi bi-people me-2"></i>Kelola Pengguna
</a>
@endcan

@can('roles.view')
<a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
    <i class="bi bi-person-vcard me-2"></i>Kelola Role
</a>
@endcan

<a class="nav-link {{ request()->routeIs('admin.competitions.*') ? 'active' : '' }}" href="{{ route('admin.competitions.index') }}">
    <i class="bi bi-trophy me-2"></i>Kelola Kompetisi
</a>

<a class="nav-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}" href="{{ route('admin.registrations.index') }}">
    <i class="bi bi-person-check me-2"></i>Penilaian
</a>

<a class="nav-link {{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}" href="{{ route('admin.submissions.index') }}">
    <i class="bi bi-file-earmark-text me-2"></i>Karya Peserta
</a>

<a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
    <i class="bi bi-credit-card me-2"></i>Pembayaran
</a>

<a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
    <i class="bi bi-graph-up me-2"></i>Laporan
</a>

{{-- User Profile Section --}}
<div class="nav-divider mt-4 mb-3"></div>
<div class="nav-section-title">
    <small class="text-white-50">PENGATURAN</small>
</div>

<a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
    <i class="bi bi-gear me-2"></i>Pengaturan
</a>

<a class="nav-link" href="{{ route('profile.index') }}">
    <i class="bi bi-person-circle me-2"></i>Profil
</a>
