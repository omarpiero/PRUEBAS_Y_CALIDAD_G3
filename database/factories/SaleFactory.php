<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 120, 800);
        $discount = fake()->randomFloat(2, 0, min(80, $subtotal));

        return [
            'user_id' => User::factory(),
            'coupon_id' => fake()->boolean(35) ? Coupon::factory() : null,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => max(0, $subtotal - $discount),
            'payment_method' => fake()->randomElement(['tarjeta', 'yape', 'plin', 'transferencia']),
            'payment_status' => 'pagado',
            'stripe_payment_id' => null,
            'notes' => 'Venta generada por factory.',
            'paid_at' => now(),
        ];
    }
}
