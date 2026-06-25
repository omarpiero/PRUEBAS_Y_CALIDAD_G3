<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('LMS##??')),
            'type' => fake()->randomElement(['porcentaje', 'monto_fijo']),
            'value' => fake()->randomFloat(2, 10, 80),
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'usage_limit' => fake()->numberBetween(20, 100),
            'times_used' => 0,
            'is_active' => true,
        ];
    }
}
