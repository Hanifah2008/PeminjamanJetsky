<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Overdue</title>
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
            border-left: 4px solid #dc3545;
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
            background-color: #dc3545;
            color: white;
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

        .danger-text {
            color: #dc3545;
            font-weight: bold;
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

        .warning-section {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin-top: 15px;
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
            <h1>LAPORAN PEMINJAMAN OVERDUE</h1>
            <p>Tugaska Rental</p>
            <p>Tanggal Laporan: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        @if (request('date_from') || request('date_to'))
            <div class="filter-info">
                <p><strong>Filter yang Digunakan:</strong></p>
                @if (request('date_from'))
                    <p>Dari Tanggal: {{ \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') }}</p>
                @endif
                @if (request('date_to'))
                    <p>Sampai Tanggal: {{ \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') }}</p>
                @endif
            </div>
        @endif

        @if ($overdues->count() > 0)
            <div class="warning-section">
                <p><strong>⚠️ PERHATIAN: Ada {{ $overdues->count() }} peminjaman yang OVERDUE!</strong></p>
                <p>Silakan hubungi penyewa untuk pengembalian alat segera.</p>
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="8%">ID</th>
                    <th width="15%">Nama Penyewa</th>
                    <th width="12%">No Telepon</th>
                    <th width="15%">Email</th>
                    <th width="12%">Tgl Due</th>
                    <th width="8%">Hari Overdue</th>
                    <th width="8%">Alat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($overdues as $overdue)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>#{{ $overdue->id }}</td>
                        <td>{{ $overdue->user->name }}</td>
                        <td>{{ $overdue->user->phone ?? '-' }}</td>
                        <td>{{ $overdue->user->email ?? '-' }}</td>
                        <td>{{ $overdue->tgl_kembali ? $overdue->tgl_kembali->format('d/m/Y') : '-' }}</td>
                        <td class="text-center danger-text">
                            @if ($overdue->tgl_kembali)
                                {{ now()->diffInDays($overdue->tgl_kembali) }} hari
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">{{ $overdue->details->count() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada peminjaman overdue</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($overdues->count() > 0)
            <div class="summary">
                <div class="summary-row">
                    <span>Total Overdue:</span>
                    <span class="danger-text">{{ $overdues->count() }} transaksi</span>
                </div>
                <div class="summary-row">
                    <span>Rata-rata Hari Overdue:</span>
                    <span class="danger-text">
                        @php
                            $avgDays = $overdues->reduce(function ($carry, $item) {
                                return $carry + ($item->tgl_kembali ? now()->diffInDays($item->tgl_kembali) : 0);
                            }, 0) / $overdues->count();
                        @endphp
                        {{ round($avgDays, 1) }} hari
                    </span>
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
