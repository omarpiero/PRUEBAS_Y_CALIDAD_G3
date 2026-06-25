<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrollment>
 */
class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'status' => Enrollment::STATUS_ACTIVE,
            'progress' => fake()->randomFloat(2, 0, 100),
            'last_accessed_at' => now()->subDays(fake()->numberBetween(0, 10)),
            'total_time_minutes' => fake()->numberBetween(15, 600),
            'completed_at' => null,
            'enrolled_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => Enrollment::STATUS_COMPLETED,
            'progress' => 100,
            'completed_at' => now(),
        ]);
    }
}
