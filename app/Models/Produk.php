<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'kategori_id', 'harga', 'stok', 'gambar'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getRatingAverage()
    {
        return $this->ratings()->avg('bintang') ?? 0;
    }

    public function getRatingCount()
    {
        return $this->ratings()->count();
    }

    // Method untuk mengurangi stok produk
    public function kurangiStok($jumlah)
    {
        // Pastikan stok cukup
        if ($this->stok >= $jumlah) {
            $this->stok -= $jumlah;
            $this->save(); // Simpan perubahan stok
        } else {
            throw new \Exception("Stok tidak cukup");
        }
    }
}
