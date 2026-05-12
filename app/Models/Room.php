<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'kamar';

    protected $fillable = [
        'nama_kamar',
        'paket',
        'harga_per_hari',
        'kapasitas',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'harga_per_hari' => 'decimal:2',
    ];

    // Label paket yang rapi
    public static function paketOptions(): array
    {
        return [
            'basic' => 'Basic',
            'regular' => 'Regular',
            'premium' => 'Premium',
        ];
    }

    public function getPaketLabelAttribute(): string
    {
        return self::paketOptions()[$this->paket] ?? ucfirst($this->paket);
    }

    public function getNameAttribute(): string
    {
        return $this->nama_kamar;
    }

    public function getPricePerDayAttribute()
    {
        return $this->harga_per_hari;
    }

    public function boardings()
    {
        return $this->hasMany(Boarding::class, 'id_kamar');
    }
}
