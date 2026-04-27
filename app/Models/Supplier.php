<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $fillable = ['nama_supplier', 'kontak', 'email', 'alamat'];

    public function getNameAttribute() { return $this->nama_supplier; }
}
