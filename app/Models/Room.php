<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'kamar';
    protected $fillable = ['nama_kamar', 'tipe', 'harga_per_hari', 'kapasitas', 'status', 'keterangan'];
    protected $casts = ['harga_per_hari' => 'decimal:2'];

    public function getNameAttribute() { return $this->nama_kamar; }
    public function getPricePerDayAttribute() { return $this->harga_per_hari; }
}
