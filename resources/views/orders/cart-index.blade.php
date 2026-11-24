@extends('layouts.app')

@section('title', 'Keranjang Belanja — TelyuCan')

@section('content')
<section class="tc-page-banner">
    <div class="container">
        <a href="{{ route('menus.index') }}" class="text-white-50 text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1 mb-3">
            <i class="bi bi-arrow-left"></i>Lanjut Pilih Menu
        </a>
        <h1 class="mb-1" style="font-size: clamp(1.65rem, 2.8vw, 2.2rem);">Keranjang Belanja</h1>
        <p class="mb-0" style="color: #CBD5E1;">Periksa pesanan dari setiap gerai sebelum melanjutkan ke pembayaran Midtrans.</p>
    </div>
</section>

<div class="container py-4 py-lg-5">
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 mb-4">{{ session('error') }}</div>
    @endif

    @if(empty($cart) || count($cart) == 0)
        <div class="tc-card p-5 text-center">
            <i class="bi bi-bag-x fs-1 text-muted d-block mb-3"></i>
            <h2 class="tc-display h5 fw-bold mb-1">Keranjang belanjamu masih kosong</h2>
            <p class="text-secondary small mb-4">Tambahkan makanan atau minuman dari gerai kantin yang buka untuk mulai memesan.</p>
            <a href="{{ route('menus.index') }}" class="btn btn-tc-primary px-4">Jelajahi Menu</a>
        </div>
    @else
        @php $grandTotal = 0; @endphp
        @foreach($cart as $tenantId => $group)
            @php $subtotalTenant = 0; @endphp
            <div class="tc-table-wrap mb-4">
                <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center bg-white">
                    <h2 class="tc-display h6 fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-shop text-maroon"></i>
                        <span>{{ $group['tenant_name'] ?? 'Gerai #' . $tenantId }}</span>
                    </h2>
                    <span class="tc-badge tc-badge-neutral">Ambil di Konter</span>
                </div>
                <div class="table-responsive">
                    <table class="table tc-table">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-4">Menu</th>
                                <th scope="col" class="text-end">Harga Satuan</th>
                                <th scope="col" class="text-center">Jumlah</th>
                                <th scope="col" class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group['items'] as $menuId => $item)
                                @php
                                    $totalItem = $item['price'] * $item['qty'];
                                    $subtotalTenant += $totalItem;
                                    $grandTotal += $totalItem;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            @if(isset($item['image']) && $item['image'])
                                                <img src="{{ \Illuminate\Support\Str::startsWith($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}"
                                                     class="rounded-3 object-fit-cover flex-shrink-0" width="52" height="52" alt="{{ $item['name'] }}">
                                            @endif
                                            <span class="fw-bold">{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end tc-mono small">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-2 p-1 rounded-pill border bg-white">
                                            <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="id_menu" value="{{ $menuId }}">
                                                <input type="hidden" name="action" value="decrease">
                                                <button type="submit" class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center border-0" style="width: 26px; height: 26px; background: var(--tc-surface-muted);" aria-label="Kurangi jumlah">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                            </form>
                                            <span class="fw-bold tc-mono px-1">{{ $item['qty'] }}</span>
                                            <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="id_menu" value="{{ $menuId }}">
                                                <input type="hidden" name="action" value="increase">
                                                <button type="submit" class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center border-0 text-white" style="width: 26px; height: 26px; background: var(--tc-crimson);" aria-label="Tambah jumlah">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4 fw-bold tc-mono">Rp {{ number_format($totalItem, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background-color: var(--tc-surface-muted);">
                                <td colspan="3" class="text-end fw-semibold py-3">Subtotal {{ $group['tenant_name'] ?? 'Gerai' }}</td>
                                <td class="text-end pe-4 fw-bold py-3 tc-mono text-maroon">Rp {{ number_format($subtotalTenant, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endforeach

        <div class="tc-sticky-bar">
            <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <small class="text-secondary d-block fw-semibold">Total Pembayaran</small>
                    <span class="tc-mono fw-bold fs-4 text-dark">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('cart.checkout') }}" class="btn btn-tc-primary px-5 py-2 text-decoration-none">
                    Lanjut ke Checkout <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
        <div style="height: 92px;"></div>
    @endif
</div>
@endsection
