<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'layanan';
    protected $fillable = ['nama_layanan', 'jenis_layanan', 'harga', 'durasi_menit', 'deskripsi', 'id_dokter', 'is_aktif'];
    protected $casts = ['harga' => 'decimal:2', 'is_aktif' => 'boolean'];

    public function getNameAttribute() { return $this->nama_layanan; }
    public function getPriceAttribute() { return $this->harga; }

    public function dokter() { return $this->belongsTo(User::class, 'id_dokter'); }
}
