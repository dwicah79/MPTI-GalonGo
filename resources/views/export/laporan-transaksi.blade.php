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

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            color: #007BFF;
        }

        .header p {
            margin: 0;
            font-size: 14px;
            color: #333;
        }

        h2 {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
        }

        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #444;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Galon Go</h1>
        <p>Laporan Transaksi dan Pengeluaran</p>
        <p>Periode: {{ $from }} - {{ $to }}</p>
    </div>

    {{-- Laporan Transaksi --}}
    <div class="section-title">Laporan Transaksi</div>
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
                <td><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- Laporan Pengeluaran --}}
    @if (!empty($expenses) && $expenses->count())
        <div class="section-title">Laporan Pengeluaran</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengeluaran</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $j => $out)
                    <tr>
                        <td>{{ $j + 1 }}</td>
                        <td>{{ $out->kurir->name ?? '-' }}</td>
                        <td>{{ $out->description }}</td>
                        <td>{{ $out->created_at->format('d/m/Y') ?? '-' }}</td>
                        <td>Rp{{ number_format($out->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                @php
                    $totalPengeluaran = $expenses->sum('price');
                @endphp
                <tr>
                    <td colspan="4" style="text-align: left;"><strong>Total:</strong></td>
                    <td><strong>Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    @endif

</body>

</html>
