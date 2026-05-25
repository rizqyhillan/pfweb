<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grooming extends Model
{
    protected $fillable = ['id_hewan', 'id_paket', 'id_transaksi', 'tanggal_grooming', 'waktu_grooming', 'status', 'total_biaya', 'catatan_grooming'];

    protected $casts = [
        'total_biaya' => 'decimal:2',
        'tanggal_grooming' => 'date',
        'waktu_grooming' => 'datetime:H:i',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    public function hewan()
    {
        return $this->belongsTo(Pet::class, 'id_hewan');
    }

    public function paket()
    {
        return $this->belongsTo(PackageType::class, 'id_paket');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi');
    }

    public function owner()
    {
        return $this->hasOneThrough(
            User::class,
            Pet::class,
            'id',
            'id',
            'id_hewan',
            'id_pemilik'
        );
    }
}
