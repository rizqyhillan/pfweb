<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockCard extends Model
{
    protected $fillable = [
        'batch_id',
        'date',
        'mutation_type',
        'quantity',
        'balance',
        'unit_price',
        'reference',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'date' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ProductBatch::class, 'batch_id');
    }
}
