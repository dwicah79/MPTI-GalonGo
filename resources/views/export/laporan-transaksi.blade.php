<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2>Laporan Transaksi</h2>
    <p>Periode: {{ $from }} - {{ $to }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Customer</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $trx->customer->name ?? '-' }}</td>
                    <td>{{ $trx->item->name ?? '-' }}</td>
                    <td>{{ $trx->item->type ?? '-' }}</td>
                    <td>{{ $trx->jumlah }}</td>
                    <td>{{ $trx->created_at->format('d/m/Y') }}</td>
                    <td>Rp{{ number_format($trx->harga_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            @php
                $total = $data->sum('harga_total');
            @endphp
            <tr>
                <td colspan="6" style="text-align: left;"><strong>Total:</strong></td>
                <td>Rp{{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
