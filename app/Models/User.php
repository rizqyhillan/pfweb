<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['nama', 'email', 'password', 'role', 'no_hp', 'alamat', 'is_aktif'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_aktif' => 'boolean'];
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
}
