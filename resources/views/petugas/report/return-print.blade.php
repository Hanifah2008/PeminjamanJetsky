<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengembalian</title>
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
            border-left: 4px solid #ffc107;
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
            background-color: #ffc107;
            color: #000;
        }

        th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 11px;
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
            <h1>LAPORAN PENGEMBALIAN ALAT</h1>
            <p>Tugaska Rental</p>
            <p>Tanggal Laporan: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        @if (request('durasi_jam') || request('return_status'))
            <div class="filter-info">
                <p><strong>Filter yang Digunakan:</strong></p>
                @if (request('durasi_jam'))
                    <p>Durasi Peminjaman: {{ request('durasi_jam') }} Jam</p>
                @endif
                @if (request('return_status'))
                    <p>Status Pengembalian: 
                        @if (request('return_status') === 'pending')
                            Pending
                        @elseif (request('return_status') === 'returned')
                            Dikembalikan
                        @elseif (request('return_status') === 'overdue')
                            Overdue
                        @endif
                    </p>
                @endif
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="8%">ID</th>
                    <th width="12%">Nama Penyewa</th>
                    <th width="10%">Durasi</th>
                    <th width="9%">Jam Kembali</th>
                    <th width="9%">Jam Sekarang</th>
                    <th width="12%">Status</th>
                    <th width="12%">Kondisi</th>
                    <th width="12%">Dikonfirmasi</th>
                    <th width="11%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($returns as $return)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>#{{ $return->id }}</td>
                        <td>{{ $return->user->name }}</td>
                        <td class="text-center">
                            @php
                                $durasiDisplay = '';
                                if($return->details->count() > 0) {
                                    $firstDuration = $return->details->first()->durasi_jam;
                                    $durasiDisplay = (int)$firstDuration . ' Jam';
                                }
                            @endphp
                            <strong>{{ $durasiDisplay ?: '-' }}</strong>
                        </td>
                        <td class="text-center"><strong>{{ $return->due_date ? $return->due_date->format('H:i') : '-' }}</strong></td>
                        <td class="text-center"><strong>{{ $printDate ? \Carbon\Carbon::createFromFormat('d-m-Y H:i', $printDate)->format('H:i') : now()->format('H:i') }}</strong></td>
                        <td class="text-center">
                            @if ($return->return_status === 'pending')
                                Pending
                            @elseif ($return->return_status === 'returned')
                                Dikembalikan
                            @elseif ($return->return_status === 'overdue')
                                Overdue
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($return->kondisi === 'baik')
                                Baik
                            @elseif ($return->kondisi === 'rusak_ringan')
                                Rusak Ringan
                            @elseif ($return->kondisi === 'rusak_berat')
                                Rusak Berat
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $return->returned_at ? $return->returned_at->format('d/m/Y H:i') : '-' }}</td>
                        <td style="font-size: 10px;">{{ Str::limit($return->return_notes ?? '-', 30) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data pengembalian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($returns->count() > 0)
            <div class="summary">
                <div class="summary-row">
                    <span>Total Pengembalian:</span>
                    <span>{{ $returns->count() }} transaksi</span>
                </div>
                <div class="summary-row">
                    <span>Dikembalikan:</span>
                    <span>{{ $returns->where('return_status', 'returned')->count() }} transaksi</span>
                </div>
                <div class="summary-row">
                    <span>Overdue:</span>
                    <span>{{ $returns->where('return_status', 'overdue')->count() }} transaksi</span>
                </div>
                <div class="summary-row">
                    <span>Pending:</span>
                    <span>{{ $returns->where('return_status', 'pending')->count() }} transaksi</span>
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
