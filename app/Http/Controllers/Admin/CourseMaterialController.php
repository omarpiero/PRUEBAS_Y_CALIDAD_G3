<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseModule;
use App\Models\AuditLog;
use App\Http\Requests\Admin\StoreCourseMaterialRequest;
use App\Http\Requests\Admin\UpdateCourseMaterialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseMaterialController extends Controller
{
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
     * Store a newly created material in storage.
     */
    public function store(StoreCourseMaterialRequest $request)
    {
        $data = $request->validated();
        $module = CourseModule::with('course')->findOrFail($data['module_id']);
        $this->authorizeCourseOwnership($module->course);
        $courseId = $module->course_id;

        // Auto-increment order if not specified
        if (!isset($data['order']) || is_null($data['order'])) {
            $nextOrder = CourseMaterial::where('module_id', $data['module_id'])->max('order') + 1;
            $data['order'] = $nextOrder;
        }

        // Handle File Upload based on material type
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = Str::random(40) . '.' . $extension;

            // Save in local (private) storage
            $path = $file->storeAs("materials/{$courseId}/{$module->id}", $filename, 'local');

            $data['file_path'] = $path;
            $data['file_type'] = $file->getMimeType();

            // For uploaded videos, set duration if available
            if ($data['type'] === 'video') {
                $data['video_source'] = 'upload';
            }
        }

        // Handle Rich Text formatting & sanitization
        if ($data['type'] === 'texto' && isset($data['content'])) {
            $data['content'] = $this->sanitizeHtml($data['content']);
        }

        // Handle checkbox boolean cast
        $data['is_downloadable'] = $request->has('is_downloadable');

        $material = CourseMaterial::create($data);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_material',
            'entity_type' => CourseMaterial::class,
            'entity_id' => $material->id,
            'new_values' => $material->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Material creado exitosamente.');
    }

    public function update(UpdateCourseMaterialRequest $request, CourseMaterial $material)
    {
        $material->loadMissing('module.course');
        $this->authorizeCourseOwnership($material->module->course);

        $data = $request->validated();
        $oldValues = $material->toArray();

        if (isset($data['module_id']) && (int) $data['module_id'] !== (int) $material->module_id) {
            $targetModule = CourseModule::with('course')->findOrFail($data['module_id']);
            $this->authorizeCourseOwnership($targetModule->course);
        }

        $module = $material->module;
        $courseId = $module->course_id;

        $type = $data['type'] ?? $material->type;

        // Handle File Upload replacement
        if ($request->hasFile('file')) {
            // Physically delete old file if it exists
            if ($material->file_path) {
                Storage::disk('local')->delete($material->file_path);
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::random(40) . '.' . $extension;

            // Save in local (private) storage
            $path = $file->storeAs("materials/{$courseId}/{$module->id}", $filename, 'local');

            $data['file_path'] = $path;
            $data['file_type'] = $file->getMimeType();
        }

        // Perform cleanups based on the resolved type
        if ($type === 'video') {
            $source = $data['video_source'] ?? $material->video_source;
            if ($source === 'youtube' || $source === 'vimeo') {
                if ($material->file_path) {
                    Storage::disk('local')->delete($material->file_path);
                }
                $data['file_path'] = null;
                $data['file_type'] = null;
            } else {
                $data['video_url'] = null;
            }
            $data['content'] = null;
        } elseif (in_array($type, ['documento', 'presentacion', 'recurso'])) {
            $data['video_url'] = null;
            $data['video_source'] = null;
            $data['duration_minutes'] = null;
            $data['content'] = null;
        } elseif ($type === 'texto') {
            if ($material->file_path) {
                Storage::disk('local')->delete($material->file_path);
            }
            $data['file_path'] = null;
            $data['file_type'] = null;
            $data['video_url'] = null;
            $data['video_source'] = null;
            $data['duration_minutes'] = null;
        }

        // Handle Rich Text formatting & sanitization
        if ($type === 'texto' && isset($data['content'])) {
            $data['content'] = $this->sanitizeHtml($data['content']);
        }

        // Handle checkbox boolean cast
        $data['is_downloadable'] = $request->has('is_downloadable');

        $material->update($data);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_material',
            'entity_type' => CourseMaterial::class,
            'entity_id' => $material->id,
            'old_values' => $oldValues,
            'new_values' => $material->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Material actualizado exitosamente.');
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy(Request $request, CourseMaterial $material)
    {
        $material->loadMissing('module.course');
        $this->authorizeCourseOwnership($material->module->course);

        $oldValues = $material->toArray();

        // Physically delete file from private storage
        if ($material->file_path) {
            Storage::disk('local')->delete($material->file_path);
        }

        $material->delete();

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_material',
            'entity_type' => CourseMaterial::class,
            'entity_id' => $material->id,
            'old_values' => $oldValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Material eliminado exitosamente.');
    }

    /**
     * Sanitize HTML to prevent XSS attacks in rich text components (Quill).
     */
    protected function sanitizeHtml(string $html): string
    {
        $allowedTags = '<p><h2><h3><h4><h5><h6><strong><em><u><s><ul><ol><li><a><pre><code><br><blockquote>';
        $clean = strip_tags($html, $allowedTags);

        // Prevent JS injection in href attributes
        $clean = preg_replace('/href="javascript:[^"]*"/i', 'href="#"', $clean);
        // Remove on-event attributes
        $clean = preg_replace('/(onload|onerror|onclick|onmouseover|onfocus|onblur|onchange)="[^"]*"/i', '', $clean);

        return $clean;
    }
}
