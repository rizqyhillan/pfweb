<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaksi';
    protected $fillable = ['id_pelanggan', 'id_kasir', 'kode_transaksi', 'jenis', 'subtotal', 'diskon', 'total', 'jumlah_bayar', 'kembalian', 'metode_bayar', 'status', 'catatan', 'tanggal'];
    protected $casts = ['subtotal' => 'decimal:2', 'diskon' => 'decimal:2', 'total' => 'decimal:2', 'jumlah_bayar' => 'decimal:2', 'kembalian' => 'decimal:2', 'tanggal' => 'datetime'];

    // Default status when creating
    protected $attributes = [
        'status' => 'pending',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan');
    }
    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir');
    }
    public function barang()
    {
        return $this->hasMany(TransactionProduct::class, 'id_transaksi');
    }
    public function layanan()
    {
        return $this->hasMany(TransactionService::class, 'id_transaksi');
    }
    // Backward compat
    public function customer()
    {
        return $this->pelanggan();
    }
    public function cashier()
    {
        return $this->kasir();
    }
    public function products()
    {
        return $this->barang();
    }
    public function services()
    {
        return $this->layanan();
    }
}
