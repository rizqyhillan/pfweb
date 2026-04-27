<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockCard extends Model
{
    protected $table = 'kartu_stok';
    protected $fillable = ['id_barang', 'id_batch', 'tanggal', 'jenis_mutasi', 'jumlah', 'saldo', 'harga_satuan', 'referensi', 'keterangan'];
    protected $casts = ['harga_satuan' => 'decimal:2', 'tanggal' => 'datetime'];

    public function barang() { return $this->belongsTo(Product::class, 'id_barang'); }
    public function batch() { return $this->belongsTo(ProductBatch::class, 'id_batch'); }
    // Backward compat
    public function product() { return $this->barang(); }
}
