<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'barang';
    protected $fillable = ['nama_barang', 'kategori', 'harga', 'stok', 'satuan', 'deskripsi', 'is_aktif'];
    protected $casts = ['harga' => 'decimal:2', 'is_aktif' => 'boolean'];

    public function getNameAttribute() { return $this->nama_barang; }
    public function getPriceAttribute() { return $this->harga; }
    public function getStockAttribute() { return $this->stok; }

    public function batches() { return $this->hasMany(ProductBatch::class, 'id_barang'); }
    public function kartuStok() { return $this->hasMany(StockCard::class, 'id_barang'); }
}
