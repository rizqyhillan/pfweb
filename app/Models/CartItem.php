<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'keranjang_items';

    protected $fillable = [
        'id_keranjang',
        'id_barang',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'id_keranjang');
    }

    public function barang()
    {
        return $this->belongsTo(Product::class, 'id_barang');
    }
}