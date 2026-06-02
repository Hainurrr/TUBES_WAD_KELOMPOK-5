@extends('layouts.app')

@section('content')
<div class="container py-5">
    <a href="{{ route('home') }}" class="text-decoration-none text-secondary mb-3 d-inline-block hover-opacity">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
    </a>
    <h2 class="fw-bold mb-4">Keranjang Belanja</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(empty($cart) || count($cart) == 0)
        <div class="text-center py-5">
            <h4 class="text-secondary">Keranjang Anda kosong.</h4>
            <a href="{{ route('home') }}" class="btn btn-maroon mt-3">Mulai Pesan</a>
        </div>
    @else
            @foreach($cart as $tenantId => $group)
                <div class="card mb-4 shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-maroon">
                            <i class="bi bi-shop me-2"></i> {{ $group['tenant_name'] ?? 'Tenant #' . $tenantId }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col" class="ps-4">Menu</th>
                                        <th scope="col" class="text-end">Harga</th>
                                        <th scope="col" class="text-center">Qty</th>
                                        <th scope="col" class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subtotalTenant = 0; @endphp
                                    @foreach($group['items'] as $menuId => $item)
                                        @php 
                                            $totalItem = $item['price'] * $item['qty'];
                                            $subtotalTenant += $totalItem;
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    @if(isset($item['image']) && $item['image'])
                                                        <img src="{{ Str::startsWith($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}" 
                                                             class="rounded me-3 object-fit-cover" width="50" height="50" alt="Menu">
                                                    @endif
                                                    <span class="fw-bold">{{ $item['name'] }}</span>
                                                </div>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td class="text-center">{{ $item['qty'] }}</td>
                                            <td class="text-end pe-4 fw-bold">Rp {{ number_format($totalItem, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold py-3">Total Pesanan Tenant Ini:</td>
                                        <td class="text-end pe-4 fw-bold py-3 text-maroon">Rp {{ number_format($subtotalTenant, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="fixed-bottom bg-white border-top shadow-lg p-3">
                <div class="container d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-secondary d-block">Total Pembayaran</small>
                        <span class="fw-bold fs-4 text-maroon">Checkout Sekarang</span>
                    </div>
                    <a href="{{ route('cart.checkout') }}" class="btn btn-maroon btn-lg px-5 rounded-pill fw-bold">
                        Bayar & Pesan <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            {{-- Spacer untuk mencegah konten tertutup oleh footer fixed --}}
            <div style="height: 100px;"></div>
    @endif
</div>
@endsection
