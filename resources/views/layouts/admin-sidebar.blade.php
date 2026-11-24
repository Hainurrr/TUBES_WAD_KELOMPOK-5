<div class="d-flex flex-column p-3 h-100">
    <a href="{{ route('admin.dashboard') }}" class="tc-brand px-2 py-2 mb-3">
        <svg width="32" height="32" viewBox="0 0 36 36" fill="none" class="flex-shrink-0" aria-hidden="true">
            <rect width="36" height="36" rx="11" fill="#B9112E"/>
            <path d="M9.5 11.5H26.5V15.5H20.25V26H15.75V15.5H9.5V11.5Z" fill="#FFFFFF"/>
            <rect x="21.5" y="21.5" width="5" height="4.5" rx="1.5" fill="#F59E0B"/>
        </svg>
        <span class="sidebar-header-text">TelyuCan Admin</span>
    </a>

    <nav class="d-flex flex-row flex-lg-column gap-1 flex-wrap mb-lg-auto" aria-label="Menu admin">
        <a href="{{ route('admin.dashboard') }}" class="tc-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid fs-5"></i>
            <span class="sidebar-text">Ringkasan</span>
        </a>
        <a href="{{ route('admin.tenants.index') }}" class="tc-sidebar-link {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}">
            <i class="bi bi-shop fs-5"></i>
            <span class="sidebar-text">Manajemen Tenant</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="tc-sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people fs-5"></i>
            <span class="sidebar-text">Kelola Pengguna</span>
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="tc-sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="bi bi-chat-square-text fs-5"></i>
            <span class="sidebar-text">Manajemen Ulasan</span>
        </a>
    </nav>

    <div class="d-none d-lg-block pt-3 mt-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
        <button id="sidebarToggleInternal" type="button" class="btn w-100 d-flex align-items-center justify-content-center gap-2 text-white-50 border-0 py-2" aria-label="Ciutkan atau perluas bilah samping">
            <i class="bi bi-arrow-left-circle fs-5" id="sidebarToggleIcon"></i>
            <span class="sidebar-text small fw-semibold">Ciutkan Menu</span>
        </button>
    </div>
</div>
