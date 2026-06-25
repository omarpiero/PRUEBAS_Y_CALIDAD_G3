<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Role;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsReleaseReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_lms_factories_create_a_complete_purchase_graph(): void
    {
        $student = User::factory()->create();
        $course = Course::factory()->published()->create();
        $module = CourseModule::factory()->create(['course_id' => $course->id]);
        $material = CourseMaterial::factory()->youtube()->create(['module_id' => $module->id]);
        $coupon = Coupon::factory()->create(['code' => 'READY10', 'type' => 'porcentaje', 'value' => 10]);
        $sale = Sale::factory()->create([
            'user_id' => $student->id,
            'coupon_id' => $coupon->id,
            'subtotal' => 200,
            'discount' => 20,
            'total' => 180,
        ]);

        SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'course_id' => $course->id,
            'price' => 200,
        ]);

        Enrollment::factory()->create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => Enrollment::STATUS_ACTIVE,
        ]);

        $this->assertTrue($course->modules()->whereKey($module->id)->exists());
        $this->assertTrue($module->materials()->whereKey($material->id)->exists());
        $this->assertTrue($sale->items()->where('course_id', $course->id)->exists());
        $this->assertTrue($student->enrollments()->where('course_id', $course->id)->exists());
    }

    public function test_database_seeder_provides_idempotent_demo_lms_data(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(4, Role::count());
        $this->assertSame(6, Category::count());
        $this->assertSame(9, Course::count());
        $this->assertSame(1, Coupon::where('code', 'DEMO20')->count());
        $this->assertSame(1, Setting::where('key', 'company_name')->count());

        $this->assertGreaterThanOrEqual(9, Course::published()->count());
        $this->assertGreaterThan(0, CourseModule::count());
        $this->assertGreaterThan(0, CourseMaterial::count());
        $this->assertGreaterThan(0, Sale::paid()->count());
        $this->assertGreaterThan(0, SaleItem::count());
        $this->assertGreaterThan(0, Enrollment::where('status', Enrollment::STATUS_ACTIVE)->count());
    }
}
