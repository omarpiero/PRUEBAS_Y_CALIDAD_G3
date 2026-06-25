<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'start_date', 'end_date',
        'usage_limit', 'times_used', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'usage_limit' => 'integer',
            'times_used' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Check if coupon is currently valid.
     */
    public function getIsValidAttribute(): bool
    {
        if (!$this->is_active) return false;

        $now = now()->toDateString();
        if ($now < $this->start_date || $now > $this->end_date) return false;

        if ($this->usage_limit !== null && $this->times_used >= $this->usage_limit) return false;

        return true;
    }

    /**
     * Calculate discount for a given amount.
     */
    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'porcentaje') {
            return round($amount * ($this->value / 100), 2);
        }
        return min((float) $this->value, $amount);
    }
}
