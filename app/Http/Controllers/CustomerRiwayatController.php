<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;

class CustomerRiwayatController extends Controller
{
// Tampilkan riwayat peminjaman customer
        public function index(Request $request)
        {
            $status = $request->get('status');
            
            $query = Transaksi::where('user_id', auth()->id())
                              ->with('details.alat')
                          ->latest();

        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }

        $riwayats = $query->paginate(10);

        $data = [
            'content' => 'customer.riwayat.index',
            'riwayats' => $riwayats,
            'status' => $status,
        ];

        return view('customer.layouts.wrapper', $data);
    }

    // Tampilkan detail riwayat peminjaman
    public function show($id)
    {
        $transaksi = Transaksi::with('details.alat')
                              ->where('user_id', auth()->id())
                              ->findOrFail($id);

        $data = [
            'content' => 'customer.riwayat.show',
            'transaksi' => $transaksi,
        ];

        return view('customer.layouts.wrapper', $data);
    }
}
