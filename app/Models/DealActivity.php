<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealActivity extends Model
{
    protected $table = 'deal_activities';

    protected $fillable = [
        'deal_id', 'crm_contact_id', 'user_id',
        'type', 'title', 'body', 'scheduled_at', 'done_at', 'is_done',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'done_at'      => 'datetime',
        'is_done'      => 'boolean',
    ];

    public function deal(): BelongsTo    { return $this->belongsTo(Deal::class); }
    public function contact(): BelongsTo { return $this->belongsTo(CrmContact::class, 'crm_contact_id'); }
    public function user(): BelongsTo   { return $this->belongsTo(User::class); }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'note'    => 'PhNote',
            'call'    => 'PhPhone',
            'email'   => 'PhEnvelope',
            'meeting' => 'PhCalendar',
            'task'    => 'PhCheckSquare',
            default   => 'PhNote',
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'note'    => '#6366F1',
            'call'    => '#22C55E',
            'email'   => '#C9A84C',
            'meeting' => '#3B82F6',
            'task'    => '#F59E0B',
            default   => '#6B7280',
        };
    }
}