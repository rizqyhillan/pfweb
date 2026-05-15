<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $table = 'jadwal_dokter';
    protected $fillable = ['id_dokter', 'hari', 'jam_mulai', 'jam_selesai', 'kuota', 'is_aktif'];
    protected $casts = ['is_aktif' => 'boolean', 'jam_mulai' => 'datetime:H:i', 'jam_selesai' => 'datetime:H:i'];

    public function dokter() { return $this->belongsTo(User::class, 'id_dokter'); }
}
