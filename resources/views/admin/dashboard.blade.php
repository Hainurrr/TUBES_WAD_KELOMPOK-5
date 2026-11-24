@extends('layouts.admin')

@section('title', 'Ringkasan — Panel Admin TelyuCan')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <h1 class="tc-display h4 fw-bold mb-1">Ringkasan Operasional</h1>
        <p class="text-secondary small mb-0">Selamat datang, {{ Auth::user()->name }}. Pantau status gerai kantin dan akses cepat pengelolaan.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="tc-card-dark p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-semibold" style="color: #CBD5E1;">Total Tenant Terdaftar</span>
                    <span class="rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(255,255,255,0.1); color: #FFFFFF;">
                        <i class="bi bi-shop"></i>
                    </span>
                </div>
                <div class="tc-mono fw-bold display-6 text-white mb-0">{{ $totalTenants }}</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="tc-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-semibold text-secondary">Tenant Sedang Buka</span>
                    <span class="rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: var(--tc-emerald-soft); color: var(--tc-emerald-ink);">
                        <i class="bi bi-check2-circle"></i>
                    </span>
                </div>
                <div class="tc-mono fw-bold display-6 mb-0" style="color: var(--tc-emerald-ink);">{{ $activeTenants }}</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="tc-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-semibold text-secondary">Tenant Sedang Tutup</span>
                    <span class="rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: var(--tc-surface-muted); color: var(--tc-ink-secondary);">
                        <i class="bi bi-shop-window"></i>
                    </span>
                </div>
                <div class="tc-mono fw-bold display-6 text-dark mb-0">{{ $inactiveTenants }}</div>
            </div>
        </div>
    </div>

    <div class="tc-card p-4">
        <h2 class="tc-display h6 fw-bold mb-3">Aksi Cepat Pengelolaan</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.tenants.create') }}" class="btn btn-tc-primary btn-sm px-4 py-2 text-decoration-none">
                <i class="bi bi-plus-lg me-1"></i>Tambah Tenant Baru
            </a>
            <a href="{{ route('admin.tenants.index') }}" class="btn btn-tc-ghost btn-sm px-4 py-2 text-decoration-none">
                <i class="bi bi-shop me-1"></i>Lihat Semua Tenant
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-tc-ghost btn-sm px-4 py-2 text-decoration-none">
                <i class="bi bi-people me-1"></i>Kelola Pengguna
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-tc-ghost btn-sm px-4 py-2 text-decoration-none">
                <i class="bi bi-chat-square-text me-1"></i>Moderasi Ulasan
            </a>
        </div>
    </div>
</div>
@endsection
