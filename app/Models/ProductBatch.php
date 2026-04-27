<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductBatch extends Model
{
    protected $fillable = [
        'product_id',
        'supplier_id',
        'batch_number',
        'purchase_price',
        'quantity_received',
        'stock_remaining',
        'received_date',
        'expired_date',
        'notes',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'received_date' => 'date',
        'expired_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockCards(): HasMany
    {
        return $this->hasMany(StockCard::class, 'batch_id');
    }
}
