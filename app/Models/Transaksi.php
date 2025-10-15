<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['meja_id', 'pelanggan_id', 'waiter_id', 'total', 'dibayar', 'status'];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }
    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiter_id');
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}