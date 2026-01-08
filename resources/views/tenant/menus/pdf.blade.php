<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu - {{ $tenant->name ?? 'TelyuCan' }}</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #800000; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #800000; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; color: #333; }
        .price { text-align: right; }
        .stock { text-align: center; }
        .available { color: green; font-weight: bold; }
        .unavailable { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $tenant->name ?? 'Nama Tenant' }}</h1>
        <p>{{ $tenant->description ?? 'Deskripsi Tenant' }}</p>
        <p>Laporan Daftar Menu - {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $index => $menu)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $menu->name }}</strong><br>
                        <small style="color: #666;">{{ $menu->description }}</small>
                    </td>
                    <td>{{ $menu->category->name ?? '-' }}</td>
                    <td class="price">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td class="stock">{{ $menu->stock }}</td>
                    <td class="stock">
                        @if($menu->stock > 0)
                            <span class="available">Tersedia</span>
                        @else
                            <span class="unavailable">Habis</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>
