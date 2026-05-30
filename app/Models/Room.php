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
        'foto_urls',
        'status',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'terisi' => 'integer',
        'harga_per_hari' => 'decimal:2',
        'foto_urls' => 'array',
    ];


    public function getFotoUrlsAttribute($value): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? array_values(array_filter($decoded)) : [];
    }

    public function getFotoFullUrlsAttribute(): array
    {
        return collect($this->foto_urls)
            ->filter()
            ->map(function ($path) {
                $path = ltrim((string) $path, '/');

                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                    return $path;
                }

                return asset('storage/' . $path);
            })
            ->values()
            ->all();
    }

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
