<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            margin: 2px 0;
        }

        .filter-info {
            margin-bottom: 15px;
            background-color: #f5f5f5;
            padding: 10px;
            border-left: 4px solid #007bff;
        }

        .filter-info p {
            margin: 3px 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background-color: #007bff;
            color: white;
        }

        th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 12px;
        }

        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 20px;
            border-top: 2px solid #000;
            padding-top: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }

        .footer p {
            margin: 2px 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                padding: 10px;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN PEMINJAMAN ALAT</h1>
            <p>Tugaska Rental</p>
            <p>Tanggal Laporan: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        @if (request('date_from') || request('date_to') || request('status'))
            <div class="filter-info">
                <p><strong>Filter yang Digunakan:</strong></p>
                @if (request('date_from'))
                    <p>Dari Tanggal: {{ \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') }}</p>
                @endif
                @if (request('date_to'))
                    <p>Sampai Tanggal: {{ \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') }}</p>
                @endif
                @if (request('status'))
                    <p>Status: {{ ucfirst(request('status')) }}</p>
                @endif
            </div>
        @endif

        <!-- Laporan Detail Peminjaman -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">ID Peminjaman</th>
                    <th width="20%">Nama Penyewa</th>
                    <th width="8%">Total Alat</th>
                    <th width="15%">Total Biaya</th>
                    <th width="15%">Tanggal Pinjam</th>
                    <th width="15%">Tanggal Kembali</th>
                    <th width="12%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($loans as $loan)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>#{{ $loan->id }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td class="text-center">{{ $loan->details->count() }}</td>
                        <td class="text-right">Rp. {{ number_format($loan->total, 0, ',', '.') }}</td>
                        <td>{{ $loan->created_at ? $loan->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $loan->tgl_kembali ? $loan->tgl_kembali->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">{{ ucfirst($loan->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data peminjaman</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Laporan Breakdown per Durasi Jam -->
        @if (!empty($loansByDuration))
        <div style="margin-top: 25px; page-break-before: auto;">
            <h3 style="font-size: 14px; font-weight: bold; margin-bottom: 10px; border-bottom: 2px solid #000; padding-bottom: 5px;">
                BREAKDOWN PEMINJAMAN PER DURASI JAM
            </h3>

            @foreach($loansByDuration as $duration => $items)
            <div style="margin-top: 12px; padding: 8px; border: 1px solid #ccc; background-color: #f0f0f0;">
                <strong style="font-size: 12px;">DURASI {{ $duration }} JAM(S)</strong>
            </div>
            
            <table style="margin-top: 5px;">
                <thead>
                    <tr style="background-color: #e8e8e8;">
                        <th width="5%">No</th>
                        <th width="10%">Transaksi ID</th>
                        <th width="18%">Penyewa</th>
                        <th width="20%">Alat</th>
                        <th width="8%">Qty</th>
                        <th width="12%">Harga</th>
                        <th width="15%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>#{{ $item['transaksi']->id }}</td>
                        <td>{{ $item['transaksi']->user->name }}</td>
                        <td>{{ $item['detail']->alat->name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $item['detail']->qty }}</td>
                        <td class="text-right">Rp. {{ number_format($item['detail']->harga, 0, ',', '.') }}</td>
                        <td class="text-right">Rp. {{ number_format($item['detail']->qty * $item['detail']->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if (isset($durationStats[$duration]))
            <div style="margin-top: 8px; background-color: #f9f9f9; padding: 6px; font-size: 11px;">
                <div style="display: flex; justify-content: space-between; margin: 2px 0;">
                    <span><strong>Total Item {{ $duration }} Jam:</strong></span>
                    <span>{{ $durationStats[$duration]['items'] }} pcs</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin: 2px 0;">
                    <span><strong>Total Transaksi:</strong></span>
                    <span>{{ $durationStats[$duration]['count'] }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin: 2px 0; font-weight: bold; border-top: 1px dashed #999; padding-top: 3px;">
                    <span>Nilai Total {{ $duration }} Jam:</span>
                    <span>Rp. {{ number_format($durationStats[$duration]['total'], 0, ',', '.') }}</span>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        @if ($loans->count() > 0)
            <div class="summary">
                <div class="summary-row">
                    <span>Total Transaksi Peminjaman:</span>
                    <span>{{ $loans->count() }} transaksi</span>
                </div>
                @if (!empty($loansByDuration))
                    <div class="summary-row">
                        <span>Total Durasi Jam:</span>
                        <span>{{ count($loansByDuration) }} kategori durasi</span>
                    </div>
                @endif
                <div class="summary-row">
                    <span>Total Nilai Peminjaman:</span>
                    <span>Rp. {{ number_format($loans->sum('total'), 0, ',', '.') }}</span>
                </div>
            </div>
        @endif

        <div class="footer">
            <p>Dicetak oleh: {{ auth()->user()->name }}</p>
            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
