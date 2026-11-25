@extends('layouts.tenant')

@section('title', 'Kelola Menu — Panel Tenant')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="tc-display h4 fw-bold mb-1">Manajemen Menu Gerai</h1>
            <p class="text-secondary small mb-0">Atur daftar makanan, minuman, harga, dan stok harian gerai Anda.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('tenant.menus.pdf') }}" target="_blank" class="btn btn-tc-outline btn-sm px-3 py-2 text-decoration-none">
                <i class="bi bi-file-earmark-pdf me-1"></i>Cetak Daftar PDF
            </a>
            <a href="{{ route('tenant.menus.create') }}" class="btn btn-tc-primary btn-sm px-4 py-2 text-decoration-none">
                <i class="bi bi-plus-lg me-1"></i>Tambah Menu
            </a>
        </div>
    </div>

    <div id="holidayAlert" data-holiday-alert class="alert alert-warning border-0 rounded-4 alert-dismissible fade show d-none mb-4" role="alert">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div class="small">
                <strong>Catatan Hari Libur Nasional:</strong> Pastikan jumlah stok harian disesuaikan dengan perkiraan pengunjung kampus hari ini.
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>

    <x-flash-alerts />

    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-4">
        @forelse($menus as $menu)
            <div class="col">
                <article class="tc-menu-card">
                    <div class="tc-menu-thumb">
                        @if($menu->image)
                            <img src="{{ \Illuminate\Support\Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                <i class="bi bi-image fs-2"></i>
                            </div>
                        @endif
                        <span class="position-absolute top-0 start-0 m-3 tc-badge tc-badge-dark">
                            {{ $menu->category->name ?? 'Tanpa Kategori' }}
                        </span>
                        <span class="position-absolute top-0 end-0 m-3 tc-badge {{ $menu->stock > 0 ? 'tc-badge-emerald-solid' : 'tc-badge-danger' }} tc-num">
                            {{ $menu->stock > 0 ? 'Stok ' . $menu->stock : 'Habis' }}
                        </span>
                    </div>

                    <div class="tc-menu-body">
                        <h2 class="tc-menu-title text-truncate" title="{{ $menu->name }}">{{ $menu->name }}</h2>
                        <div class="tc-mono fw-bold text-maroon mb-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                        <p class="tc-menu-desc">{{ \Illuminate\Support\Str::limit($menu->description, 70) }}</p>

                        <div class="d-flex gap-2 pt-3 border-top">
                            <a href="{{ route('tenant.menus.edit', $menu->id_menu) }}" class="btn btn-tc-ghost btn-sm flex-grow-1 text-decoration-none">
                                <i class="bi bi-pencil-square me-1"></i>Ubah
                            </a>
                            <form action="{{ route('tenant.menus.destroy', $menu->id_menu) }}" method="POST" class="flex-grow-1 m-0" onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100 fw-semibold">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <x-empty-state
                    icon="bi-journal-plus"
                    title="Belum ada menu terdaftar"
                    description="Tambahkan menu pertama agar pelanggan mulai dapat memesan dari gerai Anda."
                    :action-url="route('tenant.menus.create')"
                    action-label="Tambah Menu Pertama"
                />
            </div>
        @endforelse
    </div>
</div>
@endsection
