<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $table = 'hewan';
    protected $fillable = ['id_pemilik', 'nama_hewan', 'jenis', 'ras', 'umur', 'berat', 'catatan'];
    protected $casts = ['berat' => 'decimal:2'];

    // Accessor agar $pet->name tetap jalan di view
    public function getNameAttribute() { return $this->nama_hewan; }

    public function owner() { return $this->belongsTo(User::class, 'id_pemilik'); }
    public function rekamMedis() { return $this->hasMany(MedicalRecord::class, 'id_hewan'); }
}
