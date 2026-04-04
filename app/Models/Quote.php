<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'crm_contact_id', 'deal_id', 'created_by', 'reference',
        'status', 'issued_at', 'expires_at',
        'subtotal', 'tax_total', 'total', 'discount_percent',
        'notes', 'terms', 'sent_at', 'accepted_at', 'refused_at',
    ];

    protected $casts = [
        'issued_at'   => 'date',
        'expires_at'  => 'date',
        'sent_at'     => 'datetime',
        'accepted_at' => 'datetime',
        'refused_at'  => 'datetime',
        'subtotal'    => 'decimal:2',
        'tax_total'   => 'decimal:2',
        'total'       => 'decimal:2',
    ];

    // ── Relations ──────────────────────────────────────────

    public function contact(): BelongsTo  { return $this->belongsTo(CrmContact::class, 'crm_contact_id'); }
    public function deal(): BelongsTo     { return $this->belongsTo(Deal::class); }
    public function createdBy(): BelongsTo{ return $this->belongsTo(User::class, 'created_by'); }
    public function items(): HasMany      { return $this->hasMany(QuoteItem::class)->orderBy('sort_order'); }

    // ── Accessors ──────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'    => 'Brouillon',
            'sent'     => 'Envoyé',
            'accepted' => 'Accepté',
            'refused'  => 'Refusé',
            'expired'  => 'Expiré',
            default    => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'    => '#6B7280',
            'sent'     => '#F59E0B',
            'accepted' => '#22C55E',
            'refused'  => '#EF4444',
            'expired'  => '#9CA3AF',
            default    => '#6B7280',
        };
    }

    // ── Helpers ────────────────────────────────────────────

    public function recalculate(): void
    {
        $subtotal = $this->items->sum('total');
        $discount = $subtotal * ($this->discount_percent / 100);
        $subtotalAfterDiscount = $subtotal - $discount;
        $taxTotal = $this->items->sum(fn($i) => $i->total * ($i->tax_rate / 100));

        $this->update([
            'subtotal'  => $subtotalAfterDiscount,
            'tax_total' => $taxTotal,
            'total'     => $subtotalAfterDiscount + $taxTotal,
        ]);
    }

    public static function generateReference(): string
    {
        $year  = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'DEVIS-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}