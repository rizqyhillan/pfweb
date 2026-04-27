<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TransactionProduct extends Model
{
    protected $table = 'transaksi_barang';
    protected $fillable = ['id_transaksi', 'id_barang', 'jumlah', 'harga_satuan', 'subtotal'];
    protected $casts = ['harga_satuan' => 'decimal:2', 'subtotal' => 'decimal:2'];

    public function transaksi() { return $this->belongsTo(Transaction::class, 'id_transaksi'); }
    public function barang() { return $this->belongsTo(Product::class, 'id_barang'); }
    // Backward compat
    public function transaction() { return $this->transaksi(); }
    public function product() { return $this->barang(); }
}
