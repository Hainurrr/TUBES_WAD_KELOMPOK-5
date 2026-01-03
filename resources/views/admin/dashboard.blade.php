@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-maroon">Dashboard</h2>
            <p class="text-secondary">Selamat Datang, {{ Auth::user()->name }}</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <!-- Total Tenants -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-shop fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary text-uppercase small fw-bold mb-1">Total Tenant</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalTenants }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active Tenants -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="bi bi-check-circle fs-3 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary text-uppercase small fw-bold mb-1">Tenant Buka</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $activeTenants }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inactive Tenants -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-shop-window fs-3 text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary text-uppercase small fw-bold mb-1">Tenant Tutup</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $inactiveTenants }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions or Recent Activity could go here -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold m-0 text-secondary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.tenants.create') }}" class="btn btn-outline-danger me-2"> 
                        <i class="bi bi-plus-lg me-1"></i> Tambah Tenant Baru
                    </a>
                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-list-ul me-1"></i> Lihat Semua Tenant
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
