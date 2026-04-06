<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Alat;
use Illuminate\Http\Request;

class CustomerRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'bintang' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        try {
            // Cek apakah user sudah rating alat ini
            $existingRating = Rating::where('user_id', auth()->id())
                                    ->where('alat_id', $request->alat_id)
                                    ->first();

            if ($existingRating) {
                // Update rating
                $existingRating->update([
                    'bintang' => $request->bintang,
                    'komentar' => $request->komentar,
                ]);
                $message = 'Rating berhasil diperbarui!';
            } else {
                // Buat rating baru
                Rating::create([
                    'user_id' => auth()->id(),
                    'alat_id' => $request->alat_id,
                    'bintang' => $request->bintang,
                    'komentar' => $request->komentar,
                ]);
                $message = 'Rating berhasil ditambahkan!';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
