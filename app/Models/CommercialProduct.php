<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommercialProduct extends Model
{
    use SoftDeletes;

    protected $table = 'commercial_products';

    protected $fillable = [
        'formation_id', 'name', 'reference', 'description',
        'unit_price', 'unit', 'tax_rate', 'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'tax_rate'   => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    public function formation(): BelongsTo { return $this->belongsTo(Formation::class); }
    public function quoteItems(): HasMany  { return $this->hasMany(QuoteItem::class, 'product_id'); }

    public function getPriceWithTaxAttribute(): float
    {
        return $this->unit_price * (1 + $this->tax_rate / 100);
    }

    public function scopeActive($query) { return $query->where('is_active', true); }
}