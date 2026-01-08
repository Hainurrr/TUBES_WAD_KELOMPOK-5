@extends('layouts.tenant')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-maroon mb-1">Manajemen Menu</h2>
            <p class="text-secondary small mb-0">Kelola daftar menu makanan dan minuman Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tenant.menus.pdf') }}" target="_blank" class="btn btn-outline-danger">
                <i class="bi bi-file-pdf me-2"></i>Export PDF
            </a>
            <a href="{{ route('tenant.menus.create') }}" class="btn btn-primary fw-bold" style="background-color: #800000; border-color: #800000;">
                <i class="bi bi-plus-lg me-2"></i>Tambah Menu
            </a>
        </div>
    </div>

    <!-- Holiday Alert -->
    <div id="holidayAlert" class="alert alert-warning alert-dismissible fade show d-none" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div>
                <strong>Peringatan:</strong> Hari ini adalah hari libur nasional. Pastikan stok Anda sesuai dengan perkiraan pengunjung.
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Menu Grid -->
    <div class="row g-4">
        @forelse($menus as $menu)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="position-relative">
                        @if($menu->image)
                            <img src="{{ Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image) }}" class="card-img-top object-fit-cover" alt="{{ $menu->name }}" style="height: 200px;">
                        @else
                            <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-secondary" style="height: 200px;">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        
                        <div class="position-absolute top-0 end-0 p-2">
                            <span class="badge {{ $menu->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                Stok: {{ $menu->stock }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold text-truncate mb-0" title="{{ $menu->name }}">{{ $menu->name }}</h5>
                        </div>
                        <p class="text-maroon fw-bold mb-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        <p class="card-text text-secondary small text-truncate">{{ $menu->description }}</p>
                        
                        <div class="mt-3">
                            <span class="badge bg-light text-dark border">{{ $menu->category->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                        <div class="d-flex gap-2">
                            <a href="{{ route('tenant.menus.edit', $menu->id_menu) }}" class="btn btn-warning btn-sm flex-grow-1 text-white">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                            <form action="{{ route('tenant.menus.destroy', $menu->id_menu) }}" method="POST" class="d-inline flex-grow-1" onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="mb-3">
                    <i class="bi bi-clipboard-x text-secondary" style="font-size: 4rem;"></i>
                </div>
                <h4 class="text-secondary fw-bold">Belum ada menu</h4>
                <p class="text-secondary">Silakan tambahkan menu makanan atau minuman Anda.</p>
                <a href="{{ route('tenant.menus.create') }}" class="btn btn-primary mt-2" style="background-color: #800000; border-color: #800000;">
                    Tambah Menu Pertama
                </a>
            </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('https://dayoffapi.vercel.app/api')
            .then(response => response.json())
            .then(data => {
                // Cek apakah hari ini libur
                // Logika sederhana: Jika API mengembalikan tanggal yang sama dengan hari ini
                const today = new Date().toISOString().split('T')[0];
                const isHoliday = data.some(holiday => holiday.tanggal === today);

                if (isHoliday) {
                    document.getElementById('holidayAlert').classList.remove('d-none');
                }
            })
            .catch(error => console.error('Error fetching holiday data:', error));
    });
</script>

<style>
    .text-maroon { color: #800000; }
    .hover-card { transition: transform 0.2s; }
    .hover-card:hover { transform: translateY(-5px); }
</style>
@endsection
