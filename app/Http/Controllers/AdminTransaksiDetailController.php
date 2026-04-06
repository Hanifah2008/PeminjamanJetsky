<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;

class AdminTransaksiDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        //die('masuk');
        //dd($request->all());
        $alat_id = $request->alat_id;
        $transaksi_id = $request->transaksi_id;

        $td = TransaksiDetail::whereAlatId($alat_id)->whereTransaksiId($transaksi_id)->first();

        $transaksi = Transaksi::find($transaksi_id);
        
        if($td == null) {
        $data = [
            'alat_id' => $request->alat_id,
            'alat_name' => $request->alat_name,
            'transaksi_id' => $request->transaksi_id,
            'qty' => $request->qty,
            'harga' => $request->harga ?? 0,
            'subtotal' => $request->subtotal,
        ];
        TransaksiDetail::create($data);

        $dt = [
            'total' => $request->subtotal + $transaksi->total
        ];
        $transaksi->update($dt);
    } else {
        $data = [
            'qty' => $td->qty + $request->qty,
            'subtotal' => $request->subtotal + $td->subtotal,

        ];
        $td->update($data);

        $dt = [
            'total' => $request->subtotal + $transaksi->total
        ];
        $transaksi->update($dt);
    }
        return redirect('/admin/transaksi/' . $transaksi_id . '/edit');

    }

    public function printStruk($id)
{
    $transaksi = \App\Models\Transaksi::with('details')->findOrFail($id);
    $detail = $transaksi->details ?? [];

    // Ambil nilai dibayarkan dari query string (?dibayarkan=...)
    $dibayarkan = request('dibayarkan');
    $kembalian = null;

    if ($dibayarkan !== null && is_numeric($dibayarkan)) {
        $kembalian = $dibayarkan - $transaksi->total;
    }

    return view('admin.transaksi.print-struk', compact('transaksi', 'detail', 'dibayarkan', 'kembalian'));
}



    function delete()
    {
        $id = request('id');
        $td = TransaksiDetail::find($id);
        

        $transaksi = Transaksi::find($td->transaksi_id);
        $data = [
            'total' => $transaksi->total - $td->subtotal,
        ];
        $transaksi->update($data);

        $td->delete();
        return redirect()->back();
    }

    function done($id)
    {
        $transaksi = Transaksi::find($id);
        $data = [
            'status' => 'selesai'
        ];
        $transaksi->update($data);
        return redirect('/admin/transaksi');
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
    }
    
}
