<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Models\Role;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Tests\TestCase;

class AdminDashboardAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles are seeded (we can just create them if seeders aren't run automatically)
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Admin']);
        $studentRole = Role::firstOrCreate(['name' => 'estudiante'], ['display_name' => 'Estudiante']);
        Role::firstOrCreate(['name' => 'instructor'], ['display_name' => 'Instructor']);

        // Users
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->student = User::factory()->create();
        $this->student->roles()->attach($studentRole->id);

        // Category
        $this->category = Category::create([
            'name' => 'Inocuidad',
            'slug' => 'inocuidad',
            'description' => 'Inocuidad alimentaria',
            'icon' => '🛡️',
            'order' => 1,
        ]);
    }

    public function test_non_admin_cannot_access_dashboard_analytics()
    {
        $this->actingAs($this->student)
            ->get(route('admin.dashboard'))
            ->assertStatus(403);
    }

    public function test_admin_can_view_dashboard_analytics_kpis()
    {
        // 1. Create courses (2 active, 1 draft)
        $courseA = Course::create([
            'category_id' => $this->category->id,
            'name' => 'HACCP Basico',
            'slug' => 'haccp-basico',
            'short_description' => 'HACCP Basico',
            'level' => 'basico',
            'status' => 'publicado',
            'price' => 150.00,
            'duration_weeks' => 4,
        ]);

        $courseB = Course::create([
            'category_id' => $this->category->id,
            'name' => 'BPM Avanzado',
            'slug' => 'bpm-avanzado',
            'short_description' => 'BPM Avanzado',
            'level' => 'avanzado',
            'status' => 'publicado',
            'price' => 100.00,
            'duration_weeks' => 6,
        ]);

        $courseC = Course::create([
            'category_id' => $this->category->id,
            'name' => 'ISO 22000 Draft',
            'slug' => 'iso-22000-draft',
            'short_description' => 'ISO 22000 Draft',
            'level' => 'intermedio',
            'status' => 'borrador',
            'price' => 200.00,
            'duration_weeks' => 8,
        ]);

        // 2. Create another student
        $student2 = User::factory()->create();
        $studentRole = Role::where('name', 'estudiante')->first();
        $student2->roles()->attach($studentRole->id);

        // 3. Create sales (paid)
        $sale1 = Sale::create([
            'user_id' => $this->student->id,
            'subtotal' => 150.00,
            'discount' => 0.00,
            'total' => 150.00,
            'payment_method' => 'tarjeta',
            'payment_status' => 'pagado',
            'paid_at' => now(),
        ]);
        SaleItem::create(['sale_id' => $sale1->id, 'course_id' => $courseA->id, 'price' => 150.00]);

        $sale2 = Sale::create([
            'user_id' => $student2->id,
            'subtotal' => 100.00,
            'discount' => 0.00,
            'total' => 100.00,
            'payment_method' => 'tarjeta',
            'payment_status' => 'pagado',
            'paid_at' => now(),
        ]);
        SaleItem::create(['sale_id' => $sale2->id, 'course_id' => $courseB->id, 'price' => 100.00]);

        // 4. Create enrollments (1 active, 1 completed)
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $courseA->id,
            'status' => 'activo',
            'progress' => 30.00,
        ]);

        Enrollment::create([
            'user_id' => $student2->id,
            'course_id' => $courseB->id,
            'status' => 'completado',
            'progress' => 100.00,
            'completed_at' => now(),
        ]);

        // 5. Access dashboard
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);

        // Assert view variables
        $response->assertViewHas('stats', function ($stats) use ($courseA) {
            return $stats['total_courses'] === 3 &&
                   $stats['active_courses'] === 2 &&
                   $stats['inactive_courses'] === 1 &&
                   $stats['total_students'] === 2 &&
                   $stats['sales_total_historic'] == 250.00 &&
                   $stats['average_ticket'] == 125.00 &&
                   $stats['completion_rate'] == 50.00 &&
                   ($stats['best_selling_course'] === 'HACCP Basico' || $stats['best_selling_course'] === 'BPM Avanzado');
        });
    }

    public function test_metric_caching_and_invalidation()
    {
        Cache::flush();

        // 1. Initial Course
        $course = Course::create([
            'category_id' => $this->category->id,
            'name' => 'Initial Course',
            'slug' => 'initial-course',
            'short_description' => 'Initial',
            'level' => 'basico',
            'status' => 'publicado',
            'price' => 100.00,
            'duration_weeks' => 4,
        ]);

        // Access dashboard to cache results
        $this->actingAs($this->admin)->get(route('admin.dashboard'));

        // Assert cache was created
        $this->assertTrue(Cache::has('admin_dashboard_stats'));
        $cachedStats = Cache::get('admin_dashboard_stats');
        $this->assertEquals(1, $cachedStats['total_courses']);

        // 2. Create another course in DB (bypassing dashboard cache load)
        Course::create([
            'category_id' => $this->category->id,
            'name' => 'Cached Course',
            'slug' => 'cached-course',
            'short_description' => 'Cached',
            'level' => 'basico',
            'status' => 'publicado',
            'price' => 100.00,
            'duration_weeks' => 4,
        ]);

        // Access dashboard again (should load from cache, so total_courses should still be 1)
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertViewHas('stats', function ($stats) {
            return $stats['total_courses'] === 1;
        });

        // 3. Clear cache manually or trigger a payment that invalidates it
        // Simulating the checkout request
        session()->put('cart', [
            $course->id => [
                'course_id' => $course->id,
                'name' => $course->name,
                'price' => $course->price,
            ]
        ]);

        $this->mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
            $mock->shouldReceive('createCheckoutSession')
                ->once()
                ->andReturn('https://checkout.stripe.com/c/pay/cs_test_dashboard');
        });

        $response = $this->actingAs($this->student)->post(route('pago.procesar'));

        $response->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_dashboard');
        $this->assertTrue(Cache::has('admin_dashboard_stats'));

        $sale = Sale::where('user_id', $this->student->id)
            ->where('payment_status', 'pendiente')
            ->latest()
            ->firstOrFail();

        $fakeSession = StripeCheckoutSession::constructFrom([
            'id' => 'cs_test_dashboard',
            'object' => 'checkout.session',
            'payment_status' => 'paid',
            'metadata' => ['sale_id' => (string) $sale->id],
        ]);

        $this->mock(StripeService::class, function ($mock) use ($fakeSession) {
            $mock->shouldReceive('retrieveSession')
                ->with('cs_test_dashboard')
                ->andReturn($fakeSession);
        });

        $this->actingAs($this->student)
            ->get(route('pago.confirmar', ['session_id' => 'cs_test_dashboard']))
            ->assertRedirect(route('pago.exito'));

        // Assert cache is now cleared
        $this->assertFalse(Cache::has('admin_dashboard_stats'));

        // Access dashboard again (should recalculate and show total_courses as 2)
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertViewHas('stats', function ($stats) {
            return $stats['total_courses'] === 2;
        });
    }
}
