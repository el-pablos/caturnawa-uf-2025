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

{{-- User Info at Bottom --}}
<div class="nav-user-info">
    <div class="d-flex align-items-center p-3 mt-4 border-top border-light border-opacity-25">
        <div class="user-avatar me-3">
            <img src="{{ auth()->user()->avatar_url }}" width="40" height="40" 
                 class="rounded-circle" alt="Avatar">
        </div>
        <div class="user-details flex-grow-1">
            <div class="user-name text-white fw-semibold">{{ auth()->user()->name }}</div>
            <div class="user-role text-white-50 small">{{ auth()->user()->getRoleNames()->first() }}</div>
        </div>
        <div class="dropdown">
            <button class="btn btn-link text-white p-0" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-dark">
                <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                    <i class="bi bi-person-circle me-2"></i>Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
