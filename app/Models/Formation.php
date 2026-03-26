<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Formation extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'short_description',
        'content',
        // ── Nouveaux champs pédagogiques ──
        'participant_profile',
        'accessibility',
        'access_delay',
        'teaching_methods',
        'teaching_resources',
        'evaluation_methods',
        'success_rate',
        'satisfaction_rate',
        'employment_rate',
        // ── Fin nouveaux champs ──
        'thumbnail',
        'banner',
        'level',
        'duration',
        'price',
        'price_promo',
        'is_published',
        'is_featured',
        'has_certification',
        'objectives',
        'prerequisites',
        'program',
        'max_students',
        'view_count',
        'published_at',
        'sort_order',
    ];

    protected $casts = [
        'is_published'      => 'boolean',
        'is_featured'       => 'boolean',
        'has_certification' => 'boolean',
        'objectives'        => 'array',
        'prerequisites'     => 'array',
        'program'           => 'array',
        'published_at'      => 'datetime',
        'price'             => 'decimal:2',
        'price_promo'       => 'decimal:2',
        'success_rate'      => 'decimal:2',
        'satisfaction_rate' => 'decimal:2',
        'employment_rate'   => 'decimal:2',
    ];

    // ── Slug automatique ───────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Formation $formation) {
            if (empty($formation->slug)) {
                $formation->slug = static::generateUniqueSlug($formation->title);
            }
        });

        static::updating(function (Formation $formation) {
            if ($formation->isDirty('title') && empty($formation->getOriginal('slug') === $formation->slug)) {
                $formation->slug = static::generateUniqueSlug($formation->title, $formation->id);
            }
        });
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base  = Str::slug($title);
        $slug  = $base;
        $count = 1;

        while (
            static::where('slug', $slug)
                  ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                  ->withTrashed()
                  ->exists()
        ) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }

    // ── Activity log ───────────────────────────────────────

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'is_published', 'price'])
            ->logOnlyDirty()
            ->useLogName('formation');
    }

    // ── Relations ──────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(FormationCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(FormationSession::class);
    }

    public function upcomingSessions(): HasMany
    {
        return $this->hasMany(FormationSession::class)
                    ->published()
                    ->upcoming();
    }

    // ── Accessors ──────────────────────────────────────────

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/formation-placeholder.jpg');
    }

    public function getBannerUrlAttribute(): string
    {
        if ($this->banner) {
            return asset('storage/' . $this->banner);
        }
        return asset('images/banner-placeholder.jpg');
    }

    public function getAverageRatingAttribute(): float
    {
        return round(
            $this->reviews()->where('is_approved', true)->avg('rating') ?? 0,
            1
        );
    }

    public function getApprovedReviewsCountAttribute(): int
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    public function getCurrentPriceAttribute(): ?float
    {
        return $this->price_promo ?? $this->price;
    }

    public function getIsOnPromoAttribute(): bool
    {
        return ! is_null($this->price_promo) && $this->price_promo < $this->price;
    }

    // ── Scopes ─────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    // ── Helpers ────────────────────────────────────────────

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}