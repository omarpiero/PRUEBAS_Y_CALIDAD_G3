<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SaleItem>
 */
class SaleItemFactory extends Factory
{
    protected $model = SaleItem::class;

    public function definition(): array
    {
        return [
            'sale_id' => Sale::factory(),
            'course_id' => Course::factory(),
            'price' => fake()->randomFloat(2, 120, 600),
        ];
    }
}
