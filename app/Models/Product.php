<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'barang';
    protected $fillable = ['nama_barang', 'kategori', 'harga', 'stok', 'satuan', 'deskripsi', 'is_aktif', 'image'];
    protected $casts = ['harga' => 'decimal:2', 'is_aktif' => 'boolean'];

    public function getNameAttribute()
    {
        return $this->nama_barang;
    }

    public function getPriceAttribute()
    {
        return $this->harga;
    }

    public function getStockAttribute()
    {
        return $this->stok;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        $firstImage = $this->relationLoaded('images')
            ? $this->images->first()
            : $this->images()->first();

        return $firstImage
            ? asset('storage/' . $firstImage->path)
            : asset('images/placeholder.png');
    }

    public function getImageUrlsAttribute()
    {
        if ($this->relationLoaded('images')) {
            $images = $this->images;
        } else {
            $images = $this->images()->get();
        }

        if ($images->isNotEmpty()) {
            return $images->map(fn ($image) => asset('storage/' . $image->path))->toArray();
        }

        return $this->image ? [asset('storage/' . $this->image)] : [];
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    public function batches()
    {
        return $this->hasMany(ProductBatch::class, 'id_barang');
    }

    public function kartuStok()
    {
        return $this->hasMany(StockCard::class, 'id_barang');
    }
}
