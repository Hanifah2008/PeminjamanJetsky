<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Approval</title>
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
            border-left: 4px solid #28a745;
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
            background-color: #28a745;
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
            <h1>LAPORAN APPROVAL PEMINJAMAN</h1>
            <p>Tugaska Rental</p>
            <p>Tanggal Laporan: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        @if (request('date_from') || request('date_to') || request('approval_status'))
            <div class="filter-info">
                <p><strong>Filter yang Digunakan:</strong></p>
                @if (request('date_from'))
                    <p>Dari Tanggal: {{ \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') }}</p>
                @endif
                @if (request('date_to'))
                    <p>Sampai Tanggal: {{ \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') }}</p>
                @endif
                @if (request('approval_status'))
                    <p>Status Approval: 
                        @if (request('approval_status') === 'pending')
                            Pending
                        @elseif (request('approval_status') === 'approved')
                            Disetujui
                        @elseif (request('approval_status') === 'rejected')
                            Ditolak
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
                    <th width="15%">Nama Penyewa</th>
                    <th width="8%">Alat</th>
                    <th width="12%">Tgl Permohonan</th>
                    <th width="10%">Status</th>
                    <th width="15%">Disetujui Oleh</th>
                    <th width="12%">Tgl Approval</th>
                    <th width="15%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($approvals as $approval)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>#{{ $approval->id }}</td>
                        <td>{{ $approval->user->name }}</td>
                        <td class="text-center">{{ $approval->details->count() }}</td>
                        <td>{{ $approval->created_at ? $approval->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td class="text-center">
                            @if ($approval->approval_status === 'pending')
                                Pending
                            @elseif ($approval->approval_status === 'approved')
                                Disetujui
                            @elseif ($approval->approval_status === 'rejected')
                                Ditolak
                            @endif
                        </td>
                        <td>{{ $approval->approvedBy?->name ?? '-' }}</td>
                        <td>{{ $approval->approved_at ? $approval->approved_at->format('d/m/Y H:i') : '-' }}</td>
                        <td style="font-size: 10px;">{{ Str::limit($approval->approval_notes ?? '-', 30) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data approval</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($approvals->count() > 0)
            <div class="summary">
                <div class="summary-row">
                    <span>Total Approval:</span>
                    <span>{{ $approvals->count() }} transaksi</span>
                </div>
                <div class="summary-row">
                    <span>Disetujui:</span>
                    <span>{{ $approvals->where('approval_status', 'approved')->count() }} transaksi</span>
                </div>
                <div class="summary-row">
                    <span>Ditolak:</span>
                    <span>{{ $approvals->where('approval_status', 'rejected')->count() }} transaksi</span>
                </div>
                <div class="summary-row">
                    <span>Pending:</span>
                    <span>{{ $approvals->where('approval_status', 'pending')->count() }} transaksi</span>
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
