<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class PetugasReportController extends Controller
{
    // Dashboard Laporan
    public function index()
    {
        $totalTransaksi = Transaksi::count();
        $totalApproved = Transaksi::approved()->count();
        $totalRejected = Transaksi::rejected()->count();
        $totalReturned = Transaksi::returned()->count();
        $overdue = Transaksi::overdue()->count();

        $data = [
            'content' => 'petugas.report.index',
            'pageTitle' => 'Dashboard Laporan',
            'totalTransaksi' => $totalTransaksi,
            'totalApproved' => $totalApproved,
            'totalRejected' => $totalRejected,
            'totalReturned' => $totalReturned,
            'overdue' => $overdue,
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Laporan Peminjaman
    public function loanReport(Request $request)
    {
        $query = Transaksi::with('user', 'details.alat', 'approvedBy');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $loans = $query->latest()->paginate(20);

        $data = [
            'content' => 'petugas.report.loan-report',
            'pageTitle' => 'Laporan Peminjaman',
            'loans' => $loans,
            'filters' => $request->all(),
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Print Laporan Peminjaman
    public function printLoanReport(Request $request)
    {
        $query = Transaksi::with('user', 'details.alat', 'approvedBy');

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $loans = $query->latest()->get();

        // Breakdown berdasarkan durasi jam
        $loansByDuration = [];
        $durationStats = [];
        
        foreach ($loans as $loan) {
            foreach ($loan->details as $detail) {
                $duration = (int)$detail->durasi_jam;
                
                if (!isset($loansByDuration[$duration])) {
                    $loansByDuration[$duration] = [];
                }
                $loansByDuration[$duration][] = [
                    'transaksi' => $loan,
                    'detail' => $detail,
                ];
                
                if (!isset($durationStats[$duration])) {
                    $durationStats[$duration] = [
                        'count' => 0,
                        'total' => 0,
                        'items' => 0,
                    ];
                }
                $durationStats[$duration]['count']++;
                $durationStats[$duration]['total'] += $detail->qty * $detail->harga;
                $durationStats[$duration]['items'] += $detail->qty;
            }
        }
        
        ksort($loansByDuration);

        $totalValue = $loans->sum('total');
        $dataFrom = $request->date_from ?? '-';
        $dataTo = $request->date_to ?? '-';

        $data = [
            'loans' => $loans,
            'loansByDuration' => $loansByDuration,
            'durationStats' => $durationStats,
            'totalValue' => $totalValue,
            'dataFrom' => $dataFrom,
            'dataTo' => $dataTo,
            'printDate' => now()->format('d-m-Y H:i'),
        ];

        return view('petugas.report.loan-print', $data);
    }

    // Laporan Approval
    public function approvalReport(Request $request)
    {
        $query = Transaksi::with('user', 'approvedBy');

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('approved_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('approved_at', '<=', $request->date_to);
        }

        $approvals = $query->latest('approved_at')->paginate(20);

        $data = [
            'content' => 'petugas.report.approval-report',
            'pageTitle' => 'Laporan Approval Peminjaman',
            'approvals' => $approvals,
            'filters' => $request->all(),
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Print Laporan Approval
    public function printApprovalReport(Request $request)
    {
        $query = Transaksi::with('user', 'approvedBy');

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('approved_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('approved_at', '<=', $request->date_to);
        }

        $approvals = $query->latest('approved_at')->get();

        $data = [
            'approvals' => $approvals,
            'printDate' => now()->format('d-m-Y H:i'),
        ];

        return view('petugas.report.approval-print', $data);
    }

    // Laporan Pengembalian
    public function returnReport(Request $request)
    {
        $query = Transaksi::with('user', 'details.alat');

        if ($request->filled('return_status')) {
            $query->where('return_status', $request->return_status);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('durasi_jam')) {
            $query->whereHas('details', function ($q) {
                $q->where('durasi_jam', (int)request('durasi_jam'));
            });
        }

        $returns = $query->latest('returned_at')->paginate(20);

        $data = [
            'content' => 'petugas.report.return-report',
            'pageTitle' => 'Laporan Pengembalian Peminjaman',
            'returns' => $returns,
            'filters' => $request->all(),
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Print Laporan Pengembalian
    public function printReturnReport(Request $request)
    {
        $query = Transaksi::with('user', 'details.alat');

        if ($request->filled('return_status')) {
            $query->where('return_status', $request->return_status);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('durasi_jam')) {
            $query->whereHas('details', function ($q) {
                $q->where('durasi_jam', (int)request('durasi_jam'));
            });
        }

        $returns = $query->latest('returned_at')->get();

        $data = [
            'returns' => $returns,
            'printDate' => now()->format('d-m-Y H:i'),
        ];

        return view('petugas.report.return-print', $data);
    }

    // Laporan Overdue
    public function overdueReport(Request $request)
    {
        $query = Transaksi::overdue()->with('user', 'details.alat');

        if ($request->filled('date_from')) {
            $query->whereDate('tgl_kembali', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tgl_kembali', '<=', $request->date_to);
        }

        $overdues = $query->latest('tgl_kembali')->paginate(20);

        $data = [
            'content' => 'petugas.report.overdue-report',
            'pageTitle' => 'Laporan Peminjaman Overdue',
            'overdues' => $overdues,
            'filters' => $request->all(),
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Print Laporan Overdue
    public function printOverdueReport(Request $request)
    {
        $query = Transaksi::overdue()->with('user', 'details.alat');

        if ($request->filled('date_from')) {
            $query->whereDate('tgl_kembali', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tgl_kembali', '<=', $request->date_to);
        }

        $overdues = $query->latest('tgl_kembali')->get();

        $totalOverdue = $overdues->count();
        $totalValue = $overdues->sum('total');

        $data = [
            'overdues' => $overdues,
            'totalOverdue' => $totalOverdue,
            'totalValue' => $totalValue,
            'printDate' => now()->format('d-m-Y H:i'),
        ];

        return view('petugas.report.overdue-print', $data);
    }
}
