<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentCourseController extends Controller
{
    /**
     * Show the student classroom/player for a course.
     */
    public function show(Course $course)
    {
        $user = auth()->user();

        // Retrieve the enrollment
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        // Check if enrolled
        if (!$enrollment) {
            abort(403, 'No estás inscrito en este curso.');
        }

        // Check if active or completed (S5-05: Suspended access)
        if ($enrollment->status === Enrollment::STATUS_SUSPENDED) {
            abort(403, 'Tu acceso a este curso ha sido suspendido.');
        }

        if ($enrollment->status === Enrollment::STATUS_PENDING) {
            abort(403, 'Tu inscripción a este curso está pendiente de pago.');
        }

        // Update last accessed timestamp
        $enrollment->update([
            'last_accessed_at' => now(),
        ]);

        // Load modules with materials
        $course->load(['modules.materials' => function ($q) {
            $q->orderBy('order');
        }]);

        // Get list of completed material IDs for this user
        $completedMaterialIds = $user->completedMaterials()
            ->whereHas('module', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->pluck('course_materials.id')
            ->toArray();

        return view('student.aula', compact('course', 'enrollment', 'completedMaterialIds'));
    }

    /**
     * Mark a course material as completed or incomplete.
     */
    public function completeMaterial(Request $request, Course $course, CourseMaterial $material): JsonResponse
    {
        $user = auth()->user();

        // Retrieve active enrollment
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment || !in_array($enrollment->status, [Enrollment::STATUS_ACTIVE, Enrollment::STATUS_COMPLETED])) {
            return response()->json([
                'ok' => false,
                'msg' => 'No tienes acceso activo a este curso.'
            ], 403);
        }

        // Verify material belongs to this course
        if ($material->module->course_id !== $course->id) {
            return response()->json([
                'ok' => false,
                'msg' => 'El material no pertenece a este curso.'
            ], 400);
        }

        // Toggle completion status in pivot table
        $isCompletedNow = false;
        $exists = $user->completedMaterials()->where('course_material_id', $material->id)->exists();

        if ($exists) {
            $user->completedMaterials()->detach($material->id);
        } else {
            $user->completedMaterials()->attach($material->id);
            $isCompletedNow = true;
        }

        // Calculate progress
        $totalMaterials = $course->materials()->count();
        $progress = 0;

        if ($totalMaterials > 0) {
            $completedCount = $user->completedMaterials()
                ->whereHas('module', function ($q) use ($course) {
                    $q->where('course_id', $course->id);
                })
                ->count();

            $progress = round(($completedCount / $totalMaterials) * 100, 2);
        }

        // Handle enrollment status progression
        $enrollment->progress = $progress;

        if ($progress >= 100) {
            $enrollment->status = Enrollment::STATUS_COMPLETED;
            $enrollment->completed_at = now();
        } else {
            // Revert back to active if previously marked completed but uncompleted a lesson
            if ($enrollment->status === Enrollment::STATUS_COMPLETED) {
                $enrollment->status = Enrollment::STATUS_ACTIVE;
                $enrollment->completed_at = null;
            }
        }

        $enrollment->save();

        return response()->json([
            'ok' => true,
            'completed' => $isCompletedNow,
            'progress' => $progress,
            'status' => $enrollment->status,
        ]);
    }

    /**
     * Serve a private material file securely.
     */
    public function serveFile(Request $request, Course $course, CourseMaterial $material)
    {
        $user = auth()->user();

        // Retrieve active enrollment
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment || !in_array($enrollment->status, [Enrollment::STATUS_ACTIVE, Enrollment::STATUS_COMPLETED])) {
            abort(403, 'No tienes acceso activo a este curso.');
        }

        // Verify material belongs to this course
        if ($material->module->course_id !== $course->id) {
            abort(404, 'Material no encontrado.');
        }

        // Check if file path exists
        if (!$material->file_path || !Storage::disk('local')->exists($material->file_path)) {
            abort(404, 'El archivo no existe en el disco.');
        }

        // Force download if parameter is present, or if it is a downloadable resource
        $isDownload = $request->query('download') == 1 || $material->type === 'recurso' || $material->is_downloadable;
        $extension = pathinfo($material->file_path, PATHINFO_EXTENSION);
        $fileName = Str::slug($material->title) . '.' . $extension;

        if ($isDownload) {
            return Storage::disk('local')->download($material->file_path, $fileName);
        }

        return Storage::disk('local')->response($material->file_path);
    }
}
