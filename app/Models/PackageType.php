<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class PackageType extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
        'harga_per_malam',
    ];

    protected $casts = [
        'harga_per_malam' => 'decimal:2',
    ];

    public static function defaultOptions(): array
    {
        return [
            'basic' => 'Basic',
            'regular' => 'Regular',
            'premium' => 'Premium',
        ];
    }

    public static function defaultPrices(): array
    {
        return [
            'basic' => 50000,
            'regular' => 100000,
            'premium' => 150000,
        ];
    }

    public static function options(): array
    {
        if (! Schema::hasTable((new self)->getTable())) {
            return self::defaultOptions();
        }

        self::ensureDefaultTypes();

        $options = self::orderBy('label')->pluck('label', 'name')->toArray();

        return empty($options) ? self::defaultOptions() : $options;
    }

    public static function prices(): array
    {
        if (! Schema::hasTable((new self)->getTable())) {
            return self::defaultPrices();
        }

        self::ensureDefaultTypes();

        $prices = self::pluck('harga_per_malam', 'name')->toArray();

        return empty($prices) ? self::defaultPrices() : $prices;
    }

    public static function ensureDefaultTypes(): void
    {
        foreach (self::defaultOptions() as $name => $label) {
            self::firstOrCreate([
                'name' => $name,
            ], [
                'label' => $label,
                'description' => null,
                'harga_per_malam' => self::defaultPrices()[$name] ?? 0,
            ]);
        }
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'paket', 'name');
    }
}
