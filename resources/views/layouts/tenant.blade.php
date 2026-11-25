@include('layouts.dashboard', [
    'defaultTitle' => 'Panel Tenant — TelyuCan',
    'sidebarView' => 'layouts.tenant-sidebar',
    'topbarTitle' => 'Area Operasional Tenant',
    'tenantBadgeName' => Auth::user()?->tenant?->name,
    'dropdownId' => 'dropdownTenantUser',
    'fallbackUserLabel' => 'Pengelola Tenant',
])
