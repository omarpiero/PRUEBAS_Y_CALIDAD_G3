<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseMaterial;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentCourseAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected Course $course;
    protected CourseModule $module;
    protected CourseMaterial $materialVideo;
    protected CourseMaterial $materialText;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup student
        $this->student = User::factory()->create();

        // Setup category
        $category = Category::create([
            'name' => 'Inocuidad',
            'slug' => 'inocuidad',
            'description' => 'Inocuidad alimentaria',
            'icon' => '🛡️',
            'order' => 1,
        ]);

        // Setup course
        $this->course = Course::create([
            'category_id' => $category->id,
            'name' => 'BPM Avanzado',
            'slug' => 'bpm-avanzado',
            'short_description' => 'BPM',
            'level' => 'avanzado',
            'status' => 'publicado',
            'price' => 120.00,
            'duration_weeks' => 6,
        ]);

        // Setup module
        $this->module = CourseModule::create([
            'course_id' => $this->course->id,
            'name' => 'Módulo General',
            'order' => 1,
            'status' => 'activo',
        ]);

        // Setup materials
        $this->materialVideo = CourseMaterial::create([
            'module_id' => $this->module->id,
            'type' => 'video',
            'title' => 'Vídeo de BPM',
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration_minutes' => 10,
            'order' => 1,
        ]);

        $this->materialText = CourseMaterial::create([
            'module_id' => $this->module->id,
            'type' => 'texto',
            'title' => 'Lectura Obligatoria',
            'content' => '<p>Contenido de lectura</p>',
            'order' => 2,
        ]);
    }

    public function test_guest_cannot_access_classroom()
    {
        $response = $this->get(route('mi-cuenta.cursos.show', $this->course));
        $response->assertRedirect('/login');
    }

    public function test_user_without_enrollment_cannot_access_classroom()
    {
        $response = $this->actingAs($this->student)
            ->get(route('mi-cuenta.cursos.show', $this->course));

        $response->assertStatus(403);
    }

    public function test_user_with_pending_enrollment_cannot_access_classroom()
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'pendiente',
        ]);

        $response = $this->actingAs($this->student)
            ->get(route('mi-cuenta.cursos.show', $this->course));

        $response->assertStatus(403);
    }

    public function test_user_with_suspended_enrollment_cannot_access_classroom()
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'suspendido',
        ]);

        $response = $this->actingAs($this->student)
            ->get(route('mi-cuenta.cursos.show', $this->course));

        $response->assertStatus(403);
    }

    public function test_user_with_active_enrollment_can_access_classroom()
    {
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'activo',
        ]);

        $this->assertNull($enrollment->last_accessed_at);

        $response = $this->actingAs($this->student)
            ->get(route('mi-cuenta.cursos.show', $this->course));

        $response->assertStatus(200);
        $response->assertViewHas('course');
        $response->assertSee('Volver a Mi Cuenta');
        $response->assertSee('M&oacute;dulo', false);
        $response->assertSee('Módulo General');

        // Assert last accessed time was updated
        $this->assertNotNull($enrollment->fresh()->last_accessed_at);
    }

    public function test_toggle_material_completion_updates_progress()
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'activo',
        ]);

        // Mark first material completed
        $response = $this->actingAs($this->student)
            ->postJson(route('mi-cuenta.cursos.complete-material', [$this->course, $this->materialVideo]));

        $response->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'completed' => true,
                'progress' => 50,
                'status' => 'activo',
            ]);

        $this->assertDatabaseHas('course_material_user', [
            'user_id' => $this->student->id,
            'course_material_id' => $this->materialVideo->id,
        ]);

        // Mark second material completed (course is 100% completed)
        $response2 = $this->actingAs($this->student)
            ->postJson(route('mi-cuenta.cursos.complete-material', [$this->course, $this->materialText]));

        $response2->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'completed' => true,
                'progress' => 100,
                'status' => 'completado',
            ]);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'completado',
        ]);
        $this->assertNotNull(Enrollment::where('user_id', $this->student->id)->first()->completed_at);

        // Mark second material incomplete again (reverts back to active)
        $response3 = $this->actingAs($this->student)
            ->postJson(route('mi-cuenta.cursos.complete-material', [$this->course, $this->materialText]));

        $response3->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'completed' => false,
                'progress' => 50,
                'status' => 'activo',
            ]);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'activo',
        ]);
        $this->assertNull(Enrollment::where('user_id', $this->student->id)->first()->completed_at);
    }

    public function test_unauthorized_user_cannot_download_private_files()
    {
        Storage::fake('local');
        $filePath = 'materials/' . $this->course->id . '/' . $this->module->id . '/test.pdf';
        Storage::disk('local')->put($filePath, 'pdf content');

        $materialPdf = CourseMaterial::create([
            'module_id' => $this->module->id,
            'type' => 'documento',
            'title' => 'Documento Privado',
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
        ]);

        // Student is not enrolled, download should fail
        $response = $this->actingAs($this->student)
            ->get(route('mi-cuenta.cursos.file', [$this->course, $materialPdf]));

        $response->assertStatus(403);
    }

    public function test_enrolled_user_can_access_private_files()
    {
        Storage::fake('local');
        $filePath = 'materials/' . $this->course->id . '/' . $this->module->id . '/test.pdf';
        Storage::disk('local')->put($filePath, 'pdf content');

        $materialPdf = CourseMaterial::create([
            'module_id' => $this->module->id,
            'type' => 'documento',
            'title' => 'Documento Privado',
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
        ]);

        // Enroll student as active
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'activo',
        ]);

        // Download pdf file
        $response = $this->actingAs($this->student)
            ->get(route('mi-cuenta.cursos.file', [$this->course, $materialPdf]));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }
}
