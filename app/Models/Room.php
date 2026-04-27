<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'name',
        'type',
        'price_per_day',
        'capacity',
        'status',
        'description',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
    ];

    public function boardings(): HasMany
    {
        return $this->hasMany(Boarding::class);
    }
}
