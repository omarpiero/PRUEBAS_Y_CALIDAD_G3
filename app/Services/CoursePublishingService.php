<?php

namespace App\Services;

use App\Models\Course;

class CoursePublishingService
{
    /**
     * Validate if a course can be published.
     * Returns an array of validation error messages. If empty, the course is valid.
     *
     * @param Course $course
     * @return array<string>
     */
    public function canPublish(Course $course): array
    {
        $errors = [];

        if (empty($course->name)) {
            $errors[] = 'El curso debe tener un nombre.';
        }

        if (empty($course->cover_image)) {
            $errors[] = 'El curso debe tener una imagen de portada.';
        }

        if (empty($course->short_description)) {
            $errors[] = 'El curso debe tener una descripción corta.';
        }

        if (empty($course->description)) {
            $errors[] = 'El curso debe tener una descripción completa.';
        }

        if ($course->price < 0) {
            $errors[] = 'El precio del curso no puede ser negativo.';
        }

        $activeModulesCount = $course->modules()->where('status', 'activo')->count();
        if ($activeModulesCount === 0) {
            $errors[] = 'El curso debe tener al menos un módulo activo.';
        } else {
            $activeModules = $course->modules()->where('status', 'activo')->get();
            foreach ($activeModules as $module) {
                if ($module->materials()->count() === 0) {
                    $errors[] = "El módulo '{$module->name}' no contiene materiales educativos.";
                }
            }
        }

        return $errors;
    }

    /**
     * Try to publish the course.
     * Returns true if successful, false otherwise.
     *
     * @param Course $course
     * @return bool
     */
    public function publish(Course $course): bool
    {
        $errors = $this->canPublish($course);

        if (count($errors) > 0) {
            return false;
        }

        return $course->update([
            'status' => 'publicado',
            'published_at' => now(),
        ]);
    }
}
