<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\User;

class DashboardController extends Controller
{
    

    public function index()
    {
        // Ambil data alat dari database
        $alat = Alat::all(); // atau bisa dengan query lain sesuai kebutuhan
    
        // Kirim data alat ke view
        return view('dashboard', compact('alat'));
    }

}
