<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseMaterial;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCourseCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup users
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->student = User::factory()->create(['is_admin' => false]);

        // Setup category
        $this->category = Category::create([
            'name' => 'Calidad',
            'slug' => 'calidad',
            'description' => 'Aseguramiento de la calidad',
            'icon' => '🏅',
            'order' => 1,
        ]);
    }

    public function test_non_admin_cannot_access_course_admin_pages()
    {
        // Guests redirect to login
        $this->get(route('admin.courses.index'))->assertRedirect(route('login'));

        // Students receive 403 Forbidden
        $this->actingAs($this->student)
            ->get(route('admin.courses.index'))
            ->assertStatus(403);
    }

    public function test_admin_can_view_courses_list()
    {
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => 'BPM Básico',
            'slug' => 'bpm-basico',
            'short_description' => 'BPM',
            'level' => 'basico',
            'status' => 'borrador',
            'price' => 100.00,
            'duration_weeks' => 4,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.courses.index'))
            ->assertStatus(200)
            ->assertSee('BPM Básico');
    }

    public function test_admin_can_create_course()
    {
        Storage::fake('public');
        $file = $this->fakePngUpload('cover.png');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.courses.store'), [
                'category_id' => $this->category->id,
                'name' => 'HACCP Avanzado',
                'slug' => 'haccp-avanzado',
                'short_description' => 'Curso de HACCP',
                'level' => 'avanzado',
                'status' => 'borrador',
                'price' => 250.00,
                'duration_weeks' => 6,
                'cover_image' => $file,
            ]);

        $response->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseHas('courses', [
            'name' => 'HACCP Avanzado',
            'slug' => 'haccp-avanzado',
        ]);

        // Check file was stored on public disk
        $course = Course::where('slug', 'haccp-avanzado')->first();
        $this->assertNotNull($course->cover_image);

        // Check audit log was written
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->admin->id,
            'action' => 'create_course',
            'entity_type' => Course::class,
            'entity_id' => $course->id,
        ]);
    }

    public function test_admin_can_update_course()
    {
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => 'BPM Básico',
            'slug' => 'bpm-basico',
            'short_description' => 'BPM original',
            'level' => 'basico',
            'status' => 'borrador',
            'price' => 100.00,
            'duration_weeks' => 4,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.courses.update', $course), [
                'category_id' => $this->category->id,
                'name' => 'BPM Editado',
                'slug' => 'bpm-editado',
                'short_description' => 'BPM modificado',
                'level' => 'basico',
                'status' => 'borrador',
                'price' => 120.00,
                'duration_weeks' => 5,
            ]);

        $response->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'BPM Editado',
            'slug' => 'bpm-editado',
        ]);

        // Check audit log
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->admin->id,
            'action' => 'update_course',
            'entity_type' => Course::class,
            'entity_id' => $course->id,
        ]);
    }

    public function test_admin_can_duplicate_course()
    {
        Storage::fake('local');
        
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => 'Curso Original',
            'slug' => 'curso-original',
            'short_description' => 'Original',
            'level' => 'basico',
            'status' => 'publicado',
            'price' => 100.00,
            'duration_weeks' => 4,
        ]);

        $module = CourseModule::create([
            'course_id' => $course->id,
            'name' => 'Módulo 1',
            'order' => 1,
            'status' => 'activo',
        ]);

        // Create material with fake file path
        $materialFile = 'materials/99/99/sample.pdf';
        Storage::disk('local')->put($materialFile, 'content');

        $material = CourseMaterial::create([
            'module_id' => $module->id,
            'type' => 'documento',
            'title' => 'PDF original',
            'file_path' => $materialFile,
            'file_type' => 'application/pdf',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.courses.duplicate', $course));

        $response->assertRedirect(route('admin.courses.index'));
        
        // Assert duplicate exists in DB
        $this->assertDatabaseHas('courses', [
            'name' => 'Curso Original (Copia)',
            'status' => 'borrador',
        ]);

        $duplicatedCourse = Course::where('name', 'Curso Original (Copia)')->first();
        $this->assertNotNull($duplicatedCourse);

        // Check modules & materials were duplicated
        $this->assertDatabaseHas('course_modules', [
            'course_id' => $duplicatedCourse->id,
            'name' => 'Módulo 1',
        ]);

        $duplicatedModule = CourseModule::where('course_id', $duplicatedCourse->id)->first();
        $this->assertNotNull($duplicatedModule);

        $this->assertDatabaseHas('course_materials', [
            'module_id' => $duplicatedModule->id,
            'title' => 'PDF original',
        ]);

        $duplicatedMaterial = CourseMaterial::where('module_id', $duplicatedModule->id)->first();
        $this->assertNotNull($duplicatedMaterial);

        // Check file was copied in private storage
        $this->assertNotNull($duplicatedMaterial->file_path);
        $this->assertNotEquals($material->file_path, $duplicatedMaterial->file_path);
        Storage::disk('local')->assertExists($duplicatedMaterial->file_path);
    }

    public function test_admin_can_publish_and_unpublish_course()
    {
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => 'BPM Básico',
            'slug' => 'bpm-basico',
            'short_description' => 'BPM',
            'description' => 'Descripción completa',
            'cover_image' => 'https://example.com/cover.jpg',
            'level' => 'basico',
            'status' => 'borrador',
            'price' => 100.00,
            'duration_weeks' => 4,
        ]);

        // Publish fails if no active modules
        $response = $this->actingAs($this->admin)
            ->patch(route('admin.courses.publish', $course));
        
        $response->assertSessionHas('error');
        $this->assertEquals('borrador', $course->fresh()->status);

        // Add module & material to satisfy requirements
        $module = CourseModule::create([
            'course_id' => $course->id,
            'name' => 'Módulo 1',
            'order' => 1,
            'status' => 'activo',
        ]);

        CourseMaterial::create([
            'module_id' => $module->id,
            'type' => 'texto',
            'title' => 'Texto',
            'content' => 'Lectura',
            'order' => 1,
        ]);

        // Publish succeeds now
        $response = $this->actingAs($this->admin)
            ->patch(route('admin.courses.publish', $course));
        
        $response->assertSessionHas('success');
        $this->assertEquals('publicado', $course->fresh()->status);

        // Unpublish
        $response = $this->actingAs($this->admin)
            ->patch(route('admin.courses.unpublish', $course));
        
        $response->assertSessionHas('success');
        $this->assertEquals('borrador', $course->fresh()->status);
    }

    public function test_admin_cannot_delete_course_with_active_enrollments()
    {
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => 'BPM Básico',
            'slug' => 'bpm-basico',
            'short_description' => 'BPM',
            'level' => 'basico',
            'status' => 'borrador',
            'price' => 100.00,
            'duration_weeks' => 4,
        ]);

        $student = User::factory()->create();
        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'activo',
        ]);

        // Deletion should be rejected
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.courses.destroy', $course));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }

    public function test_admin_can_delete_course_without_active_enrollments()
    {
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => 'BPM Básico',
            'slug' => 'bpm-basico',
            'short_description' => 'BPM',
            'level' => 'basico',
            'status' => 'borrador',
            'price' => 100.00,
            'duration_weeks' => 4,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.courses.destroy', $course));

        $response->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    private function fakePngUpload(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'course-cover-');
        file_put_contents($path, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        ));

        return new UploadedFile($path, $name, 'image/png', null, true);
    }
}
