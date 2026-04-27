<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $table = 'rekam_medis';
    protected $fillable = ['id_hewan', 'id_dokter', 'diagnosa', 'tindakan', 'resep', 'berat_saat_itu', 'catatan', 'tanggal'];
    protected $casts = ['berat_saat_itu' => 'decimal:2', 'tanggal' => 'datetime'];

    public function hewan() { return $this->belongsTo(Pet::class, 'id_hewan'); }
    public function dokter() { return $this->belongsTo(User::class, 'id_dokter'); }
    // Backward compat
    public function pet() { return $this->hewan(); }
    public function doctor() { return $this->dokter(); }
}
