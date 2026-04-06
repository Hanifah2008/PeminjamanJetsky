<?php

namespace App\Http\Controllers;

use App\Models\Alat; // Pastikan model Alat sudah ada
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama dengan data produk
     */
    public function index()
    {
        // Mengambil semua data dari tabel alats
        // Jika ingin spesifik jetski saja, gunakan: Alat::where('kategori_id', 1)->get();
        $units = Alat::all(); 

        // Mengirim data ke view 'welcome' (sesuaikan dengan nama file blade kamu)
        return view('welcome', compact('units'));
    }
}