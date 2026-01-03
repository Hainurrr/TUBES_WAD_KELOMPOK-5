<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan #{{ $order->id_order }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 14px; max-width: 400px; margin: 0 auto; padding: 20px; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .border-bottom { border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .d-flex { display: flex; justify-content: space-between; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    
    <div class="text-center mb-4">
        <h2 class="fw-bold mb-2">TELYUCAN</h2>
        <p class="mb-2">{{ $order->tenant->name }}</p>
        <p>{{ $order->tenant->address ?? 'Telkom University' }}</p>
    </div>

    <div class="border-bottom">
        <div class="d-flex mb-2">
            <span>No. Order</span>
            <span class="fw-bold">#{{ $order->id_order }}</span>
        </div>
        <div class="d-flex mb-2">
            <span>Tanggal</span>
            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="d-flex mb-2">
            <span>Pelanggan</span>
            <span>{{ $order->user->name }}</span>
        </div>
        <div class="d-flex mb-2">
            <span>Pembayaran</span>
            <span>QRIS / Tunai</span>
        </div>
    </div>

    <div class="border-bottom">
        @foreach($order->orderDetails as $detail)
        <div class="mb-2">
            <div class="fw-bold">{{ $detail->menu->name }}</div>
            <div class="d-flex">
                <span>{{ $detail->qty }} x {{ number_format($detail->menu->price, 0, ',', '.') }}</span>
                <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex fw-bold mb-4" style="font-size: 16px;">
        <span>TOTAL</span>
        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
    </div>

    <!-- QR Code untuk Verifikasi Pesanan (API: goqr.me) -->
    <div class="text-center mb-4">
        <p class="fw-bold mb-2">Scan QR untuk Verifikasi:</p>
        @php
            $qrData = "TELYUCAN|ORDER#" . $order->id_order . "|" . $order->created_at->format('Ymd') . "|Rp" . number_format($order->total_price, 0, ',', '.');
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=" . urlencode($qrData);
        @endphp
        <img src="{{ $qrUrl }}" alt="QR Code Order #{{ $order->id_order }}" style="width: 120px; height: 120px;">
        <p style="font-size: 10px; margin-top: 5px;">Order #{{ $order->id_order }}</p>
    </div>

    <div class="text-center border-bottom">
        <p class="fw-bold mb-2">Status: {{ strtoupper($order->status) }}</p>
        <p class="mb-2">Terima Kasih!</p>
        <p>Simpan struk ini sebagai bukti pembayaran.</p>
    </div>

    <div class="text-center no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #800000; color: white; border: none; border-radius: 5px; cursor: pointer;">Cetak Struk</button>
        <br><br>
        <a href="{{ route('orders.history') }}" style="color: #666; text-decoration: none;">Kembali</a>
    </div>

</body>
</html>
