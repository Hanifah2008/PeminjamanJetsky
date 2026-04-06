<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $guarded = [];

    protected $casts = [
        'qty' => 'integer',
        'harga' => 'integer',
        'harga_original' => 'integer',
        'harga_setelah_diskon' => 'integer',
        'diskon_persen' => 'integer',
        'durasi_jam' => 'float',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}
