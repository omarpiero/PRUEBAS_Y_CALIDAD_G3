<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $name = fake()->unique()->sentence(3);

        return [
            'category_id' => Category::factory(),
            'instructor_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'short_description' => fake()->sentence(12),
            'description' => '<p>' . fake()->paragraph() . '</p>',
            'cover_image' => 'https://example.com/course-cover.jpg',
            'level' => fake()->randomElement(['basico', 'intermedio', 'avanzado']),
            'status' => 'borrador',
            'price' => fake()->randomFloat(2, 120, 600),
            'sale_price' => null,
            'sale_start' => null,
            'sale_end' => null,
            'duration_weeks' => fake()->numberBetween(4, 12),
            'meta_description' => fake()->sentence(12),
            'is_featured' => false,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => 'publicado',
            'published_at' => now(),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn () => [
            'is_featured' => true,
        ]);
    }
}
