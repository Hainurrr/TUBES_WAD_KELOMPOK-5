<div class="dropdown">
    <button class="btn border-0 p-0 d-flex align-items-center gap-2 text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu akun">
        <x-user-avatar :user="Auth::user()" :size="38" style="border: 2px solid rgba(255,255,255,0.22);" />
    </button>

    <ul class="dropdown-menu dropdown-menu-end tc-dropdown-menu mt-2" style="min-width: 230px;">
        <li class="px-3 py-2 border-bottom mb-1">
            <div class="fw-bold text-dark lh-sm">{{ Auth::user()->name }}</div>
            <small class="text-muted d-block text-truncate">{{ Auth::user()->email }}</small>
        </li>
        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i>Profil &amp; Pengaturan</a></li>

        @if(Auth::user()->role === 'admin')
            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid me-2"></i>Panel Admin</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>Kelola Pengguna</a></li>
        @endif

        @if(Auth::user()->role === 'tenant')
            <li><a class="dropdown-item" href="{{ route('tenant.dashboard') }}"><i class="bi bi-fire me-2"></i>Layar Dapur</a></li>
            <li><a class="dropdown-item" href="{{ route('tenant.menus.index') }}"><i class="bi bi-journal-text me-2"></i>Kelola Menu</a></li>
            <li><a class="dropdown-item" href="{{ route('tenant.orders.history') }}"><i class="bi bi-receipt me-2"></i>Riwayat Penjualan</a></li>
        @endif

        @if(Auth::user()->role !== 'admin')
            <li><a class="dropdown-item" href="{{ route('orders.history') }}"><i class="bi bi-clock-history me-2"></i>Riwayat Pesanan</a></li>
        @endif

        <li><hr class="dropdown-divider my-1"></li>
        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-danger fw-semibold">
                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                </button>
            </form>
        </li>
    </ul>
</div>
