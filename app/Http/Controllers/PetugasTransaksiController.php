<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasTransaksiController extends Controller
{
    // Menampilkan list transaksi yang pending approval
    public function indexPending()
    {
        $transaksis = Transaksi::pendingApproval()
            ->with('user')
            ->with('details')
            ->latest()
            ->paginate(10);

        $data = [
            'content' => 'petugas.transaksi.pending-approval',
            'transaksis' => $transaksis,
            'pageTitle' => 'Data Pinjaman Menunggu Approval',
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Menampilkan history transaksi yang sudah diapprove/reject
    public function indexHistory()
    {
        $transaksis = Transaksi::whereIn('approval_status', ['approved', 'rejected'])
            ->with('user')
            ->with('approvedBy')
            ->latest()
            ->paginate(10);

        $data = [
            'content' => 'petugas.transaksi.approval-history',
            'transaksis' => $transaksis,
            'pageTitle' => 'Riwayat Approval Pinjaman',
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Menampilkan detail transaksi untuk approval
    public function show($id)
    {
        $transaksi = Transaksi::with('user', 'details.alat', 'approvedBy')->findOrFail($id);

        $data = [
            'content' => 'petugas.transaksi.show-approval',
            'transaksi' => $transaksi,
            'pageTitle' => 'Detail Pinjaman - Approval',
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Menyetujui pinjaman
    public function approve(Request $request, $id)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:500',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi sudah diproses sebelumnya!');
        }

        $transaksi->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
            'status' => 'selesai', // Otomatis ubah status transaksi
        ]);

        return redirect()->route('petugas.transaksi.pending-approval')
            ->with('success', 'Pinjaman berhasil disetujui!');
    }

    // Menolak pinjaman
    public function reject(Request $request, $id)
    {
        $request->validate([
            'approval_notes' => 'required|string|max:500',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi sudah diproses sebelumnya!');
        }

        $transaksi->update([
            'approval_status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
            'status' => 'pending', // Tetap pending
        ]);

        return redirect()->route('petugas.transaksi.pending-approval')
            ->with('success', 'Pinjaman berhasil ditolak!');
    }

    // Menampilkan semua transaksi dengan filter
    public function index(Request $request)
    {
        $query = Transaksi::with('user', 'approvedBy');

        // Filter berdasarkan approval status
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        // Filter berdasarkan transaksi status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search berdasarkan nama user
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        }

        $transaksis = $query->latest()->paginate(10);

        $data = [
            'content' => 'petugas.transaksi.index-new',
            'transaksis' => $transaksis,
            'pageTitle' => 'Manajemen Pinjaman & Approval',
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Dashboard Monitoring Peminjaman
    public function monitoring()
    {
        // Total & Status Statistics
        $totalTransaksi = Transaksi::count();
        $pendingApproval = Transaksi::pendingApproval()->count();
        $totalApproved = Transaksi::approved()->count();
        $totalRejected = Transaksi::rejected()->count();
        $totalSelesai = Transaksi::where('status', 'selesai')->count();
        $totalPending = Transaksi::where('status', 'pending')->count();

        // Top Customers (yang sering pinjam)
        $topCustomers = \App\Models\User::withCount('transaksis')
            ->orderBy('transaksis_count', 'desc')
            ->take(5)
            ->get();

        // Peminjaman aktif (pending approval & belum selesai)
        $activeLoans = Transaksi::where('approval_status', 'approved')
            ->where('status', 'pending')
            ->with('user', 'details.alat')
            ->latest()
            ->take(10)
            ->get();

        // Peminjaman hari ini
        $todayLoans = Transaksi::whereDate('created_at', \Carbon\Carbon::today())
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // Total nilai peminjaman
        $totalNilaiPeminjaman = Transaksi::where('status', 'selesai')->sum('total');
        $totalNilaiPending = Transaksi::where('status', 'pending')->sum('total');

        // Chart data - Approval status distribution
        $chartApprovalStatus = [
            'pending' => Transaksi::pendingApproval()->count(),
            'approved' => Transaksi::approved()->count(),
            'rejected' => Transaksi::rejected()->count(),
        ];

        // Chart data - Transaksi status distribution
        $chartTransaksiStatus = [
            'selesai' => Transaksi::where('status', 'selesai')->count(),
            'pending' => Transaksi::where('status', 'pending')->count(),
        ];

        // Chart data - Daily loans (7 hari terakhir)
        $dailyLoans = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $count = Transaksi::whereDate('created_at', $date)->count();
            $dailyLoans[$date->format('d-M')] = $count;
        }

        $data = [
            'content' => 'petugas.monitoring.index',
            'pageTitle' => 'Dashboard Monitoring Peminjaman',
            'totalTransaksi' => $totalTransaksi,
            'pendingApproval' => $pendingApproval,
            'totalApproved' => $totalApproved,
            'totalRejected' => $totalRejected,
            'totalSelesai' => $totalSelesai,
            'totalPending' => $totalPending,
            'topCustomers' => $topCustomers,
            'activeLoans' => $activeLoans,
            'todayLoans' => $todayLoans,
            'totalNilaiPeminjaman' => $totalNilaiPeminjaman,
            'totalNilaiPending' => $totalNilaiPending,
            'chartApprovalStatus' => json_encode($chartApprovalStatus),
            'chartTransaksiStatus' => json_encode($chartTransaksiStatus),
            'chartDailyLoans' => json_encode($dailyLoans),
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Detail monitoring transaksi aktif
    public function monitoringDetail($id)
    {
        $transaksi = Transaksi::with('user', 'details.alat', 'approvedBy')->findOrFail($id);

        $data = [
            'content' => 'petugas.monitoring.detail',
            'transaksi' => $transaksi,
            'pageTitle' => 'Detail Monitoring Peminjaman',
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Dashboard Return Monitoring
    public function monitoringReturn()
    {
        // Statistics
        $totalApproved = Transaksi::approved()->count();
        $pendingReturn = Transaksi::pendingReturn()->count();
        $overdue = Transaksi::overdue()->count();
        $returned = Transaksi::returned()->count();

        // Peminjaman dengan return pending
        $pendingReturns = Transaksi::pendingReturn()
            ->with('user', 'details.alat')
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        // Peminjaman overdue (belum dikembalikan & sudah lewat due date)
        $overdueLoans = Transaksi::overdue()
            ->with('user', 'details.alat')
            ->orderBy('due_date', 'asc')
            ->take(10)
            ->get();

        // Peminjaman yang sudah dikembalikan
        $returnedLoans = Transaksi::returned()
            ->with('user', 'details.alat')
            ->latest('returned_at')
            ->paginate(10);

        // Upcoming returns (akan jatuh tempo dalam 3 hari ke depan)
        $today = \Carbon\Carbon::today();
        $upcomingReturns = Transaksi::where('return_status', 'pending')
            ->where('due_date', '>', $today)
            ->where('due_date', '<=', $today->copy()->addDays(3))
            ->with('user', 'details.alat')
            ->orderBy('due_date', 'asc')
            ->take(10)
            ->get();

        // Chart data - Return status distribution
        $chartReturnStatus = [
            'pending' => Transaksi::pendingReturn()->count(),
            'returned' => Transaksi::returned()->count(),
            'overdue' => Transaksi::overdue()->count(),
        ];

        // Chart data - Last 7 days returns
        $dailyReturns = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $count = Transaksi::returned()
                ->whereDate('returned_at', $date)
                ->count();
            $dailyReturns[$date->format('d-M')] = $count;
        }

        $data = [
            'content' => 'petugas.monitoring.return',
            'pageTitle' => 'Monitoring Pengembalian Peminjaman',
            'totalApproved' => $totalApproved,
            'pendingReturn' => $pendingReturn,
            'overdue' => $overdue,
            'returned' => $returned,
            'pendingReturns' => $pendingReturns,
            'overdueLoans' => $overdueLoans,
            'returnedLoans' => $returnedLoans,
            'upcomingReturns' => $upcomingReturns,
            'chartReturnStatus' => json_encode($chartReturnStatus),
            'chartDailyReturns' => json_encode($dailyReturns),
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Halaman konfirmasi pengembalian
    public function returnDetail($id)
    {
        $transaksi = Transaksi::with('user', 'details.alat')->findOrFail($id);

        $data = [
            'content' => 'petugas.monitoring.return-detail',
            'transaksi' => $transaksi,
            'pageTitle' => 'Konfirmasi Pengembalian Peminjaman',
        ];

        return view('petugas.layouts.wrapper', $data);
    }

    // Proses pengembalian
    public function processReturn(Request $request, $id)
    {
        $request->validate([
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'return_notes' => 'nullable|string|max:500',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->return_status !== 'pending') {
            return redirect()->back()->with('error', 'Peminjaman sudah dikonfirmasi pengembaliannya!');
        }

        $transaksi->update([
            'return_status' => 'returned',
            'returned_at' => now(),
            'kondisi' => $request->kondisi,
            'return_notes' => $request->return_notes,
        ]);

        return redirect()->route('petugas.monitoring.return')
            ->with('success', 'Pengembalian peminjaman berhasil dikonfirmasi!');
    }

    // Mark sebagai overdue
    public function markOverdue(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->return_status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi sudah diproses sebelumnya!');
        }

        $transaksi->update([
            'return_status' => 'overdue',
        ]);

        return redirect()->back()
            ->with('success', 'Status peminjaman berubah menjadi Overdue!');
    }

    // Cetak Struk Peminjaman (format Indomaret)
    public function printReceipt($id)
    {
        $transaksi = Transaksi::with('user', 'details.alat', 'approvedBy')->findOrFail($id);

        $data = [
            'transaksi' => $transaksi,
        ];

        return view('petugas.transaksi.receipt', $data);
    }
}