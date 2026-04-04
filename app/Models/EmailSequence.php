<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailSequence extends Model
{
    protected $fillable = ['created_by', 'name', 'trigger', 'target_stage_slug', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function steps(): HasMany     { return $this->hasMany(EmailSequenceStep::class)->orderBy('sort_order'); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}