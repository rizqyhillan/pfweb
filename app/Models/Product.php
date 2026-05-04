<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'barang';
    protected $fillable = ['nama_barang', 'kategori', 'harga', 'stok', 'satuan', 'deskripsi', 'is_aktif', 'image'];
    protected $casts = ['harga' => 'decimal:2', 'is_aktif' => 'boolean'];

    public function getNameAttribute() { return $this->nama_barang; }
    public function getPriceAttribute() { return $this->harga; }
    public function getStockAttribute() { return $this->stok; }

    /**
     * Accessor: returns full URL to the product image, or a placeholder.
     */
    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/placeholder.png');
    }

    public function batches() { return $this->hasMany(ProductBatch::class, 'id_barang'); }
    public function kartuStok() { return $this->hasMany(StockCard::class, 'id_barang'); }
}
