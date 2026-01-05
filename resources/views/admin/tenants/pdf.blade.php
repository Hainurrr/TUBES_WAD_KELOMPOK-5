<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Tenant - TelyuCan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            color: black;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #800000; /* Maroon branding kept for title */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <h2>Laporan Data Tenant TelyuCan</h2>
    <p style="text-align: center; font-size: 12px; margin-bottom: 20px;">
        Dicetak pada: {{ date('d F Y, H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Tenant</th>
                <th>Pemilik (Asli)</th>
                <th>Akun User</th>
                <th>Telepon</th>
                <th>Kota</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tenants as $index => $tenant)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $tenant->name }}</td>
                <td>{{ $tenant->owner_name }}</td>
                <td>{{ $tenant->user_account_name ?? '-' }}</td>
                <td>{{ $tenant->phone }}</td>
                <td>{{ $tenant->city }}</td>
                <td>{{ $tenant->is_active ? 'Aktif' : 'Nonaktif' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Admin TelyuCan</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
