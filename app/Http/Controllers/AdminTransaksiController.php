<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = [
            'title'      => 'Manajemen Transaksi',
            'transaksi'   => Transaksi::OrderBy('created_at', 'DESC')->paginate(10),
            'content'    => 'admin/transaksi/index'
        ];
        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data = [
            'user_id'  => auth()->user()->id,
            'kasir_name'  => auth()->user()->name,
            'total'       => 0

        ];

        $transaksi = Transaksi::create($data);
        return redirect('/admin/transaksi/' . $transaksi->id . '/edit');
        
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $alat = Alat::get();

        $alat_id = request('alat_id');
        $a_detail = Alat::find($alat_id);

        $transaksi_detail = TransaksiDetail::whereTransaksiId($id)->get();

        $act = request('act');
$qty = request('qty');
if ($act == 'min') {
    if ($qty <= 1) {
        $qty = 1;
    } else {
        $qty = $qty - 1;
    }
} else {
    $qty = $qty + 1;
}


$subtotal = $a_detail ? ($qty * $a_detail->harga) : 0;

$transaksi = Transaksi::find($id);

$dibayarkan = request('dibayarkan');

$kembalian = $dibayarkan - $transaksi->total;


        $data = [
            'title'      => 'Tambah Transaksi',
            'alat'     => $alat,
            'a_detail'   => $a_detail,
            'qty'        => $qty,
            'subtotal'   => $subtotal,
            'transaksi_detail'   => $transaksi_detail,
            'transaksi'  => $transaksi,
            'kembalian'  => $kembalian,
            'content'    => 'admin/transaksi/create'
        ];
        return view('admin.layouts.wrapper', $data);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $transaksi = Transaksi::find($id);
        $transaksi->delete();
        
        return redirect()->back();
    }

    

    
}




