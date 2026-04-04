<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmContact extends Model
{
    use SoftDeletes;

    protected $table = 'crm_contacts';

    protected $fillable = [
        'assigned_to', 'contact_form_id',
        'first_name', 'last_name', 'email', 'phone',
        'company', 'job_title', 'source', 'status',
        'notes', 'tags', 'last_contacted_at',
    ];

    protected $casts = [
        'tags'              => 'array',
        'last_contacted_at' => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function contactForm(): BelongsTo
    {
        return $this->belongsTo(ContactForm::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(DealActivity::class);
    }

    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }

    // ── Accessors ──────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'lead'     => 'Lead',
            'prospect' => 'Prospect',
            'client'   => 'Client',
            'lost'     => 'Perdu',
            default    => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'lead'     => '#6366F1',
            'prospect' => '#F59E0B',
            'client'   => '#22C55E',
            'lost'     => '#EF4444',
            default    => '#6B7280',
        };
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(fn($q) => $q
            ->where('first_name', 'like', "%{$term}%")
            ->orWhere('last_name',  'like', "%{$term}%")
            ->orWhere('email',      'like', "%{$term}%")
            ->orWhere('company',    'like', "%{$term}%")
        );
    }
}