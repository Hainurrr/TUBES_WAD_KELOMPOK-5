@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #800000;">Daftar Tenant</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.tenants.create') }}" class="btn text-white" style="background-color: #800000;">
                <i class="bi bi-plus-lg me-1"></i> Tambah Tenant
            </a>
            <a href="{{ route('admin.tenants.pdf') }}" target="_blank" class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="p-4 border-bottom bg-light">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-secondary"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" placeholder="Cari tenant berdasarkan nama atau pemilik...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-secondary small text-uppercase">
                            <th scope="col" class="ps-4">Tenant</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Pemilik</th>
                            <th scope="col">Kontak</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $tenant)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    @if($tenant->banner_image)
                                        <img src="{{ Str::startsWith($tenant->banner_image, 'http') ? $tenant->banner_image : asset('storage/' . $tenant->banner_image) }}" alt="{{ $tenant->name }}" class="rounded-3 object-fit-cover shadow-sm me-3" width="60" height="60">
                                    @else
                                        <div class="bg-secondary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-secondary small me-3" style="width: 60px; height: 60px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ $tenant->name }}</h6>
                                        <small class="text-muted">{{ Str::limit($tenant->description, 30) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-normal px-3 py-2 rounded-pill">
                                    {{ $tenant->category ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $tenant->owner_name }}</div>
                                <small class="text-secondary d-block">{{ $tenant->user_account_name ?? 'No Account' }}</small>
                            </td>
                            <td>
                                <div class="small text-secondary"><i class="bi bi-telephone me-1"></i> {{ $tenant->phone }}</div>
                                <div class="small text-secondary text-truncate" style="max-width: 150px;">
                                    <i class="bi bi-geo-alt me-1"></i> {{ $tenant->city }}
                                </div>
                            </td>
                            <td>
                                @if($tenant->is_open)
                                    <div class="d-flex align-items-center text-success fw-medium">
                                        <i class="bi bi-check-circle-fill me-2"></i> Buka
                                    </div>
                                @else
                                    <div class="d-flex align-items-center text-danger fw-medium">
                                        <i class="bi bi-dash-circle-fill me-2"></i> Tutup
                                    </div>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.tenants.edit', $tenant->id_tenant) }}" class="btn btn-light btn-sm text-primary" data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.tenants.destroy', $tenant->id_tenant) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm text-danger" data-bs-toggle="tooltip" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486777.png" width="80" class="mb-3 opacity-25" alt="Empty">
                                <p class="text-secondary fw-medium">Belum ada data tenant.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tenants instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="p-4 border-top">
                {{ $tenants->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection