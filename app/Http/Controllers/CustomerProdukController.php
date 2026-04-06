<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;

class CustomerProdukController extends Controller
{
    // Tampilkan semua alat untuk dipinjam
    public function index(Request $request)
    {
        $kategoriId = $request->get('kategori');
        $search = $request->get('search');

        $query = Alat::with('kategori', 'ratings');

        // Filter kategori
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }

        // Search alat
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $alats = $query->paginate(12);
        $kategoris = Kategori::all();

        $data = [
            'content' => 'customer.belanja.index',
            'alats' => $alats,
            'kategoris' => $kategoris,
            'selectedKategori' => $kategoriId,
            'search' => $search,
        ];

        return view('customer.layouts.wrapper', $data);
    }

    // Tampilkan detail alat
    public function show($id)
    {
        $alat = Alat::with('kategori', 'ratings.user')->findOrFail($id);

        $data = [
            'content' => 'customer.belanja.show',
            'alat' => $alat,
        ];

        return view('customer.layouts.wrapper', $data);
    }

    // Konfirmasi Pinjaman - Tampilkan halaman checkout/konfirmasi
    public function tampilKonfirmasi($alatId, Request $request)
    {
        $alat = Alat::findOrFail($alatId);
        $qty = (int) $request->query('qty', 1);
        $durasi_jam = (float) $request->query('durasi_jam', 1);

        // Hitung harga dengan progressive pricing
        $multiplier = 1.0;
        if ($durasi_jam >= 3) {
            $multiplier = 0.78;
        } else if ($durasi_jam >= 2) {
            $multiplier = 1.21;
        }

        $harga_original = $alat->harga * $durasi_jam * $multiplier;
        $diskon_persen = $alat->diskon_persen ?? 0;
        $harga_setelah_diskon = $harga_original * (100 - $diskon_persen) / 100;
        $total_harga = $harga_setelah_diskon * $qty;

        $data = [
            'content' => 'customer.belanja.konfirmasi',
            'alat' => $alat,
            'qty' => $qty,
            'durasi_jam' => $durasi_jam,
            'harga_original' => $harga_original,
            'harga_setelah_diskon' => $harga_setelah_diskon,
            'diskon_persen' => $diskon_persen,
            'total_harga' => $total_harga,
        ];

        return view('customer.layouts.wrapper', $data);
    }

    // Proses Pinjaman - Buat transaksi
    public function prosesPinjaman(Request $request, $alatId)
    {
        $user = auth()->user();
        $alat = Alat::findOrFail($alatId);
        $qty = (int) $request->input('qty', 1);
        $durasi_jam = (float) $request->input('durasi_jam', 1);

        // Validasi durasi
        if ($durasi_jam < 0.5) {
            return back()->with('error', 'Durasi sewa minimal 0.5 jam (30 menit)!');
        }

        // Validasi stok
        if ($alat->stok < $qty) {
            return back()->with('error', 'Stok alat tidak cukup!');
        }

        // Hitung progressive pricing multiplier
        $multiplier = 1.0;
        if ($durasi_jam >= 3) {
            $multiplier = 0.78;
        } else if ($durasi_jam >= 2) {
            $multiplier = 1.21;
        }

        // Hitung harga original dengan progressive pricing
        $harga_original = $alat->harga * $durasi_jam * $multiplier;

        // Hitung harga setelah diskon promo
        $diskon_persen = $alat->diskon_persen ?? 0;
        $harga_setelah_diskon = $harga_original * (100 - $diskon_persen) / 100;

        // Total harga untuk semua qty
        $total_harga = $harga_setelah_diskon * $qty;

        // Buat Transaksi
        $transaksi = Transaksi::create([
            'user_id' => $user->id,
            'total' => (int) round($total_harga),
            'status' => 'selesai',
            'kasir_name' => null,
        ]);

        // Buat TransaksiDetail
        TransaksiDetail::create([
            'transaksi_id' => $transaksi->id,
            'alat_id' => $alatId,
            'qty' => $qty,
            'durasi_jam' => $durasi_jam,
            'harga' => (int) round($harga_original),
            'harga_original' => (int) round($harga_original),
            'harga_setelah_diskon' => (int) round($harga_setelah_diskon),
            'diskon_persen' => (int) $diskon_persen,
        ]);

        // Kurangi stok alat
        $alat->update(['stok' => $alat->stok - $qty]);

        return redirect()->route('customer.riwayat.show', $transaksi->id)
                       ->with('success', 'Peminjaman berhasil! Transaksi telah dibuat.');
    }

    // Sewa Langsung (Keep untuk backward compatibility)
    public function sewaLangsung(Request $request, $alatId)
    {
        $user = auth()->user();
        $alat = Alat::findOrFail($alatId);
        $qty = (int) $request->input('qty', 1);
        $durasi_jam = (float) $request->input('durasi_jam', 1);

        // Validasi durasi
        if ($durasi_jam < 0.5) {
            return back()->with('error', 'Durasi sewa minimal 0.5 jam (30 menit)!');
        }

        // Validasi stok
        if ($alat->stok < $qty) {
            return back()->with('error', 'Stok alat tidak cukup!');
        }

        // Hitung progressive pricing multiplier
        $multiplier = 1.0;
        if ($durasi_jam >= 3) {
            $multiplier = 0.78;  // Diskon 22% untuk 3 jam atau lebih
        } else if ($durasi_jam >= 2) {
            $multiplier = 1.21;  // Naik 21% untuk 2 jam
        }

        // Hitung harga original dengan progressive pricing
        $harga_original = $alat->harga * $durasi_jam * $multiplier;

        // Hitung harga setelah diskon promo
        $diskon_persen = $alat->diskon_persen ?? 0;
        $harga_setelah_diskon = $harga_original * (100 - $diskon_persen) / 100;

        // Total harga untuk semua qty
        $total_harga = $harga_setelah_diskon * $qty;

        // Buat Transaksi
        $transaksi = Transaksi::create([
            'user_id' => $user->id,
            'total' => (int) round($total_harga),
            'status' => 'selesai',  // Langsung selesai untuk sewa langsung
            'kasir_name' => null,  // Bisa diisi nanti oleh admin
        ]);

        // Buat TransaksiDetail
        TransaksiDetail::create([
            'transaksi_id' => $transaksi->id,
            'alat_id' => $alatId,
            'qty' => $qty,
            'durasi_jam' => $durasi_jam,
            'harga' => (int) round($harga_original),
            'harga_original' => (int) round($harga_original),
            'harga_setelah_diskon' => (int) round($harga_setelah_diskon),
            'diskon_persen' => (int) $diskon_persen,
        ]);

        // Kurangi stok alat
        $alat->update(['stok' => $alat->stok - $qty]);

        return redirect()->route('customer.riwayat.show', $transaksi->id)
                       ->with('success', 'Transaksi peminjaman berhasil dibuat!');
    }
}
