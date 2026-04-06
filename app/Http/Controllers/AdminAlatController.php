<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Alat;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AdminAlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //die('masuk');
        $data = [
            'title'      => 'Manajemen Alat',
            'alat'   => Alat::paginate(10),
            'content'    => 'admin/alat/index'
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
            'title'      => 'Tambah Alat',
            'kategori'   =>  Kategori::get(),
            'content'    => 'admin/alat/create'
        ];
        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'name' => 'required',
            'kategori_id'  => 'required',
            'harga'  => 'required',
            'stok'  => 'required',
        ]);


        if($request->hasFile('gambar')){
            $gambar = $request->file('gambar');
            $file_name = time() . "_" . $gambar->getClientOriginalName();

            $storage = 'uploads/images/';
            $gambar->move($storage, $file_name);
            $data['gambar'] = $storage . $file_name;
        }else{
            $data['gambar'] =null;
        }

        Alat::create($data);
        Alert::success('Sukses', 'Data berhasil ditambahkan');
        return redirect()->back();
        
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
        $data =[
            'title'    => 'Edit Alat',
            'alat'   => Alat::find($id),
            'kategori' => Kategori::get(),
            'content'    => 'admin/alat/create'
        ];
        return view('admin.layouts.wrapper', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $alat = Alat::findOrFail($id);
        $data = $request->validate([
        'name'        => 'required',
        'kategori_id' => 'required',
        'harga'       => 'required',
        'stok'        => 'required',
    ]);

    // Cek apakah ada file gambar baru yang diunggah
    if ($request->hasFile('gambar')) {
        $gambar = $request->file('gambar');
        $file_name = time() . "_" . $gambar->getClientOriginalName();
        $storage = 'uploads/images/';

        // Pindahkan gambar ke folder public
        $gambar->move(public_path($storage), $file_name);

        // Simpan path gambar baru
        $data['gambar'] = $storage . $file_name;
    } else {
        // Gunakan gambar lama jika tidak ada gambar baru
        $data['gambar'] = $alat->gambar;
    }

    // Update data alat
    $alat->update($data);

    Alert::success('Sukses', 'Data berhasil diedit');
    return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $alat = Alat::find($id);
        if ($alat->gambar != null) {
            unlink($alat->gambar);
        }
        
        $alat->delete();
        Alert::success('Sukses', 'Data berhasil dihapus');
        return redirect()->back();
    }
}
