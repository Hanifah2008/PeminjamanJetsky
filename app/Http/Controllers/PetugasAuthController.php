<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PetugasAuthController extends Controller
{
    // Tampilkan halaman login petugas
    public function loginIndex()
    {
        return view('petugas.auth.login');
    }

    // Proses login petugas
    public function doLogin(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($data)) {
            $request->session()->regenerate();

            if (auth()->user()->role === 'petugas') {
                return redirect('/petugas/dashboard')->with('success', 'Selamat datang Petugas!');
            }

            Auth::logout();
            return redirect('/petugas/login')->with('loginError', 'Gunakan akun petugas untuk login');
        }

        return back()->with('loginError', 'Email atau Password salah');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/petugas/login');
    }

    // 🔹 HALAMAN PROFIL PETUGAS
    public function profil()
    {
        $petugas = Auth::user();
        return view('petugas.profil', compact('petugas'));
    }

    // 🔹 UPDATE PROFIL
    public function updateProfil(Request $request)
    {
        $petugas = Auth::user();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $petugas->name = $request->name;
        $petugas->email = $request->email;

        if ($request->password) {
            $petugas->password = bcrypt($request->password);
        }

        $petugas->save();

        return back()->with('success', 'Profil berhasil diupdate');
    }
}