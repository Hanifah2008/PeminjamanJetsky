<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'approved_at' => 'datetime',
        'due_date' => 'date',
        'returned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope untuk mendapatkan transaksi yang pending approval
    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    // Scope untuk mendapatkan transaksi yang sudah disetujui
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    // Scope untuk mendapatkan transaksi yang ditolak
    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    // Scope untuk return tracking
    public function scopePendingReturn($query)
    {
        return $query->where('return_status', 'pending')->where('approval_status', 'approved');
    }

    public function scopeOverdue($query)
    {
        return $query->where('return_status', 'overdue');
    }

    public function scopeReturned($query)
    {
        return $query->where('return_status', 'returned');
    }
}
