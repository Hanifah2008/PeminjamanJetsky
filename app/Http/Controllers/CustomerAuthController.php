<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    // Tampilkan halaman login customer
    public function loginIndex()
    {
        return view('customer.auth.login');
    }

    // Tampilkan halaman register customer
    public function registerIndex()
    {
        return view('customer.auth.register');
    }

    // Proses login
    public function doLogin(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi'
        ]);

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            
            // Cek role
            if (auth()->user()->role === 'customer') {
                return redirect('/customer/dashboard')->with('success', 'Selamat datang!');
            }
            
            // Jika admin, arahkan ke admin dashboard
            if (auth()->user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }
        }

        return back()->with('loginError', 'Email atau Password salah');
    }

    // Proses register
    public function doRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai'
        ]);

        // Buat user baru dengan role customer
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer'
        ]);

        return redirect('/customer/login')->with('success', 'Registrasi berhasil! Silahkan login');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/customer/login')->with('success', 'Berhasil logout');
    }
}