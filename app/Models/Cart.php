<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'keranjang';

    protected $fillable = [
        'id_user',
        'status',
    ];

    protected $attributes = [
        'status' => 'aktif',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'id_keranjang');
    }
}