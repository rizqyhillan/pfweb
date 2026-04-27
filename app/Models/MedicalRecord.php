<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    protected $fillable = [
        'pet_id',
        'doctor_id',
        'diagnosis',
        'treatment',
        'prescription',
        'current_weight',
        'date',
    ];

    protected $casts = [
        'current_weight' => 'decimal:2',
        'date' => 'datetime',
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
