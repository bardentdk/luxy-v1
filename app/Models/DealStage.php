<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DealStage extends Model
{
    protected $fillable = ['name', 'slug', 'color', 'sort_order', 'is_won', 'is_lost'];

    protected $casts = [
        'is_won'  => 'boolean',
        'is_lost' => 'boolean',
    ];

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}