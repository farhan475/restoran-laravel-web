<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = ['transaksi_id', 'menu_id', 'jumlah', 'harga_satuan', 'subtotal'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
