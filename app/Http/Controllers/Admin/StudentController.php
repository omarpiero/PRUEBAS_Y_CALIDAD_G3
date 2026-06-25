<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students with filters.
     */
    public function index(Request $request)
    {
        $query = User::whereHas('enrollments');

        // Search name, email or DNI
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        // Filter by course
        if ($request->filled('course_id')) {
            $courseId = $request->input('course_id');
            $query->whereHas('enrollments', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
        }

        // Filter by enrollment status
        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->whereHas('enrollments', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $students = $query->with(['enrollments.course'])
            ->paginate(15)
            ->withQueryString();

        $courses = Course::orderBy('name')->get();

        return view('admin.students.index', compact('students', 'courses'));
    }

    /**
     * Display the student profile and academic details.
     */
    public function show(User $student)
    {
        $student->load(['enrollments.course', 'sales.saleItems.course']);

        return view('admin.students.show', compact('student'));
    }

    /**
     * Suspend student access to a specific course.
     */
    public function suspend(Request $request, User $student, Course $course)
    {
        $enrollment = Enrollment::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $oldValues = $enrollment->toArray();
        $enrollment->update(['status' => Enrollment::STATUS_SUSPENDED]);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'suspend_student_access',
            'entity_type' => Enrollment::class,
            'entity_id' => $enrollment->id,
            'old_values' => $oldValues,
            'new_values' => $enrollment->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');

        return back()->with('success', "Acceso suspendido para el alumno {$student->name} en el curso {$course->name}.");
    }

    /**
     * Reactivate student access to a specific course.
     */
    public function reactivate(Request $request, User $student, Course $course)
    {
        $enrollment = Enrollment::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $oldValues = $enrollment->toArray();

        // If progress is already 100%, mark as completado, else active
        $newStatus = ($enrollment->progress >= 100) ? Enrollment::STATUS_COMPLETED : Enrollment::STATUS_ACTIVE;
        $enrollment->update(['status' => $newStatus]);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'reactivate_student_access',
            'entity_type' => Enrollment::class,
            'entity_id' => $enrollment->id,
            'old_values' => $oldValues,
            'new_values' => $enrollment->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');

        return back()->with('success', "Acceso reactivado para el alumno {$student->name} en el curso {$course->name}.");
    }

    /**
     * Reset student progress in a specific course.
     */
    public function resetProgress(Request $request, User $student, Course $course)
    {
        $enrollment = Enrollment::where('user_id', $student->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $oldValues = $enrollment->toArray();

        // 1. Delete all completed materials for this course by this user
        $materialIds = $course->materials()->pluck('course_materials.id');
        $student->completedMaterials()->detach($materialIds);

        // 2. Reset enrollment progress
        $enrollment->update([
            'progress' => 0.00,
            'status' => Enrollment::STATUS_ACTIVE,
            'completed_at' => null,
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'reset_student_progress',
            'entity_type' => Enrollment::class,
            'entity_id' => $enrollment->id,
            'old_values' => $oldValues,
            'new_values' => $enrollment->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');

        return back()->with('success', "Progreso reiniciado para el alumno {$student->name} en el curso {$course->name}.");
    }
}
