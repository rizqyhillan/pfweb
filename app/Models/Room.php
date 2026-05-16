<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Room extends Model
{
    protected $table = 'kamar';

    protected $fillable = [
        'nama_kamar',
        'paket',
        'kapasitas',
        'terisi',
        'harga_per_hari',
        'fasilitas',
        'status',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'terisi' => 'integer',
        'harga_per_hari' => 'decimal:2',
    ];

    public function getPaketLabelAttribute(): string
    {
        if (! Schema::hasTable('package_types')) {
            return ucfirst($this->paket);
        }

        return PackageType::where('name', $this->paket)->value('label') ?? ucfirst($this->paket);
    }

    public static function paketOptions(): array
    {
        return [
            'basic' => 'Basic',
            'regular' => 'Regular',
            'premium' => 'Premium',
        ];
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
