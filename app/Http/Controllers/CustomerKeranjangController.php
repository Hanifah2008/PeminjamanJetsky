<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Alat;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;

class CustomerKeranjangController extends Controller
{
    // Tampilkan keranjang
    public function index()
    {
        $user = auth()->user();
        $keranjangs = Keranjang::where('user_id', $user->id)
                               ->with('alat')
                               ->get();

        $totalItem = $keranjangs->sum('qty');
        $totalHarga = $keranjangs->sum(function($item) {
            // Gunakan harga_setelah_diskon jika ada, atau harga
            $price = $item->harga_setelah_diskon ?? $item->harga;
            return $price * $item->qty;
        });

        $data = [
            'content' => 'customer.keranjang.index',
            'keranjangs' => $keranjangs,
            'totalItem' => $totalItem,
            'totalHarga' => $totalHarga,
        ];

        return view('customer.layouts.wrapper', $data);
    }

    // Tambah ke keranjang (dari halaman pinjam alat)
    public function add(Request $request, $alatId)
    {
        $user = auth()->user();
        $alat = Alat::findOrFail($alatId);
        $qty = $request->input('qty', 1);
        $durasi_jam = $request->input('durasi_jam') ?: $request->input('durasi_custom', 1);
        $durasi_jam = floatval($durasi_jam);

        // Validasi durasi
        if ($durasi_jam < 0.5) {
            return back()->with('error', 'Durasi sewa minimal 0.5 jam (30 menit)!');
        }

        // Hitung progressive pricing multiplier
        $multiplier = 1.0;
        if ($durasi_jam >= 3) {
            $multiplier = 0.9;  // Diskon 10% untuk 3 jam atau lebih
        } else if ($durasi_jam >= 2) {
            $multiplier = 1.2;  // Naik 20% untuk 2 jam
        }

        // Hitung harga original dengan progressive pricing
        $harga_original = $alat->harga * $durasi_jam * $multiplier;

        // Hitung harga setelah diskon promo
        $diskon_persen = $alat->diskon_persen ?? 0;
        $harga_setelah_diskon = $harga_original * (100 - $diskon_persen) / 100;

        // Cek apakah alat sudah di keranjang dengan durasi yang sama
        $keranjang = Keranjang::where('user_id', $user->id)
                             ->where('alat_id', $alatId)
                             ->where('durasi_jam', $durasi_jam)
                             ->first();

        if ($keranjang) {
            // Update qty jika sudah ada dengan durasi yang sama
            $keranjang->qty += $qty;
            $keranjang->save();
        } else {
            // Tambah alat baru ke keranjang
            Keranjang::create([
                'user_id' => $user->id,
                'alat_id' => $alatId,
                'qty' => $qty,
                'durasi_jam' => $durasi_jam,
                'harga' => $harga_setelah_diskon,
                'harga_original' => $harga_original,
                'diskon_persen' => $diskon_persen,
                'harga_setelah_diskon' => $harga_setelah_diskon,
            ]);
        }

        return redirect()->route('customer.keranjang.index')
                       ->with('success', 'Alat ditambahkan ke keranjang peminjaman');
    }

    // Update qty di keranjang
    public function update(Request $request, $id)
    {
        $keranjang = Keranjang::where('id', $id)
                             ->where('user_id', auth()->id())
                             ->firstOrFail();

        $qty = $request->input('qty', 1);

        if ($qty > 0) {
            $keranjang->qty = $qty;
            $keranjang->save();
        }

        return back()->with('success', 'Keranjang diperbarui');
    }

    // Hapus dari keranjang
    public function delete($id)
    {
        $keranjang = Keranjang::where('id', $id)
                             ->where('user_id', auth()->id())
                             ->firstOrFail();

        $keranjang->delete();

        return back()->with('success', 'Alat dihapus dari keranjang');
    }

    // Tampilkan halaman checkout
    public function checkout()
    {
        $user = auth()->user();
        $keranjangs = Keranjang::where('user_id', $user->id)
                               ->with('alat')
                               ->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('customer.keranjang.index')
                           ->with('error', 'Keranjang kosong');
        }

        $totalHarga = $keranjangs->sum(function($item) {
            return $item->harga * $item->qty;
        });

        $data = [
            'content' => 'customer.keranjang.checkout',
            'user' => $user,
            'keranjangs' => $keranjangs,
            'totalHarga' => $totalHarga,
            'totalItem' => $keranjangs->sum('qty'),
        ];

        return view('customer.layouts.wrapper', $data);
    }

    // Proses checkout/peminjaman
    public function proses(Request $request)
    {
        $user = auth()->user();
        $keranjangs = Keranjang::where('user_id', $user->id)
                               ->with('alat')
                               ->get();

        if ($keranjangs->isEmpty()) {
            return redirect()->route('customer.keranjang.index')
                           ->with('error', 'Keranjang kosong');
        }

        // Hitung total
        $totalHarga = $keranjangs->sum(function($item) {
            return $item->harga * $item->qty;
        });

        // Buat transaksi
        $transaksi = Transaksi::create([
            'user_id' => $user->id,
            'total' => $totalHarga,
            'kasir_name' => 'Customer Online',
            'status' => 'pending',
        ]);

        // Buat detail transaksi
        foreach ($keranjangs as $item) {
            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'alat_id' => $item->alat_id,
                'alat_name' => $item->alat->name ?? 'Alat Dihapus',
                'qty' => $item->qty,
                'durasi_jam' => $item->durasi_jam,
                'harga' => $item->harga_original,
                'harga_original' => $item->harga_original,
                'diskon_persen' => $item->diskon_persen,
                'harga_setelah_diskon' => $item->harga_setelah_diskon,
            ]);
        }

        // Hapus dari keranjang
        Keranjang::where('user_id', $user->id)->delete();

        return redirect()->route('customer.riwayat.show', $transaksi->id)
                       ->with('success', 'Peminjaman berhasil dibuat! Silahkan tunggu konfirmasi dari admin.');
    }
}
