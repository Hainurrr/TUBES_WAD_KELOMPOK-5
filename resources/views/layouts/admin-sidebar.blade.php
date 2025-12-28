<div class="d-flex flex-column flex-shrink-0 p-3 text-white h-100" style="width: 100%;">
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <div class="rounded text-white d-flex align-items-center justify-content-center me-2 fw-bold sidebar-logo" 
             style="width: 32px; height: 32px; background-color: #800000; flex-shrink: 0;">
            A
        </div>
        <span class="fs-4 fw-bold sidebar-header-text">TelyuCan Admin</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
               style="{{ request()->routeIs('admin.dashboard') ? 'background-color: #800000;' : '' }}">
                <i class="bi bi-speedometer2 me-2 fs-5"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.tenants.index') }}" class="nav-link text-white {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}"
               style="{{ request()->routeIs('admin.tenants.*') ? 'background-color: #800000;' : '' }}">
                <i class="bi bi-shop me-2 fs-5"></i>
                <span class="sidebar-text">Manajemen Tenant</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.index') }}" class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
               style="{{ request()->routeIs('admin.users.*') ? 'background-color: #800000;' : '' }}">
                <i class="bi bi-people me-2 fs-5"></i>
                <span class="sidebar-text">Kelola User</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.reviews.index') }}" class="nav-link text-white {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
               style="{{ request()->routeIs('admin.reviews.*') ? 'background-color: #800000;' : '' }}">
                <i class="bi bi-chat-quote me-2 fs-5"></i>
                <span class="sidebar-text">Manajemen Ulasan</span>
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
