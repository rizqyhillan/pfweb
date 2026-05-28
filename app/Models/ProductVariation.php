<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $table = 'product_variations';
    protected $fillable = ['product_id', 'nama_variasi', 'harga', 'stok'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
