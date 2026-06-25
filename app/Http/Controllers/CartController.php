<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $course = Course::findOrFail($data['course_id']);

        // Check if the course is published
        if ($course->status !== 'publicado') {
            return response()->json(['ok' => false, 'msg' => 'Este curso no está disponible para inscripción.'], 422);
        }

        $cart = session()->get('cart', []);

        $exists = collect($cart)->contains('course_id', $course->id);
        if ($exists) {
            return response()->json(['ok' => false, 'msg' => 'Este curso ya está en tu carrito.'], 422);
        }

        $cart[] = [
            'course_id'   => $course->id,
            'course_name' => $course->name,
            'level'       => $course->level,
            'price'       => (float) $course->effective_price,
        ];

        session()->put('cart', $cart);

        return response()->json([
            'ok'    => true,
            'msg'   => '¡Curso agregado al carrito!',
            'count' => count($cart),
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'course_id' => ['required', 'integer']
        ]);

        $courseId = (int) $request->input('course_id');
        $cart = collect(session()->get('cart', []))
            ->reject(fn ($item) => (int)$item['course_id'] === $courseId)
            ->values()
            ->all();

        session()->put('cart', $cart);

        return response()->json(['ok' => true, 'count' => count($cart)]);
    }

    public function index()
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('status', 'Inicia sesión para ver tu carrito.');
        }

        $cart = session()->get('cart', []);
        $subtotal = collect($cart)->sum('price');
        $discount = 0;
        $coupon = null;

        if (session()->has('coupon_code')) {
            $couponCode = session()->get('coupon_code');
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->is_valid) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                session()->forget('coupon_code');
            }
        }

        $total = $subtotal - $discount;

        return view('checkout', compact('cart', 'subtotal', 'discount', 'total', 'coupon'));
    }

    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $code = trim($request->input('code'));
        $coupon = \App\Models\Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'ok' => false,
                'msg' => 'El cupón ingresado no existe.'
            ], 422);
        }

        if (!$coupon->is_valid) {
            return response()->json([
                'ok' => false,
                'msg' => 'El cupón no es válido, expiró o superó su límite de uso.'
            ], 422);
        }

        session()->put('coupon_code', $coupon->code);

        $cart = session()->get('cart', []);
        $subtotal = collect($cart)->sum('price');
        $discount = $coupon->calculateDiscount($subtotal);
        $total = $subtotal - $discount;

        return response()->json([
            'ok' => true,
            'msg' => '¡Cupón aplicado con éxito!',
            'code' => $coupon->code,
            'discount' => $discount,
            'total' => $total,
        ]);
    }

    public function removeCoupon(): JsonResponse
    {
        session()->forget('coupon_code');

        $cart = session()->get('cart', []);
        $subtotal = collect($cart)->sum('price');

        return response()->json([
            'ok' => true,
            'msg' => 'Cupón removido.',
            'total' => $subtotal,
        ]);
    }
}
