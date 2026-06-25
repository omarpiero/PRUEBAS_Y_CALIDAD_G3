<?php

namespace Database\Factories;

use App\Models\CourseMaterial;
use App\Models\CourseModule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseMaterial>
 */
class CourseMaterialFactory extends Factory
{
    protected $model = CourseMaterial::class;

    public function definition(): array
    {
        return [
            'module_id' => CourseModule::factory(),
            'type' => 'texto',
            'title' => 'Leccion ' . fake()->words(3, true),
            'description' => fake()->sentence(),
            'content' => '<p>' . fake()->paragraph() . '</p>',
            'file_path' => null,
            'file_type' => null,
            'video_url' => null,
            'video_source' => null,
            'duration_minutes' => fake()->numberBetween(5, 25),
            'order' => fake()->numberBetween(1, 10),
            'is_downloadable' => false,
        ];
    }

    public function youtube(): static
    {
        return $this->state(fn () => [
            'type' => 'video',
            'content' => null,
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration_minutes' => 12,
        ]);
    }

    public function document(): static
    {
        return $this->state(fn () => [
            'type' => 'documento',
            'content' => null,
            'file_path' => 'materials/demo/demo.pdf',
            'file_type' => 'application/pdf',
            'is_downloadable' => true,
        ]);
    }
}
