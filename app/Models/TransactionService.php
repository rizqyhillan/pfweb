<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TransactionService extends Model
{
    protected $table = 'transaksi_layanan';
    protected $fillable = ['id_transaksi', 'id_layanan', 'jumlah', 'harga_satuan', 'subtotal', 'catatan'];
    protected $casts = ['harga_satuan' => 'decimal:2', 'subtotal' => 'decimal:2'];

    public function transaksi() { return $this->belongsTo(Transaction::class, 'id_transaksi'); }
    public function layanan() { return $this->belongsTo(Service::class, 'id_layanan'); }
    // Backward compat
    public function transaction() { return $this->transaksi(); }
    public function service() { return $this->layanan(); }
}
