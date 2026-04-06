<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $alatCount = Alat::count();
        $transaksiCount = Transaksi::count();
        $kategoriCount = Kategori::count();
        $userCount = User::count();

        // Penjualan Hari Ini
        $penjualanHariIni = Transaksi::whereDate('created_at', Carbon::today())
            ->sum('total');

        // Penjualan Bulan Ini
        $penjualanBulanIni = Transaksi::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        // Data Penjualan per Hari untuk Chart (30 hari terakhir)
        $penjualanPerHari = [];
        $hariLabels = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            $hariLabels[] = Carbon::parse($tanggal)->format('d/m');
            
            $total = Transaksi::whereDate('created_at', $tanggal)
                ->sum('total');
            
            $penjualanPerHari[] = $total;
        }

        // Data Penjualan per Bulan untuk Chart (12 bulan terakhir)
        $penjualanPerBulan = [];
        $bulanLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $bulanLabels[] = $bulan->format('M Y');
            
            $total = Transaksi::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->sum('total');
            
            $penjualanPerBulan[] = $total;
        }

        // Transaksi terbaru
        $transaksiTerbaru = Transaksi::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $data = [
            'content' => 'admin.dashboard.index',
            'alatCount' => $alatCount,
            'transaksiCount' => $transaksiCount,
            'kategoriCount' => $kategoriCount,
            'userCount' => $userCount,
            'penjualanHariIni' => $penjualanHariIni,
            'penjualanBulanIni' => $penjualanBulanIni,
            'hariLabels' => json_encode($hariLabels),
            'penjualanPerHari' => json_encode($penjualanPerHari),
            'bulanLabels' => json_encode($bulanLabels),
            'penjualanPerBulan' => json_encode($penjualanPerBulan),
            'transaksiTerbaru' => $transaksiTerbaru,
        ];

        return view('admin.layouts.wrapper', $data);
    }
}
