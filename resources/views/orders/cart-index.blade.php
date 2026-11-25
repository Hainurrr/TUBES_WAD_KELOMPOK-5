@extends('layouts.app')

@section('title', 'Keranjang Belanja — TelyuCan')

@section('content')
<x-page-banner
    title="Keranjang Belanja"
    subtitle="Periksa pesanan dari setiap gerai sebelum melanjutkan ke pembayaran."
    :back-url="route('menus.index')"
    back-label="Lanjut Pilih Menu"
/>

<section class="tc-operate-sheet">
    <div class="container">
        <x-flash-alerts />

        @if(empty($cart) || count($cart) == 0)
            <x-empty-state
                icon="bi-bag-x"
                heading-tag="h2"
                title="Keranjang belanjamu masih kosong"
                description="Tambahkan makanan atau minuman dari gerai kantin yang buka untuk mulai memesan."
                :action-url="route('menus.index')"
                action-label="Jelajahi Menu"
            />
        @else
            @php $grandTotal = 0; @endphp
            @foreach($cart as $tenantId => $group)
                @php
                    $subtotalTenant = 0;
                    foreach ($group['items'] as $calcItem) {
                        $subtotalTenant += $calcItem['price'] * $calcItem['qty'];
                    }
                    $grandTotal += $subtotalTenant;
                @endphp
                <div class="tc-table-wrap mb-4">
                    <div class="px-3 px-md-4 py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2 bg-white">
                        <h2 class="tc-display h6 fw-bold mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-shop text-maroon"></i>
                            <span>{{ $group['tenant_name'] ?? 'Gerai #' . $tenantId }}</span>
                        </h2>
                        <span class="tc-badge tc-badge-neutral">Ambil di Konter</span>
                    </div>

                    <div class="d-md-none">
                        @foreach($group['items'] as $menuId => $item)
                            @php $totalItem = $item['price'] * $item['qty']; @endphp
                            <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex align-items-start gap-3 mb-2">
                                    @if(!empty($item['image']))
                                        <img src="{{ \Illuminate\Support\Str::startsWith($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}"
                                             class="rounded-3 object-fit-cover flex-shrink-0" width="56" height="56" alt="{{ $item['name'] }}">
                                    @endif
                                    <div class="min-w-0 flex-grow-1">
                                        <div class="fw-bold text-dark lh-sm mb-1">{{ $item['name'] }}</div>
                                        <div class="small text-secondary tc-mono">Rp {{ number_format($item['price'], 0, ',', '.') }} / porsi</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2 pt-1">
                                    <x-qty-stepper :menu-id="$menuId" :qty="$item['qty']" :item-name="$item['name']" :editable="false" />
                                    <span class="fw-bold tc-mono text-dark">Rp {{ number_format($totalItem, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                        <div class="px-3 py-3 border-top d-flex justify-content-between align-items-center" style="background-color: var(--tc-surface-muted);">
                            <span class="small fw-semibold text-secondary">Subtotal {{ $group['tenant_name'] ?? 'Gerai' }}</span>
                            <span class="fw-bold tc-mono text-maroon">Rp {{ number_format($subtotalTenant, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="table-responsive d-none d-md-block">
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
                                    @php $totalItem = $item['price'] * $item['qty']; @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                @if(!empty($item['image']))
                                                    <img src="{{ \Illuminate\Support\Str::startsWith($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}"
                                                         class="rounded-3 object-fit-cover flex-shrink-0" width="52" height="52" alt="{{ $item['name'] }}">
                                                @endif
                                                <span class="fw-bold">{{ $item['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end tc-mono small">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <x-qty-stepper :menu-id="$menuId" :qty="$item['qty']" :item-name="$item['name']" :editable="false" />
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
                <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 gap-sm-3">
                    <div class="d-flex d-sm-block justify-content-between align-items-baseline">
                        <small class="text-secondary d-block fw-semibold">Total Pembayaran</small>
                        <span class="tc-mono fw-bold fs-4 text-dark">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('cart.checkout') }}" class="btn btn-tc-primary px-4 px-sm-5 py-2 text-decoration-none text-center">
                        Lanjut ke Checkout <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            <div style="height: 112px;"></div>
        @endif
    </div>
</section>
@endsection
