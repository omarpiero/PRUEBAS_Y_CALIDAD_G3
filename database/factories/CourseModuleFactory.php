<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseModule>
 */
class CourseModuleFactory extends Factory
{
    protected $model = CourseModule::class;

    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'name' => 'Modulo ' . fake()->words(2, true),
            'description' => fake()->sentence(),
            'order' => fake()->numberBetween(1, 8),
            'status' => 'activo',
        ];
    }
}
