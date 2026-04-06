<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        th { background: #f0f0f0; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h3 style="text-align: center;">Struk Peminjaman</h3>
    <p><strong>ID Peminjaman:</strong> {{ $transaksi->id }}</p>
    <p><strong>Tanggal:</strong> {{ $transaksi->created_at->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Alat</th>
                <th>Qty</th>
                <th>Tarif</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi_detail as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->alat_name }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-right"><strong>Total: Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</strong></p>
    <p class="text-right"><strong>Dibayarkan: Rp. {{ number_format($transaksi->dibayarkan, 0, ',', '.') }}</strong></p>
    <p class="text-right"><strong>Kembalian: Rp. {{ number_format($transaksi->kembalian, 0, ',', '.') }}</strong></p>

    <p style="text-align: center; margin-top: 20px;">Terima kasih telah menyewa alat kami!</p>
</body>
</html>
