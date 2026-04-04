<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailSequenceStep extends Model
{
    protected $fillable = ['email_sequence_id', 'delay_days', 'subject', 'body_html', 'sort_order', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function sequence(): BelongsTo { return $this->belongsTo(EmailSequence::class, 'email_sequence_id'); }
    public function logs(): HasMany       { return $this->hasMany(EmailLog::class, 'sequence_step_id'); }
}