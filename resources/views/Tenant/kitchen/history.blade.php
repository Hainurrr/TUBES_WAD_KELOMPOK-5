@extends('layouts.tenant')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#manualOrderModal">
                    <i class="bi bi-plus-circle"></i> Tambah Manual
                </button>
                <a href="{{ route('tenant.orders.pdf', request()->query()) }}" target="_blank" class="btn btn-outline-danger">
                    <i class="bi bi-printer"></i> Cetak Laporan
                </a>
            </div>
        </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-maroon text-white shadow">
                <div class="card-body py-4 text-center">
                    <h5 class="card-title opacity-75">Total Pendapatan (Filtered)</h5>
                    <h1 class="display-4 fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('tenant.orders.history') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0 text-center align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>ID Order</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Menu</th>
                        <th class="text-end">Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id_order }}</td>
                            <td class="order-date" data-date="{{ $order->created_at->format('Y-m-d') }}">
                                {{ $order->created_at->format('d M Y H:i') }}
                                <!-- Badge Holiday will be inserted here by JS -->
                            </td>
                            <td>{{ $order->user->name ?? 'Walk-in / Manual' }}</td>
                            <td class="text-start">
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($order->orderDetails as $detail)
                                        <li>- {{ $detail->menu->name ?? 'Menu Terhapus' }} (x{{ $detail->qty }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-end fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                <!-- Tombol Hapus (Hanya untuk Admin/Tenant yang berhak, misal semua tenant boleh hapus history mereka sendiri) -->
                                <form action="{{ route('tenant.orders.destroy', $order->id_order) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat pesanan ini? Data tidak bisa dikembalikan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Pesanan Manual -->
<div class="modal fade" id="manualOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tenant.orders.storeManual') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pesanan Manual (Offline)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between">
                            <span>Item Menu</span>
                            <button type="button" class="btn btn-xs btn-primary py-0" onclick="addManualItem()">+ Tambah Item</button>
                        </label>
                        <div id="manual-items-container">
                            <div class="row g-2 mb-2 item-row">
                                <div class="col-8">
                                    <select name="items[0][id_menu]" class="form-select" required>
                                        <option value="">Pilih Menu...</option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->id_menu }}">
                                                {{ $menu->name }} - Rp {{ number_format($menu->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <input type="number" name="items[0][qty]" class="form-control" placeholder="Qty" min="1" value="1" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let itemIndex = 1;
    function addManualItem() {
        const container = document.getElementById('manual-items-container');
        const firstRow = container.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        // Update name attributes using Regex or simple replacement
        newRow.innerHTML = newRow.innerHTML.replace(/items\[0\]/g, `items[${itemIndex}]`);
        
        // Reset values
        newRow.querySelector('select').value = "";
        newRow.querySelector('input').value = "1";
        
        container.appendChild(newRow);
        itemIndex++;
    }
</script>

<style>
    .bg-maroon { background-color: #800000; }
    .text-maroon { color: #800000; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const year = new Date().getFullYear();
        const apiUrl = `https://api-harilibur.vercel.app/api?year=${year}`;

        // Helper: Normalisasi tanggal agar selalu YYYY-MM-DD (misal: 2026-1-1 jadi 2026-01-01)
        const normalizeDate = (dateStr) => {
            const parts = dateStr.split('-');
            if (parts.length !== 3) return dateStr;
            const y = parts[0];
            const m = parts[1].padStart(2, '0');
            const d = parts[2].padStart(2, '0');
            return `${y}-${m}-${d}`;
        };

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                const holidays = {};
                
                // Mapping data API dengan normalisasi tanggal
                data.forEach(h => {
                    const cleanDate = normalizeDate(h.holiday_date);
                    holidays[cleanDate] = h.holiday_name;
                });

                // Cek setiap baris tabel
                document.querySelectorAll('.order-date').forEach(el => {
                    const dateStr = el.getAttribute('data-date'); // Format di HTML: YYYY-MM-DD
                    
                    if (holidays[dateStr]) {
                        // Hapus badge lama jika ada (untuk menghindari duplikasi saat refresh manual)
                        const oldBadge = el.querySelector('.badge-holiday');
                        if(oldBadge) oldBadge.remove();

                        // Render Badge Baru
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-info text-dark ms-2 badge-holiday';
                        badge.style.fontSize = '0.75em';
                        badge.innerHTML = `<i class="bi bi-calendar-event me-1"></i> ${holidays[dateStr]}`;
                        badge.title = 'Hari Libur Nasional';
                        el.appendChild(badge);
                    }
                });
            })
            .catch(err => console.error('Gagal mengambil data hari libur:', err));
    });
</script>
@endsection
