@extends('layouts.tenant')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-maroon fw-bold">📜 Riwayat Penjualan</h2>
        <a href="{{ route('tenant.orders.pdf', request()->query()) }}" target="_blank" class="btn btn-outline-danger">
            <i class="bi bi-printer"></i> Cetak Laporan
        </a>
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
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="text-start">
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($order->orderDetails as $detail)
                                        <li>- {{ $detail->menu->name }} (x{{ $detail->qty }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-end fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .bg-maroon { background-color: #800000; }
    .text-maroon { color: #800000; }
</style>

 <script>
    document.addEventListener('DOMContentLoaded', function() {
        const year = new Date().getFullYear();
        // API Publik untuk Hari Libur Indonesia
        const apiUrl = `https://api-harilibur.vercel.app/api?year=${year}`;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                // Mapping hari libur: { "2024-12-25": "Hari Natal", ... }
                const holidays = {};
                data.forEach(h => {
                    holidays[h.holiday_date] = h.holiday_name;
                });

                // Cek setiap tanggal pada baris
                document.querySelectorAll('.order-date').forEach(el => {
                    const dateStr = el.getAttribute('data-date'); // YYYY-MM-DD
                    if (holidays[dateStr]) {
                        // Buat Badge
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-info text-dark ms-2';
                        badge.style.fontSize = '0.7em';
                        badge.innerText = '🏝️ ' + holidays[dateStr]; // Holiday Name
                        badge.title = holidays[dateStr];
                        el.appendChild(badge);
                    }
                });
            })
            .catch(err => console.error('Failed to fetch holidays', err));
    });
</script>
@endsection
