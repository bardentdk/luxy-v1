<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItem extends Model
{
    protected $table = 'quote_items';

    protected $fillable = [
        'quote_id', 'product_id', 'name', 'description',
        'unit_price', 'quantity', 'unit', 'tax_rate', 'discount_percent',
        'total', 'sort_order',
    ];

    protected $casts = [
        'unit_price'       => 'decimal:2',
        'quantity'         => 'decimal:2',
        'tax_rate'         => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'total'            => 'decimal:2',
    ];

    public function quote(): BelongsTo   { return $this->belongsTo(Quote::class); }
    public function product(): BelongsTo { return $this->belongsTo(CommercialProduct::class, 'product_id'); }

    public function calculateTotal(): float
    {
        $base     = $this->unit_price * $this->quantity;
        $discount = $base * ($this->discount_percent / 100);
        return round($base - $discount, 2);
    }
}