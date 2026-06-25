<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoLmsSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', 'test@example.com')->first();
        $course = Course::published()->orderBy('id')->first();

        if (! $student || ! $course) {
            return;
        }

        $coupon = Coupon::updateOrCreate(
            ['code' => 'DEMO20'],
            [
                'type' => 'porcentaje',
                'value' => 20,
                'start_date' => now()->subDay()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'usage_limit' => 100,
                'times_used' => 1,
                'is_active' => true,
            ]
        );

        $subtotal = (float) $course->effective_price;
        $discount = $coupon->calculateDiscount($subtotal);
        $total = max(0, $subtotal - $discount);

        $sale = Sale::updateOrCreate(
            [
                'user_id' => $student->id,
                'notes' => 'Venta demo generada por DatabaseSeeder.',
            ],
            [
                'coupon_id' => $coupon->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => 'tarjeta',
                'payment_status' => 'pagado',
                'paid_at' => now(),
            ]
        );

        SaleItem::updateOrCreate(
            [
                'sale_id' => $sale->id,
                'course_id' => $course->id,
            ],
            [
                'price' => $subtotal,
            ]
        );

        Enrollment::updateOrCreate(
            [
                'user_id' => $student->id,
                'course_id' => $course->id,
            ],
            [
                'status' => Enrollment::STATUS_ACTIVE,
                'progress' => 25,
                'enrolled_at' => now(),
                'last_accessed_at' => now()->subDay(),
                'total_time_minutes' => 45,
            ]
        );
    }
}
