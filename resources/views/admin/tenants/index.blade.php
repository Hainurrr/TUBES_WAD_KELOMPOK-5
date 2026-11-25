@extends('layouts.admin')

@section('title', 'Manajemen Tenant — Panel Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="tc-display h4 fw-bold mb-1">Manajemen Tenant</h1>
            <p class="text-secondary small mb-0">Kelola profil gerai kantin, lokasi area, akun pengelola, dan status operasional.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.tenants.pdf') }}" target="_blank" class="btn btn-tc-outline btn-sm px-3 py-2 text-decoration-none">
                <i class="bi bi-file-earmark-pdf me-1"></i>Cetak PDF
            </a>
            <a href="{{ route('admin.tenants.create') }}" class="btn btn-tc-primary btn-sm px-4 py-2 text-decoration-none">
                <i class="bi bi-plus-lg me-1"></i>Tambah Tenant
            </a>
        </div>
    </div>

    <x-flash-alerts />

    <div class="tc-table-wrap">
        <div class="p-3 p-md-4 border-bottom bg-white">
            <div style="max-width: 380px;">
                <label for="tenantSearchInput" class="visually-hidden">Cari tenant</label>
                <input type="text" id="tenantSearchInput" data-table-filter="#adminTenantsTable" class="form-control" placeholder="Cari nama tenant, pemilik, atau area kantin...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table tc-table" id="adminTenantsTable">
                <thead>
                    <tr>
                        <th scope="col" class="ps-4">Tenant</th>
                        <th scope="col">Area Kantin</th>
                        <th scope="col">Pemilik &amp; Akun</th>
                        <th scope="col">Kontak</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                        <tr class="tenant-row" data-filter-row>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    @if($tenant->banner_image)
                                        <img src="{{ \Illuminate\Support\Str::startsWith($tenant->banner_image, 'http') ? $tenant->banner_image : asset('storage/' . $tenant->banner_image) }}"
                                             alt="{{ $tenant->name }}" class="rounded-3 object-fit-cover flex-shrink-0 border" width="52" height="52">
                                    @else
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 text-white fw-bold" style="width: 52px; height: 52px; background: var(--tc-obsidian);">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark">{{ $tenant->name }}</div>
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($tenant->description, 38) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="tc-badge tc-badge-neutral">{{ $tenant->category ?? '-' }}</span></td>
                            <td>
                                <div class="fw-semibold text-dark small">{{ $tenant->owner_name }}</div>
                                <small class="text-muted">{{ $tenant->user_account_name ?? 'Belum terhubung akun' }}</small>
                            </td>
                            <td>
                                <div class="small text-secondary tc-mono"><i class="bi bi-telephone me-1"></i>{{ $tenant->phone }}</div>
                                <div class="small text-muted text-truncate" style="max-width: 170px;"><i class="bi bi-geo-alt me-1"></i>{{ $tenant->city }}</div>
                            </td>
                            <td>
                                @if($tenant->is_open)
                                    <span class="tc-badge tc-badge-emerald"><span class="tc-dot" style="background: var(--tc-emerald);"></span>Buka</span>
                                @else
                                    <span class="tc-badge tc-badge-danger"><span class="tc-dot" style="background: var(--tc-danger);"></span>Tutup</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.tenants.edit', $tenant->id_tenant) }}" class="btn btn-tc-ghost btn-sm px-3" title="Ubah">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.tenants.destroy', $tenant->id_tenant) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus tenant ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">Belum ada data tenant terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tenants instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-4 py-3 border-top">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
