<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use SoftDeletes;

    protected $table = 'hewan';
    protected $fillable = [
    'id_pemilik',
    'nama_hewan',
    'jenis',
    'jenis_kelamin',
    'tanggal_lahir',
    'ras',
    'umur',
    'berat',
    'catatan',
    'foto',
    ];
    protected $casts = ['berat' => 'decimal:2'];

    // Accessor agar $pet->name tetap jalan di view
    public function getNameAttribute() { return $this->nama_hewan; }

    public function owner() { return $this->belongsTo(User::class, 'id_pemilik'); }
    public function rekamMedis() { return $this->hasMany(MedicalRecord::class, 'id_hewan'); }
    public function penitipan() { return $this->hasMany(Boarding::class, 'id_hewan'); }

    // Alias
    public function medicalRecords() { return $this->rekamMedis(); }
    public function boardings() { return $this->penitipan(); }

    public function bookingDokter()
    {
        return $this->hasMany(DoctorBooking::class, 'id_hewan');
    }
}
