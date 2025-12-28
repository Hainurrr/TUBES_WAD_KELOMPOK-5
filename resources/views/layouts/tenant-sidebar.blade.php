<div class="d-flex flex-column flex-shrink-0 p-3 text-white h-100" style="width: 100%;">
    <a href="{{ route('tenant.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <div class="rounded text-white d-flex align-items-center justify-content-center me-2 fw-bold sidebar-logo" 
             style="width: 32px; height: 32px; background-color: #800000; flex-shrink: 0;">
            T
        </div>
        <span class="fs-4 fw-bold sidebar-header-text">Tenant Panel</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('tenant.dashboard') }}" class="nav-link text-white {{ request()->routeIs('tenant.dashboard') || request()->routeIs('tenant.orders.index') ? 'active' : '' }}" 
               style="{{ request()->routeIs('tenant.dashboard') || request()->routeIs('tenant.orders.index') ? 'background-color: #800000;' : '' }}">
                <i class="bi bi-speedometer2 me-2 fs-5"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('tenant.menus.index') }}" class="nav-link text-white {{ request()->routeIs('tenant.menus.*') ? 'active' : '' }}"
               style="{{ request()->routeIs('tenant.menus.*') ? 'background-color: #800000;' : '' }}">
                <i class="bi bi-book-half me-2 fs-5"></i>
                <span class="sidebar-text">Kelola Menu</span>
            </a>
        </li>
        <li>
            <a href="{{ route('tenant.orders.history') }}" class="nav-link text-white {{ request()->routeIs('tenant.orders.history') ? 'active' : '' }}"
               style="{{ request()->routeIs('tenant.orders.history') ? 'background-color: #800000;' : '' }}">
                <i class="bi bi-clock-history me-2 fs-5"></i>
                <span class="sidebar-text">Riwayat Pesanan</span>
            </a>
        </li>
        <li>
            <a href="{{ route('tenant.reviews.index') }}" class="nav-link text-white {{ request()->routeIs('tenant.reviews.*') ? 'active' : '' }}"
               style="{{ request()->routeIs('tenant.reviews.*') ? 'background-color: #800000;' : '' }}">
                <i class="bi bi-chat-quote me-2 fs-5"></i>
                <span class="sidebar-text">Ulasan Pelanggan</span>
            </a>
        </li>
    </ul>
    
    <!-- Internal Sidebar Toggle (Arrow) -->
    <div class="mt-auto">
        <hr>
        <button id="sidebarToggleInternal" class="btn btn-link text-white text-decoration-none w-100 d-flex align-items-center justify-content-center p-0">
            <i class="bi bi-arrow-left-circle fs-3" id="sidebarToggleIcon"></i>
        </button>
    </div>
</div>
