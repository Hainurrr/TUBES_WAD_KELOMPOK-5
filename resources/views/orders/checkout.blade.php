@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <a href="{{ route('cart.index') }}" class="text-decoration-none text-secondary mb-3 d-inline-block hover-opacity">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Keranjang
            </a>
            <h2 class="fw-bold mb-4">Menu Pembayaran</h2>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('order.store') }}" method="POST" id="checkoutForm">
                @csrf
                
                <!-- Order Summary -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                        
                        @php $globalTotal = 0; @endphp
                        @foreach($cart as $tenantId => $group)
                            <div class="mb-3 border-bottom pb-3 last-no-border">
                                <h6 class="fw-bold text-maroon mb-2">{{ $group['tenant_name'] }}</h6>
                                <ul class="list-unstyled mb-0">
                                    @foreach($group['items'] as $item)
                                        @php 
                                            $subtotal = $item['price'] * $item['qty'];
                                            $globalTotal += $subtotal;
                                        @endphp
                                        <li class="d-flex justify-content-between mb-1">
                                            <span>{{ $item['qty'] }}x {{ $item['name'] }}</span>
                                            <span class="text-secondary">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <span class="fw-bold fs-5">Total Pembayaran</span>
                            <span class="fw-bold fs-4 text-maroon">Rp {{ number_format($globalTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Metode Pembayaran</h5>
                        
                        <div class="form-check p-3 border rounded-3 mb-2 bg-light">
                            <input class="form-check-input" type="radio" name="payment_method" id="qris" value="qris" checked>
                            <label class="form-check-label d-flex align-items-center w-100" for="qris">
                                <i class="bi bi-qr-code-scan fs-4 me-3 text-maroon"></i>
                                <div>
                                    <span class="d-block fw-bold">QRIS</span>
                                    <small class="text-secondary">Gopay, OVO, Dana, ShopeePay</small>
                                </div>
                            </label>
                        </div>

                        <div class="form-check p-3 border rounded-3 bg-light">
                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash">
                            <label class="form-check-label d-flex align-items-center w-100" for="cash">
                                <i class="bi bi-cash-stack fs-4 me-3 text-success"></i>
                                <div>
                                    <span class="d-block fw-bold">Tunai</span>
                                    <small class="text-secondary">Bayar di kasir</small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <button type="button" class="btn btn-maroon w-100 rounded-pill py-3 fw-bold fs-5 shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmationModal">
                    Pesan
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 p-3">
            <div class="modal-body">
                <h5 class="fw-bold mb-3">Pastiin pesananmu udah sesuai, ya</h5>
                <p class="text-secondary mb-4">
                    Abis dikonfirmasi, pesanan kamu gak bisa batal. Resto bakal langsung buat, biar cepat diantar.
                </p>
                
                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-outline-success flex-grow-1 rounded-pill fw-bold py-2" data-bs-dismiss="modal">
                        Cek kembali
                    </button>
                    <button type="button" onclick="submitOrder()" class="btn btn-success flex-grow-1 rounded-pill fw-bold py-2">
                        Pesan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function submitOrder() {
        document.getElementById('checkoutForm').submit();
    }
</script>

<style>
    .last-no-border:last-child {
        border-bottom: 0 !important;
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }
</style>
@endsection
