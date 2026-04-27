<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Boarding extends Model
{
    protected $fillable = [
        'pet_id',
        'room_id',
        'check_in_date',
        'planned_check_out_date',
        'check_out_date',
        'drop_off_notes',
        'pick_up_notes',
        'status',
        'total_cost',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'planned_check_out_date' => 'date',
        'check_out_date' => 'date',
        'total_cost' => 'decimal:2',
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
