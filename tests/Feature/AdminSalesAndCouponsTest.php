<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Coupon;
use App\Models\Enrollment;
use App\Models\Sale;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Tests\TestCase;

class AdminSalesAndCouponsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $student;
    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        // Users
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->student = User::factory()->create();

        // Category
        $category = Category::create([
            'name' => 'Inocuidad',
            'slug' => 'inocuidad',
            'description' => 'Inocuidad alimentaria',
            'icon' => '🛡️',
            'order' => 1,
        ]);

        // Course
        $this->course = Course::create([
            'category_id' => $category->id,
            'name' => 'HACCP Avanzado',
            'slug' => 'haccp-avanzado',
            'short_description' => 'HACCP',
            'level' => 'avanzado',
            'status' => 'publicado',
            'price' => 150.00,
            'duration_weeks' => 8,
        ]);
    }

    public function test_non_admin_cannot_access_sales_and_coupons_admin()
    {
        // Students index
        $this->actingAs($this->student)->get(route('admin.students.index'))->assertStatus(403);
        
        // Sales index
        $this->actingAs($this->student)->get(route('admin.sales.index'))->assertStatus(403);

        // Coupons index
        $this->actingAs($this->student)->get(route('admin.coupons.index'))->assertStatus(403);
    }

    public function test_admin_can_manage_students_and_enrollments()
    {
        // Enroll student to course
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'activo',
            'progress' => 50.00,
        ]);

        // 1. View students list
        $response = $this->actingAs($this->admin)->get(route('admin.students.index'));
        $response->assertStatus(200);
        $response->assertSee($this->student->name);

        // 2. View student show page
        $response = $this->actingAs($this->admin)->get(route('admin.students.show', $this->student));
        $response->assertStatus(200);
        $response->assertSee('Matr&iacute;culas y Cursos', false);

        // 3. Suspend student access
        $response = $this->actingAs($this->admin)->post(route('admin.students.suspend', [$this->student, $this->course]));
        $response->assertRedirect();
        $this->assertEquals('suspendido', $enrollment->fresh()->status);

        // 4. Reactivate student access
        $response = $this->actingAs($this->admin)->post(route('admin.students.reactivate', [$this->student, $this->course]));
        $response->assertRedirect();
        $this->assertEquals('activo', $enrollment->fresh()->status);

        // 5. Reset progress
        $response = $this->actingAs($this->admin)->post(route('admin.students.reset', [$this->student, $this->course]));
        $response->assertRedirect();
        $this->assertEquals(0.00, $enrollment->fresh()->progress);
        $this->assertEquals('activo', $enrollment->fresh()->status);
    }

    public function test_admin_can_crud_coupons()
    {
        // 1. Create Coupon
        $response = $this->actingAs($this->admin)->post(route('admin.coupons.store'), [
            'code' => 'TEST50',
            'type' => 'porcentaje',
            'value' => 50.00,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'usage_limit' => 10,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', ['code' => 'TEST50']);

        $coupon = Coupon::where('code', 'TEST50')->first();

        // 2. Edit Coupon
        $response = $this->actingAs($this->admin)->put(route('admin.coupons.update', $coupon), [
            'code' => 'TEST50_NEW',
            'type' => 'monto_fijo',
            'value' => 20.00,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'usage_limit' => 5,
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'code' => 'TEST50_NEW',
            'type' => 'monto_fijo',
            'value' => 20.00,
        ]);

        // 3. Delete Coupon
        $response = $this->actingAs($this->admin)->delete(route('admin.coupons.destroy', $coupon));
        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }

    public function test_cart_coupon_application_rules()
    {
        // Create active valid coupons
        $pctCoupon = Coupon::create([
            'code' => 'PCT10',
            'type' => 'porcentaje',
            'value' => 10.00,
            'start_date' => now()->subDays(1)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'usage_limit' => 100,
            'is_active' => true,
        ]);

        $fixedCoupon = Coupon::create([
            'code' => 'FIXED50',
            'type' => 'monto_fijo',
            'value' => 50.00,
            'start_date' => now()->subDays(1)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'usage_limit' => 100,
            'is_active' => true,
        ]);

        $inactiveCoupon = Coupon::create([
            'code' => 'INACTIVE',
            'type' => 'porcentaje',
            'value' => 15.00,
            'start_date' => now()->subDays(1)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'usage_limit' => 100,
            'is_active' => false,
        ]);

        $expiredCoupon = Coupon::create([
            'code' => 'EXPIRED',
            'type' => 'porcentaje',
            'value' => 20.00,
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->subDays(1)->toDateString(),
            'is_active' => true,
        ]);

        $exhaustedCoupon = Coupon::create([
            'code' => 'EXHAUSTED',
            'type' => 'porcentaje',
            'value' => 20.00,
            'start_date' => now()->subDays(1)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'usage_limit' => 5,
            'times_used' => 5,
            'is_active' => true,
        ]);

        // Add course to cart in session
        $this->actingAs($this->student)->postJson('/cart/add', ['course_id' => $this->course->id]);

        // 1. Try applying invalid/inactive/expired/exhausted coupons
        $this->actingAs($this->student)->postJson(route('cart.coupon.apply'), ['code' => 'NONEXISTENT'])->assertStatus(422);
        $this->actingAs($this->student)->postJson(route('cart.coupon.apply'), ['code' => 'INACTIVE'])->assertStatus(422);
        $this->actingAs($this->student)->postJson(route('cart.coupon.apply'), ['code' => 'EXPIRED'])->assertStatus(422);
        $this->actingAs($this->student)->postJson(route('cart.coupon.apply'), ['code' => 'EXHAUSTED'])->assertStatus(422);

        // 2. Apply valid percentage coupon (PCT10)
        $response = $this->actingAs($this->student)->postJson(route('cart.coupon.apply'), ['code' => 'PCT10']);
        $response->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'code' => 'PCT10',
                'discount' => 15.00, // 10% of 150.00
                'total' => 135.00,
            ]);

        // 3. Remove coupon
        $this->actingAs($this->student)->postJson(route('cart.coupon.remove'))
            ->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'total' => 150.00,
            ]);

        // 4. Apply valid fixed coupon (FIXED50)
        $response = $this->actingAs($this->student)->postJson(route('cart.coupon.apply'), ['code' => 'FIXED50']);
        $response->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'code' => 'FIXED50',
                'discount' => 50.00,
                'total' => 100.00,
            ]);
    }

    public function test_checkout_processes_order_with_coupon()
    {
        $coupon = Coupon::create([
            'code' => 'SAVE30',
            'type' => 'porcentaje',
            'value' => 20.00, // 20%
            'start_date' => now()->subDays(1)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'is_active' => true,
        ]);

        // 1. Setup cart and apply coupon in session
        $this->actingAs($this->student)->postJson('/cart/add', ['course_id' => $this->course->id]);
        $this->actingAs($this->student)->postJson(route('cart.coupon.apply'), ['code' => 'SAVE30']);

        $this->mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
            $mock->shouldReceive('createCheckoutSession')
                ->once()
                ->andReturn('https://checkout.stripe.com/c/pay/cs_test_order');
        });

        // 2. Start Stripe checkout
        $response = $this->actingAs($this->student)->post(route('pago.procesar'));

        $response->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_order');

        // 3. Verify Sale and SaleItem exist in DB as pending until Stripe confirms payment
        $this->assertDatabaseHas('sales', [
            'user_id' => $this->student->id,
            'coupon_id' => $coupon->id,
            'subtotal' => 150.00,
            'discount' => 30.00, // 20% of 150
            'total' => 120.00,
            'payment_status' => 'pendiente',
        ]);

        $sale = Sale::where('user_id', $this->student->id)->first();

        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $sale->id,
            'course_id' => $this->course->id,
            'price' => 150.00,
        ]);

        $fakeSession = StripeCheckoutSession::constructFrom([
            'id' => 'cs_test_order',
            'object' => 'checkout.session',
            'payment_status' => 'paid',
            'metadata' => ['sale_id' => (string) $sale->id],
        ]);

        $this->mock(StripeService::class, function ($mock) use ($fakeSession) {
            $mock->shouldReceive('retrieveSession')
                ->with('cs_test_order')
                ->andReturn($fakeSession);
        });

        $this->actingAs($this->student)
            ->get(route('pago.confirmar', ['session_id' => 'cs_test_order']))
            ->assertRedirect(route('pago.exito'));

        // 4. Verify enrollment created and active after Stripe confirmation
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'activo',
        ]);

        $this->assertSame('pagado', $sale->fresh()->payment_status);

        // 5. Verify coupon usage count incremented
        $this->assertEquals(1, $coupon->fresh()->times_used);

        // 6. Verify session cart and coupon are empty
        $this->assertNull(session()->get('cart'));
        $this->assertNull(session()->get('coupon_code'));
    }
}
