<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    protected $table = 'barang_batch';
    protected $fillable = ['id_barang', 'id_supplier', 'no_batch', 'harga_beli', 'jumlah_masuk', 'sisa_stok', 'tanggal_masuk', 'tanggal_expired', 'keterangan'];
    protected $casts = ['harga_beli' => 'decimal:2', 'tanggal_masuk' => 'date', 'tanggal_expired' => 'date'];

    public function barang() { return $this->belongsTo(Product::class, 'id_barang'); }
    public function supplier() { return $this->belongsTo(Supplier::class, 'id_supplier'); }
    // Backward compat
    public function product() { return $this->barang(); }
}
