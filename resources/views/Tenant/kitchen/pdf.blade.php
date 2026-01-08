<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - {{ $tenant->name }}</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #800000; }
        .header p { margin: 5px 0; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        
        .summary { text-align: right; margin-top: 20px; font-size: 18px; font-weight: bold; }
        .footer { margin-top: 50px; text-align: right; font-size: 12px; }
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <h2>{{ $tenant->name }}</h2>
        <p>Periode: {{ request('start_date') ?? 'Awal' }} s/d {{ request('end_date') ?? 'Sekarang' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Menu Item</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    {{ $order->created_at->format('d/m/Y H:i') }}
                    @php
                        $dateKey = $order->created_at->format('Y-m-d');
                    @endphp
                    @if(isset($holidays[$dateKey]))
                        <br>
                        <span style="color: #0dcaf0; font-size: 10px;">
                            (Libur: {{ $holidays[$dateKey] }})
                        </span>
                    @endif
                </td>
                <td>
                    @foreach($order->orderDetails as $detail)
                        {{ $detail->menu->name }} (x{{ $detail->qty }})<br>
                    @endforeach
                </td>
                <td class="text-end">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        Total Pendapatan: Rp {{ number_format($totalRevenue, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Bandung, {{ date('d F Y') }}</p>
        <br><br><br>
        <p>( {{ Auth::user()->name }} )</p>
        <p>Tenant Manager</p>
    </div>

</body>
</html>
