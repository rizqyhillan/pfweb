<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['nama', 'email', 'password', 'role', 'no_hp', 'alamat', 'is_aktif', 'foto'];
    protected $hidden = ['password', 'remember_token'];
    protected $appends = ['foto_url'];
    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_aktif' => 'boolean'];
    }

    protected static function booted()
    {
        static::deleting(function ($user) {
            $user->hewan()->delete();
        });
    }

    public function getFotoUrlAttribute()
    {
        if (!$this->foto) {
            return asset('admin-assets/img/avatars/1.png');
        }

        if (filter_var($this->foto, FILTER_VALIDATE_URL)) {
            return $this->foto;
        }

        return asset('storage/' . $this->foto);
    }

    // Accessor agar Auth::user()->name tetap jalan
    public function getNameAttribute() { return $this->nama; }

    // Relationships
    public function hewan()       { return $this->hasMany(Pet::class, 'id_pemilik'); }
    public function rekamMedis()   { return $this->hasMany(MedicalRecord::class, 'id_dokter'); }
    public function jadwalDokter() { return $this->hasMany(DoctorSchedule::class, 'id_dokter'); }
    public function transaksiKasir()    { return $this->hasMany(Transaction::class, 'id_kasir'); }
    public function transaksiPelanggan() { return $this->hasMany(Transaction::class, 'id_pelanggan'); }
    public function penitipan()    { return $this->hasManyThrough(Boarding::class, Pet::class, 'id_pemilik', 'id_hewan'); }

    // Alias — English names
    public function pets()            { return $this->hewan(); }
    public function medicalRecords()  { return $this->rekamMedis(); }
    public function schedules()       { return $this->jadwalDokter(); }
    public function transactions()    { return $this->transaksiKasir(); }
    public function transactionsAsCustomer() { return $this->transaksiPelanggan(); }
    public function transactionsAsCashier()  { return $this->transaksiKasir(); }
    public function boardings()       { return $this->penitipan(); }

    public function bookingDokterSebagaiDokter()
    {
    return $this->hasMany(DoctorBooking::class, 'id_dokter');
    }

    public function bookingDokterSebagaiPelanggan()
    {
        return $this->hasManyThrough(
            DoctorBooking::class,
            Pet::class,
            'id_pemilik',
            'id_hewan'
        );
    }
}
