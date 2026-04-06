<?php

namespace App\Http\Controllers;

// 1. WAJIB PANGGIL MODEL ALAT
use App\Models\Alat; 
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index()
    {
        // 2. AMBIL DATA DARI DB
        // 'Alat::all()' artinya mengambil semua baris dari tabel 'alats'
        $units = Alat::all(); 

        // 3. KIRIM KE VIEW
        // 'landing' adalah nama file blade kamu (misal: landing.blade.php)
        // 'units' adalah nama variabel yang akan dipanggil di @foreach
        return view('landing', compact('units'));
    }
}