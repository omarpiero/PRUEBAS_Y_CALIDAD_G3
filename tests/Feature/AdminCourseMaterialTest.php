<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseMaterial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCourseMaterialTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Course $course;
    protected CourseModule $module;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup admin user
        $this->admin = User::factory()->create(['is_admin' => true]);

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
            'name' => 'BPM Básico',
            'slug' => 'bpm-basico',
            'short_description' => 'BPM',
            'level' => 'basico',
            'status' => 'borrador',
            'price' => 100.00,
            'duration_weeks' => 4,
        ]);

        // Setup module
        $this->module = CourseModule::create([
            'course_id' => $this->course->id,
            'name' => 'Módulo General',
            'order' => 1,
            'status' => 'activo',
        ]);
    }

    public function test_admin_can_create_module()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.modules.store'), [
                'course_id' => $this->course->id,
                'name' => 'Nuevo Módulo',
                'description' => 'Detalle del módulo',
                'status' => 'activo',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('course_modules', [
            'course_id' => $this->course->id,
            'name' => 'Nuevo Módulo',
        ]);
    }

    public function test_admin_can_update_module()
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.modules.update', $this->module), [
                'name' => 'Módulo Modificado',
                'status' => 'inactivo',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('course_modules', [
            'id' => $this->module->id,
            'name' => 'Módulo Modificado',
            'status' => 'inactivo',
        ]);
    }

    public function test_admin_can_reorder_modules()
    {
        $module2 = CourseModule::create([
            'course_id' => $this->course->id,
            'name' => 'Módulo Segundo',
            'order' => 2,
            'status' => 'activo',
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.modules.reorder'), [
                'ids' => [$module2->id, $this->module->id]
            ]);

        $response->assertStatus(200);
        $this->assertEquals(1, $module2->fresh()->order);
        $this->assertEquals(2, $this->module->fresh()->order);
    }

    public function test_admin_can_create_video_material_by_url()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.materials.store'), [
                'module_id' => $this->module->id,
                'type' => 'video',
                'title' => 'Vídeo de Introducción',
                'video_source' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration_minutes' => 10,
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('course_materials', [
            'module_id' => $this->module->id,
            'title' => 'Vídeo de Introducción',
            'video_source' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
    }

    public function test_admin_can_create_video_material_by_upload()
    {
        Storage::fake('local');
        $videoFile = UploadedFile::fake()->create('lecture.mp4', 1500, 'video/mp4'); // ~1.5MB

        $response = $this->actingAs($this->admin)
            ->post(route('admin.materials.store'), [
                'module_id' => $this->module->id,
                'type' => 'video',
                'title' => 'Vídeo Subido',
                'video_source' => 'upload',
                'file' => $videoFile,
                'duration_minutes' => 15,
            ]);

        $response->assertStatus(302);
        
        $material = CourseMaterial::where('title', 'Vídeo Subido')->first();
        $this->assertNotNull($material);
        $this->assertNotNull($material->file_path);
        
        // Assert file exists in private storage materials folder
        Storage::disk('local')->assertExists($material->file_path);
        $this->assertEquals('video/mp4', $material->file_type);
    }

    public function test_file_upload_validation_limits()
    {
        Storage::fake('local');
        
        // 1. Invalid extension (e.g. .exe in resources)
        $exeFile = UploadedFile::fake()->create('hack.exe', 100, 'application/octet-stream');
        
        $response = $this->actingAs($this->admin)
            ->post(route('admin.materials.store'), [
                'module_id' => $this->module->id,
                'type' => 'recurso',
                'title' => 'Programa malicioso',
                'file' => $exeFile,
            ]);
            
        $response->assertSessionHasErrors('file');

        // 2. Size limit exceeded (e.g. document > 50MB)
        // config/lms.php allows 51200 KB (50MB) for documents
        $largeFile = UploadedFile::fake()->create('huge.pdf', 60000, 'application/pdf'); // ~60MB
        
        $response = $this->actingAs($this->admin)
            ->post(route('admin.materials.store'), [
                'module_id' => $this->module->id,
                'type' => 'documento',
                'title' => 'Documento muy grande',
                'file' => $largeFile,
            ]);
            
        $response->assertSessionHasErrors('file');
    }

    public function test_rich_text_material_with_quill_sanitization()
    {
        $maliciousHtml = '<p>Esto es texto seguro. <script>alert("hack")</script> <iframe src="http://evil.com"></iframe></p>';

        $response = $this->actingAs($this->admin)
            ->post(route('admin.materials.store'), [
                'module_id' => $this->module->id,
                'type' => 'texto',
                'title' => 'Lectura de Prueba',
                'content' => $maliciousHtml,
            ]);

        $response->assertStatus(302);
        
        $material = CourseMaterial::where('title', 'Lectura de Prueba')->first();
        $this->assertNotNull($material);
        
        // Verify script and iframe were stripped out
        $this->assertStringNotContainsString('<script>', $material->content);
        $this->assertStringNotContainsString('<iframe>', $material->content);
        $this->assertStringContainsString('Esto es texto seguro.', $material->content);
    }

    public function test_file_cleanup_on_material_replacement_and_deletion()
    {
        Storage::fake('local');
        $pdfFile = UploadedFile::fake()->create('doc.pdf', 500, 'application/pdf');

        // 1. Create material with file
        $this->actingAs($this->admin)
            ->post(route('admin.materials.store'), [
                'module_id' => $this->module->id,
                'type' => 'documento',
                'title' => 'Documento PDF',
                'file' => $pdfFile,
            ]);

        $material = CourseMaterial::where('title', 'Documento PDF')->first();
        $filePath = $material->file_path;
        Storage::disk('local')->assertExists($filePath);

        // 2. Replace file with new PDF
        $newPdfFile = UploadedFile::fake()->create('doc_new.pdf', 600, 'application/pdf');
        $this->actingAs($this->admin)
            ->put(route('admin.materials.update', $material), [
                'title' => 'Documento PDF Editado',
                'file' => $newPdfFile,
            ]);

        // Old file must be physically deleted
        Storage::disk('local')->assertMissing($filePath);
        // New file must exist
        $updatedMaterial = $material->fresh();
        Storage::disk('local')->assertExists($updatedMaterial->file_path);

        // 3. Delete material
        $newFilePath = $updatedMaterial->file_path;
        $this->actingAs($this->admin)
            ->delete(route('admin.materials.destroy', $updatedMaterial));

        // New file must be physically deleted
        Storage::disk('local')->assertMissing($newFilePath);
        $this->assertDatabaseMissing('course_materials', ['id' => $material->id]);
    }

    public function test_admin_can_update_file_material_metadata_without_reuploading_file()
    {
        Storage::fake('local');

        $filePath = "materials/{$this->course->id}/{$this->module->id}/manual.pdf";
        Storage::disk('local')->put($filePath, 'pdf content');

        $material = CourseMaterial::create([
            'module_id' => $this->module->id,
            'type' => 'documento',
            'title' => 'Manual original',
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.materials.update', $material), [
                'type' => 'documento',
                'title' => 'Manual actualizado',
                'description' => 'Nueva descripcion',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('course_materials', [
            'id' => $material->id,
            'title' => 'Manual actualizado',
            'file_path' => $filePath,
        ]);
        Storage::disk('local')->assertExists($filePath);
    }

    public function test_admin_must_upload_new_file_when_switching_file_material_type()
    {
        Storage::fake('local');

        $filePath = "materials/{$this->course->id}/{$this->module->id}/manual.pdf";
        Storage::disk('local')->put($filePath, 'pdf content');

        $material = CourseMaterial::create([
            'module_id' => $this->module->id,
            'type' => 'documento',
            'title' => 'Manual original',
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.materials.update', $material), [
                'type' => 'presentacion',
                'title' => 'Manual como presentacion',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('file');
        $this->assertEquals('documento', $material->fresh()->type);
        Storage::disk('local')->assertExists($filePath);
    }

    public function test_admin_validates_replacement_file_when_type_is_not_submitted()
    {
        Storage::fake('local');

        $filePath = "materials/{$this->course->id}/{$this->module->id}/manual.pdf";
        Storage::disk('local')->put($filePath, 'pdf content');

        $material = CourseMaterial::create([
            'module_id' => $this->module->id,
            'type' => 'documento',
            'title' => 'Manual original',
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.materials.update', $material), [
                'title' => 'Manual con archivo invalido',
                'file' => UploadedFile::fake()->create('payload.exe', 10, 'application/octet-stream'),
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('file');
        $this->assertEquals('Manual original', $material->fresh()->title);
        Storage::disk('local')->assertExists($filePath);
    }

    public function test_admin_cannot_upload_file_to_text_material()
    {
        Storage::fake('local');

        $material = CourseMaterial::create([
            'module_id' => $this->module->id,
            'type' => 'texto',
            'title' => 'Lectura original',
            'content' => '<p>Contenido inicial</p>',
            'order' => 1,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.materials.update', $material), [
                'title' => 'Lectura con archivo invalido',
                'file' => UploadedFile::fake()->create('manual.pdf', 10, 'application/pdf'),
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('file');
        $this->assertNull($material->fresh()->file_path);
        $this->assertEquals('Lectura original', $material->fresh()->title);
    }
}
