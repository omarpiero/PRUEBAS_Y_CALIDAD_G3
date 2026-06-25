<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'instructor_id', 'name', 'slug', 'short_description',
        'description', 'cover_image', 'level', 'status', 'price', 'sale_price',
        'sale_start', 'sale_end', 'duration_weeks', 'meta_description',
        'is_featured', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'sale_start' => 'datetime',
            'sale_end' => 'datetime',
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'duration_weeks' => 'integer',
        ];
    }

    // ── Relationships ──

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class)->orderBy('order');
    }

    public function materials(): HasManyThrough
    {
        return $this->hasManyThrough(CourseMaterial::class, CourseModule::class, 'course_id', 'module_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    // ── Accessors ──

    /**
     * Get the effective price (sale price if active offer, else normal price).
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->sale_price && $this->sale_start && $this->sale_end) {
            $now = now();
            if ($now->between($this->sale_start, $this->sale_end)) {
                return (float) $this->sale_price;
            }
        }
        return (float) $this->price;
    }

    public function getHasActiveOfferAttribute(): bool
    {
        if (!$this->sale_price || !$this->sale_start || !$this->sale_end) {
            return false;
        }
        return now()->between($this->sale_start, $this->sale_end);
    }

    public function getStudentCountAttribute(): int
    {
        return $this->enrollments()->whereIn('status', ['activo', 'completado'])->count();
    }

    // ── Scopes ──

    public function scopePublished($query)
    {
        return $query->where('status', 'publicado');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    // ── Boot ──

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->name);
            }
            // Ensure slug uniqueness
            $originalSlug = $course->slug;
            $count = 1;
            while (static::where('slug', $course->slug)->exists()) {
                $course->slug = $originalSlug . '-' . $count++;
            }
        });
    }
}
