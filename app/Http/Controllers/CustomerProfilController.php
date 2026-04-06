<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerProfilController extends Controller
{
    // Tampilkan profil customer
    public function index()
    {
        $user = auth()->user();
        
        // Ambil statistik
        $totalBelanja = Transaksi::where('user_id', $user->id)->count();
        $totalTransaksi = Transaksi::where('user_id', $user->id)->sum('total');
        $transaksiSelesai = Transaksi::where('user_id', $user->id)
                                     ->where('status', 'selesai')
                                     ->count();

        $data = [
            'content' => 'customer.profil.index',
            'user' => $user,
            'totalBelanja' => $totalBelanja,
            'totalTransaksi' => $totalTransaksi,
            'transaksiSelesai' => $transaksiSelesai,
        ];

        return view('customer.layouts.wrapper', $data);
    }

    // Tampilkan form edit profil
    public function edit()
    {
        $user = auth()->user();

        $data = [
            'content' => 'customer.profil.edit',
            'user' => $user,
        ];

        return view('customer.layouts.wrapper', $data);
    }

    // Update profil
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'profile_picture.image' => 'File harus berupa gambar',
            'profile_picture.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'profile_picture.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Upload foto profil
        if ($request->hasFile('profile_picture')) {
            // Hapus foto lama jika ada
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Upload foto baru
            $file = $request->file('profile_picture');
            $filename = 'profiles/' . time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public', $filename);
            $validated['profile_picture'] = 'storage/' . $filename;
        }

        $user->update($validated);

        return redirect()->route('customer.profil.index')
                       ->with('success', 'Profil berhasil diperbarui');
    }
}
