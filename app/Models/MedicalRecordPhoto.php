<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecordPhoto extends Model
{
    protected $fillable = ['id_rekam_medis', 'foto'];

    public function rekamMedis(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class, 'id_rekam_medis');
    }
}
