<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['nama', 'email', 'password', 'role', 'no_hp', 'alamat', 'is_aktif'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_aktif' => 'boolean'];
    }

    // Accessor agar Auth::user()->name tetap jalan
    public function getNameAttribute() { return $this->nama; }

    public function hewan() { return $this->hasMany(Pet::class, 'id_pemilik'); }
}
