<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::published()->with(['category', 'instructor']);

        // Filter by Level
        if ($request->filled('level') && in_array($request->input('level'), ['basico', 'intermedio', 'avanzado'])) {
            $query->where('level', $request->input('level'));
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by Search Query
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        $courses = $query->orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        return view('cursos', compact('courses', 'categories'));
    }

    public function show(string $slug)
    {
        $course = Course::where('slug', $slug)
            ->with(['category', 'instructor', 'modules' => function ($q) {
                $q->where('status', 'activo')->with('materials');
            }])
            ->firstOrFail();

        // Prevent non-admin access to draft or archived courses
        if ($course->status !== 'publicado') {
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                abort(404);
            }
        }

        return view('curso-detalle', compact('course'));
    }
}
