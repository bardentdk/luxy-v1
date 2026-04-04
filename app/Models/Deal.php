<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'crm_contact_id', 'deal_stage_id', 'assigned_to', 'formation_id',
        'title', 'amount', 'currency', 'expected_close_date',
        'probability', 'lost_reason', 'notes', 'won_at', 'lost_at',
    ];

    protected $casts = [
        'amount'              => 'decimal:2',
        'expected_close_date' => 'date',
        'won_at'              => 'datetime',
        'lost_at'             => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────

    public function contact(): BelongsTo
    {
        return $this->belongsTo(CrmContact::class, 'crm_contact_id');
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(DealStage::class, 'deal_stage_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(DealActivity::class)->latest();
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    // ── Helpers ────────────────────────────────────────────

    public function moveToStage(DealStage $stage): void
    {
        $data = ['deal_stage_id' => $stage->id];
        if ($stage->is_won)  $data['won_at']  = now();
        if ($stage->is_lost) $data['lost_at'] = now();
        $this->update($data);
    }
}