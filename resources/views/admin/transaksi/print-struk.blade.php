<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            padding: 20px;
        }
        .text-center {
            text-align: center;
        }
        .border-top {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        table {
            width: 100%;
            font-size: 14px;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
    </style>
</head>
<body onload="window.print();">

    <div class="text-center">
        <h4>Hani Jestky Jogja</h4>
        <p>Yogyakarta</p>
        <p>{{ $transaksi->created_at->format('d M Y H:i') }}</p>
    </div>

    <div class="border-top"></div>

    <table>
        <thead>
            <tr>
                <th>Alat</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $item)
                <tr>
                    <td>{{ $item->alat_name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ format_rupiah($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="border-top"></div>

    

    <table>
    <tr>
        <td><strong>Total</strong></td>
        <td><strong>: Rp {{ format_rupiah($transaksi->total) }}</strong></td>
    </tr>

    @if ($dibayarkan)
        <tr>
            <td>Dibayar</td>
            <td>: Rp {{ format_rupiah($dibayarkan) }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td>: Rp {{ format_rupiah($kembalian) }}</td>
        </tr>
    @endif
</table>


    <div class="text-center mt-3">
        <p>Terima kasih telah berbelanja!</p>
    </div>

</body>
</html>
