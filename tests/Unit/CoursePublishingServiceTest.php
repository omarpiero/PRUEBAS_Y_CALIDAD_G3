<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseModule;
use App\Services\CoursePublishingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursePublishingServiceTest extends TestCase
{
    use RefreshDatabase;

    private CoursePublishingService $service;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CoursePublishingService();
        $this->category = Category::create([
            'name' => 'Calidad Alimentaria',
            'slug' => 'calidad-alimentaria',
            'description' => 'Cursos de calidad'
        ]);
    }

    public function test_course_cannot_be_published_if_missing_basic_fields()
    {
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => '',
            'slug' => 'curso-test',
            'short_description' => '',
            'description' => '',
            'cover_image' => '',
            'price' => -10.00
        ]);

        $errors = $this->service->canPublish($course);

        $this->assertContains('El curso debe tener un nombre.', $errors);
        $this->assertContains('El curso debe tener una imagen de portada.', $errors);
        $this->assertContains('El curso debe tener una descripción corta.', $errors);
        $this->assertContains('El curso debe tener una descripción completa.', $errors);
        $this->assertContains('El precio del curso no puede ser negativo.', $errors);
    }

    public function test_course_cannot_be_published_without_active_modules()
    {
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => 'Curso Calidad',
            'slug' => 'curso-calidad',
            'short_description' => 'Breve desc',
            'description' => 'Desc completa',
            'cover_image' => 'cover.png',
            'price' => 99.99
        ]);

        $errors = $this->service->canPublish($course);
        $this->assertContains('El curso debe tener al menos un módulo activo.', $errors);

        $module = CourseModule::create([
            'course_id' => $course->id,
            'name' => 'Modulo Inactivo',
            'status' => 'inactivo'
        ]);

        $errors = $this->service->canPublish($course);
        $this->assertContains('El curso debe tener al menos un módulo activo.', $errors);
    }

    public function test_course_cannot_be_published_if_active_module_has_no_materials()
    {
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => 'Curso Calidad',
            'slug' => 'curso-calidad',
            'short_description' => 'Breve desc',
            'description' => 'Desc completa',
            'cover_image' => 'cover.png',
            'price' => 99.99
        ]);

        $module = CourseModule::create([
            'course_id' => $course->id,
            'name' => 'Modulo Activo',
            'status' => 'activo'
        ]);

        $errors = $this->service->canPublish($course);
        $this->assertContains("El módulo 'Modulo Activo' no contiene materiales educativos.", $errors);
    }

    public function test_course_can_be_published_if_all_criteria_met()
    {
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => 'Curso Calidad',
            'slug' => 'curso-calidad',
            'short_description' => 'Breve desc',
            'description' => 'Desc completa',
            'cover_image' => 'cover.png',
            'price' => 99.99
        ]);

        $module = CourseModule::create([
            'course_id' => $course->id,
            'name' => 'Modulo Activo',
            'status' => 'activo'
        ]);

        CourseMaterial::create([
            'module_id' => $module->id,
            'type' => 'texto',
            'title' => 'Texto Educativo',
            'content' => 'Lorem ipsum...'
        ]);

        $errors = $this->service->canPublish($course);
        $this->assertEmpty($errors);

        $published = $this->service->publish($course);
        $this->assertTrue($published);
        $this->assertEquals('publicado', $course->fresh()->status);
        $this->assertNotNull($course->fresh()->published_at);
    }
}
