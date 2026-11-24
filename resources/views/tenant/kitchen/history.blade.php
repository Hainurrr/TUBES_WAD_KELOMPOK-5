@extends('layouts.tenant')

@section('title', 'Riwayat Penjualan — Panel Tenant')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="tc-display h4 fw-bold mb-1">Riwayat Penjualan</h1>
            <p class="text-secondary small mb-0">Rekap transaksi selesai dan pencatatan pesanan langsung (walk-in).</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-tc-emerald btn-sm px-3 py-2" data-bs-toggle="modal" data-bs-target="#manualOrderModal">
                <i class="bi bi-plus-circle me-1"></i>Catat Pesanan Langsung
            </button>
            <a href="{{ route('tenant.orders.pdf', request()->query()) }}" target="_blank" class="btn btn-tc-outline btn-sm px-3 py-2 text-decoration-none">
                <i class="bi bi-printer me-1"></i>Cetak Laporan PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 mb-4">{{ session('error') }}</div>
    @endif

    {{-- Bento Summary & Date Filter --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-5">
            <div class="tc-card-dark p-4 h-100 d-flex flex-column justify-content-between">
                <span class="small fw-semibold" style="color: #CBD5E1;">Total Pendapatan (Sesuai Filter)</span>
                <div class="tc-mono fw-bold mt-2 mb-1" style="font-size: clamp(1.65rem, 2.8vw, 2.25rem);">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </div>
                <span class="small" style="color: #94A3B8;">Dari {{ $orders->count() }} transaksi selesai</span>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="tc-card p-4 h-100 d-flex align-items-center">
                <form action="{{ route('tenant.orders.history') }}" method="GET" class="row g-3 align-items-end w-100">
                    <div class="col-sm-4">
                        <label for="startDate" class="form-label">Dari Tanggal</label>
                        <input type="date" id="startDate" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-sm-4">
                        <label for="endDate" class="form-label">Sampai Tanggal</label>
                        <input type="date" id="endDate" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-sm-4 d-flex gap-2">
                        <button type="submit" class="btn btn-tc-primary w-100">Terapkan</button>
                        @if(request('start_date') || request('end_date'))
                            <a href="{{ route('tenant.orders.history') }}" class="btn btn-tc-ghost px-3" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="tc-table-wrap">
        <div class="table-responsive">
            <table class="table tc-table">
                <thead>
                    <tr>
                        <th class="ps-4">ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Rincian Menu</th>
                        <th class="text-end">Total</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 tc-mono fw-bold">#{{ $order->id_order }}</td>
                            <td class="order-date small tc-num" data-date="{{ $order->created_at->format('Y-m-d') }}">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="fw-medium">{{ $order->user->name ?? 'Langsung di Konter' }}</td>
                            <td>
                                <ul class="list-unstyled mb-0 small text-secondary">
                                    @foreach($order->orderDetails as $detail)
                                        <li>{{ $detail->menu->name ?? 'Menu Terhapus' }} <span class="tc-mono">(x{{ $detail->qty }})</span></li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-end fw-bold tc-mono">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="pe-4 text-end">
                                <form action="{{ route('tenant.orders.destroy', $order->id_order) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat pesanan ini? Data tidak bisa dikembalikan.');" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">Belum ada data penjualan pada rentang waktu ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Manual Walk-in Order Modal --}}
<div class="modal fade" id="manualOrderModal" tabindex="-1" aria-labelledby="manualOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 p-2" style="border-radius: 24px;">
            <form action="{{ route('tenant.orders.storeManual') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="tc-display fw-bold mb-0" id="manualOrderModalLabel">Catat Pesanan Langsung (Konter)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="manualDate" class="form-label">Tanggal Transaksi</label>
                        <input type="date" id="manualDate" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">Daftar Menu</label>
                            <button type="button" class="btn btn-tc-ghost btn-sm py-1 px-3" onclick="addManualItem()">+ Tambah Baris</button>
                        </div>
                        <div id="manual-items-container">
                            <div class="row g-2 mb-2 item-row">
                                <div class="col-8">
                                    <select name="items[0][id_menu]" class="form-select" required>
                                        <option value="">Pilih Menu...</option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->id_menu }}">
                                                {{ $menu->name }} — Rp {{ number_format($menu->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <input type="number" name="items[0][qty]" class="form-control tc-mono" placeholder="Jml" min="1" value="1" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-tc-ghost px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tc-primary px-4">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = 1;
    function addManualItem() {
        const container = document.getElementById('manual-items-container');
        const firstRow = container.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        newRow.innerHTML = newRow.innerHTML.replace(/items\[0\]/g, `items[${itemIndex}]`);
        newRow.querySelector('select').value = '';
        newRow.querySelector('input').value = '1';
        container.appendChild(newRow);
        itemIndex++;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const year = new Date().getFullYear();
        const apiUrl = `https://api-harilibur.vercel.app/api?year=${year}`;
        const normalizeDate = (dateStr) => {
            const parts = dateStr.split('-');
            if (parts.length !== 3) return dateStr;
            return `${parts[0]}-${parts[1].padStart(2, '0')}-${parts[2].padStart(2, '0')}`;
        };

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                const holidays = {};
                data.forEach(h => { holidays[normalizeDate(h.holiday_date)] = h.holiday_name; });

                document.querySelectorAll('.order-date').forEach(el => {
                    const dateStr = el.getAttribute('data-date');
                    if (holidays[dateStr]) {
                        const oldBadge = el.querySelector('.badge-holiday');
                        if (oldBadge) oldBadge.remove();
                        const badge = document.createElement('span');
                        badge.className = 'tc-badge tc-badge-amber ms-2 badge-holiday';
                        badge.innerHTML = `<i class="bi bi-calendar-event"></i>${holidays[dateStr]}`;
                        badge.title = 'Hari Libur Nasional';
                        el.appendChild(badge);
                    }
                });
            })
            .catch(() => {});
    });
</script>
@endpush
@endsection
