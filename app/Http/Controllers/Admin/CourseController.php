<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use App\Models\AuditLog;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Services\CoursePublishingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    protected CoursePublishingService $publishingService;

    public function __construct(CoursePublishingService $publishingService)
    {
        $this->publishingService = $publishingService;
    }

    private function authorizeCourseOwnership(Course $course): void
    {
        $user = request()->user();

        if (! $user) {
            abort(401);
        }

        if ($user->isAdmin()) {
            return;
        }

        if ($user->hasRole('instructor') && (int) $course->instructor_id === (int) $user->id) {
            return;
        }

        abort(403, 'No tienes permiso para gestionar este curso.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Course::with(['category', 'instructor'])
            ->withCount(['enrollments', 'modules']);

        if (! $request->user()->isAdmin() && $request->user()->hasRole('instructor')) {
            $query->where('instructor_id', $request->user()->id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Level filter
        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $courses = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.courses.index', compact('courses', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $instructors = $request->user()->isAdmin()
            ? User::where('is_admin', true)
                ->orWhereHas('roles', function ($q) {
                    $q->where('name', 'instructor');
                })
                ->get()
            : collect([$request->user()]);

        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();

        // Sanitize course description
        if (isset($data['description'])) {
            $data['description'] = $this->sanitizeHtml($data['description']);
        }

        // Handle cover image file upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $data['cover_image'] = Storage::disk('public')->url($path);
        }

        // Ensure checkbox is handled
        $data['is_featured'] = $request->has('is_featured');

        if (! $request->user()->isAdmin()) {
            $data['instructor_id'] = $request->user()->id;
            $data['status'] = 'borrador';
            $data['is_featured'] = false;
        }

        $course = Course::create($data);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_course',
            'entity_type' => Course::class,
            'entity_id' => $course->id,
            'new_values' => $course->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $this->authorizeCourseOwnership($course);

        return redirect()->route('admin.courses.edit', $course);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->authorizeCourseOwnership($course);

        $course->load(['modules.materials']);
        $categories = Category::orderBy('name')->get();
        $instructors = request()->user()->isAdmin()
            ? User::where('is_admin', true)
                ->orWhereHas('roles', function ($q) {
                    $q->where('name', 'instructor');
                })
                ->get()
            : collect([request()->user()]);

        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $this->authorizeCourseOwnership($course);

        $data = $request->validated();
        $oldValues = $course->toArray();

        // Sanitize course description
        if (isset($data['description'])) {
            $data['description'] = $this->sanitizeHtml($data['description']);
        }

        // Handle cover image replacement
        if ($request->hasFile('cover_image')) {
            // Delete old file if exists
            if ($course->cover_image) {
                $oldPath = str_replace(url('storage') . '/', '', $course->cover_image);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('cover_image')->store('covers', 'public');
            $data['cover_image'] = Storage::disk('public')->url($path);
        }

        $data['is_featured'] = $request->has('is_featured');

        if (! $request->user()->isAdmin()) {
            $data['instructor_id'] = $course->instructor_id ?: $request->user()->id;
            $data['is_featured'] = (bool) $course->is_featured;
        }

        $course->update($data);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_course',
            'entity_type' => Course::class,
            'entity_id' => $course->id,
            'old_values' => $oldValues,
            'new_values' => $course->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Course $course)
    {
        $this->authorizeCourseOwnership($course);

        // Protect against deleting courses with active/completed enrollments
        if ($course->enrollments()->whereIn('status', ['activo', 'completado'])->exists()) {
            return back()->with('error', 'No se puede eliminar el curso porque tiene estudiantes matriculados activos.');
        }

        $oldValues = $course->toArray();

        // Delete cover image file
        if ($course->cover_image) {
            $oldPath = str_replace(url('storage') . '/', '', $course->cover_image);
            Storage::disk('public')->delete($oldPath);
        }

        // Delete material files physically
        foreach ($course->modules as $module) {
            foreach ($module->materials as $material) {
                if ($material->file_path) {
                    Storage::disk('local')->delete($material->file_path);
                }
            }
        }

        // The cascade delete in the DB handles deleting modules and materials
        $course->delete();

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_course',
            'entity_type' => Course::class,
            'entity_id' => $course->id,
            'old_values' => $oldValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso eliminado exitosamente.');
    }

    /**
     * Publish the specified course.
     */
    public function publish(Request $request, Course $course)
    {
        $this->authorizeCourseOwnership($course);

        $errors = $this->publishingService->canPublish($course);

        if (count($errors) > 0) {
            return back()->with('error', 'No se puede publicar el curso: ' . implode(' ', $errors));
        }

        $oldValues = $course->toArray();
        $this->publishingService->publish($course);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'publish_course',
            'entity_type' => Course::class,
            'entity_id' => $course->id,
            'old_values' => $oldValues,
            'new_values' => $course->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Curso publicado exitosamente.');
    }

    /**
     * Unpublish the specified course.
     */
    public function unpublish(Request $request, Course $course)
    {
        $this->authorizeCourseOwnership($course);

        $oldValues = $course->toArray();
        $course->update([
            'status' => 'borrador',
            'published_at' => null,
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'unpublish_course',
            'entity_type' => Course::class,
            'entity_id' => $course->id,
            'old_values' => $oldValues,
            'new_values' => $course->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Curso despublicado (guardado como borrador).');
    }

    /**
     * Duplicate the specified course.
     */
    public function duplicate(Request $request, Course $course)
    {
        $this->authorizeCourseOwnership($course);

        $course->load(['modules.materials']);

        // Replicate course
        $newCourse = $course->replicate();
        $newCourse->name = $course->name . ' (Copia)';
        $newCourse->status = 'borrador';
        $newCourse->published_at = null;
        $newCourse->is_featured = false;

        // Cover image cloning
        if ($course->cover_image && !str_starts_with($course->cover_image, 'http')) {
            $oldPath = str_replace(url('storage') . '/', '', $course->cover_image);
            $ext = pathinfo($oldPath, PATHINFO_EXTENSION);
            $newCoverPath = 'covers/' . Str::random(40) . '.' . $ext;
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->copy($oldPath, $newCoverPath);
                $newCourse->cover_image = Storage::disk('public')->url($newCoverPath);
            }
        }

        $newCourse->save(); // Generates slug automatically via model boot

        // Duplicate modules and materials
        foreach ($course->modules as $module) {
            $newModule = $module->replicate();
            $newModule->course_id = $newCourse->id;
            $newModule->save();

            foreach ($module->materials as $material) {
                $newMaterial = $material->replicate();
                $newMaterial->module_id = $newModule->id;

                // Copy files physically in storage/app/private
                if ($material->file_path && Storage::disk('local')->exists($material->file_path)) {
                    $ext = pathinfo($material->file_path, PATHINFO_EXTENSION);
                    $newFilePath = 'materials/' . $newCourse->id . '/' . $newModule->id . '/' . Str::random(40) . '.' . $ext;
                    Storage::disk('local')->copy($material->file_path, $newFilePath);
                    $newMaterial->file_path = $newFilePath;
                }

                $newMaterial->save();
            }
        }

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'duplicate_course',
            'entity_type' => Course::class,
            'entity_id' => $newCourse->id,
            'new_values' => $newCourse->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso duplicado exitosamente como Borrador.');
    }

    /**
     * Sanitize HTML to prevent XSS attacks in course descriptions.
     */
    protected function sanitizeHtml(string $html): string
    {
        $allowedTags = '<p><h2><h3><h4><h5><h6><strong><em><u><s><ul><ol><li><a><pre><code><br><blockquote><img><iframe>';
        $clean = strip_tags($html, $allowedTags);

        // Prevent JS injection in href attributes
        $clean = preg_replace('/href="javascript:[^"]*"/i', 'href="#"', $clean);
        // Remove on-event attributes
        $clean = preg_replace('/(onload|onerror|onclick|onmouseover|onfocus|onblur|onchange)="[^"]*"/i', '', $clean);

        return $clean;
    }
}
