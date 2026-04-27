<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Boarding extends Model
{
    protected $table = 'penitipan';
    protected $fillable = ['id_hewan', 'id_kamar', 'tanggal_masuk', 'tanggal_rencana_keluar', 'tanggal_keluar', 'catatan_titip', 'catatan_jemput', 'status', 'total_biaya'];
    protected $casts = ['total_biaya' => 'decimal:2', 'tanggal_masuk' => 'date', 'tanggal_rencana_keluar' => 'date', 'tanggal_keluar' => 'date'];

    public function hewan() { return $this->belongsTo(Pet::class, 'id_hewan'); }
    public function kamar() { return $this->belongsTo(Room::class, 'id_kamar'); }
    // Backward compat
    public function pet() { return $this->hewan(); }
    public function room() { return $this->kamar(); }
}
