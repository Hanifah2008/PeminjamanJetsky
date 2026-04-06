<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Peminjaman</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.2;
            color: #000;
            background: #fff;
        }

        .receipt {
            max-width: 80mm;
            margin: 0 auto;
            padding: 10px;
            background: white;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .header .line {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header .title {
            font-size: 12px;
            font-weight: bold;
            margin: 2px 0;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .separator-solid {
            border-top: 1px solid #000;
            margin: 8px 0;
        }

        .info-section {
            font-size: 11px;
            margin-bottom: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            font-size: 11px;
        }

        .label {
            width: 45%;
            text-align: left;
        }

        .value {
            width: 55%;
            text-align: right;
            word-break: break-word;
        }

        .items-header {
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
            margin-bottom: 3px;
            font-weight: bold;
            font-size: 11px;
        }

        .items-header .col {
            display: inline-block;
        }

        .item-row {
            font-size: 10px;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .item-row .no {
            display: inline-block;
            width: 4%;
            text-align: center;
        }

        .item-row .nama {
            display: inline-block;
            width: 35%;
            word-break: break-word;
        }

        .item-row .qty {
            display: inline-block;
            width: 10%;
            text-align: center;
        }

        .item-row .harga {
            display: inline-block;
            width: 18%;
            text-align: right;
        }

        .item-row .subtotal {
            display: inline-block;
            width: 28%;
            text-align: right;
        }

        .summary-section {
            margin: 8px 0;
            font-size: 11px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 12px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 3px 0;
        }

        .footer {
            text-align: center;
            margin-top: 8px;
            font-size: 10px;
            line-height: 1.4;
        }

        .footer-note {
            margin-top: 6px;
            font-size: 9px;
            border-top: 1px dashed #000;
            padding-top: 6px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .receipt {
                width: 80mm;
                padding: 5px;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="line">================================</div>
            <div class="title">TUGASKA RENTAL CENTER</div>
            <div class="title">STRUK PEMINJAMAN ALAT</div>
            <div class="line">================================</div>
        </div>

        <!-- Info Transaksi -->
        <div class="info-section">
            <div class="info-row">
                <span class="label">No. Transaksi</span>
                <span class="value">#{{ $transaksi->id }}</span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal</span>
                <span class="value">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($transaksi->approvedBy)
            <div class="info-row">
                <span class="label">Petugas</span>
                <span class="value">{{ $transaksi->approvedBy->name }}</span>
            </div>
            @endif
        </div>

        <div class="separator"></div>

        <!-- Data Penyewa -->
        <div class="info-section">
            <div class="info-row">
                <span class="label">Nama Penyewa</span>
                <span class="value">{{ $transaksi->user->name }}</span>
            </div>
            @if($transaksi->user->phone)
            <div class="info-row">
                <span class="label">Telp</span>
                <span class="value">{{ $transaksi->user->phone }}</span>
            </div>
            @endif
        </div>

        <div class="separator"></div>

        <!-- Daftar Item -->
        <div class="items-header">
            <span class="no">NO</span>
            <span class="nama">ITEM</span>
            <span class="qty">QTY</span>
            <span class="harga">HARGA</span>
            <span class="subtotal">TOTAL</span>
        </div>

        @forelse($transaksi->details as $detail)
        <div class="item-row">
            <span class="no">{{ $loop->iteration }}</span>
            <span class="nama">{{ Str::limit($detail->alat->name, 12, '') }}</span>
            <span class="qty">{{ $detail->qty }}</span>
            <span class="harga">{{ number_format($detail->harga, 0, ',', '.') }}</span>
            <span class="subtotal">{{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</span>
        </div>
        @empty
        <div class="item-row">
            <span style="text-align: center; width: 100%;">Tidak ada item</span>
        </div>
        @endforelse

        <div class="separator-solid"></div>

        <!-- Summary -->
        <div class="summary-section">
            @if($transaksi->details->sum('durasi_jam') > 0)
            <div class="summary-row">
                <span>Durasi Peminjaman</span>
                <span>{{ $transaksi->details->sum('durasi_jam') }} Jam</span>
            </div>
            @endif

            <div class="summary-row">
                <span>Jumlah Item</span>
                <span>{{ $transaksi->details->count() }} pcs</span>
            </div>

            <div class="summary-row total">
                <span>TOTAL PEMBAYARAN</span>
                <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
            </div>

            @if($transaksi->due_date)
            <div class="summary-row">
                <span>Berlaku Sampai</span>
                <span>{{ $transaksi->due_date->format('d/m/Y') }}</span>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="line">================================</div>
            <div style="margin: 6px 0;">Terima kasih atas peminjaman Anda</div>
            <div>Kami tunggu pengembalian tepat waktu</div>
            <div style="margin-top: 4px; font-size: 9px;">
                <strong>Denda Keterlambatan :</strong>
                <br />
                <span>Rp 10.000,- per hari</span>
            </div>
            <div class="line" style="margin-top: 6px;">================================</div>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <span>Dicetak: {{ now()->format('d/m/Y H:i:s') }}</span>
            <br />
            <span>Struk ini adalah bukti peminjaman yang sah</span>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
