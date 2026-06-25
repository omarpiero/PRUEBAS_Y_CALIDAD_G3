<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $already = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $data['course_id'])
            ->exists();

        if ($already) {
            return response()->json(['ok' => false, 'msg' => 'Ya estás inscrito en este curso.'], 422);
        }

        Enrollment::create([
            'user_id'     => auth()->id(),
            'course_id'   => $data['course_id'],
            'status'      => 'activo',
            'enrolled_at' => now(),
        ]);

        return response()->json(['ok' => true, 'msg' => '¡Inscripción exitosa! Ve a Mi Cuenta para verla.']);
    }
}
