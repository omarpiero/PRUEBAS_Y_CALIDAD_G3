<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Event;
use Tests\TestCase;

class PaymentStripeTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;

    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = User::factory()->create();

        $category = Category::create([
            'name' => 'Inocuidad',
            'slug' => 'inocuidad',
            'description' => 'Inocuidad alimentaria',
            'icon' => 'shield',
            'order' => 1,
        ]);

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

    public function test_pago_with_empty_cart_redirects_to_cursos(): void
    {
        $response = $this->actingAs($this->student)->post(route('pago.procesar'));

        $response->assertRedirect(route('cursos'));
        $response->assertSessionHas('status');
        $this->assertDatabaseCount('sales', 0);
        $this->assertDatabaseCount('sale_items', 0);
    }

    public function test_process_creates_pending_sale_and_redirects_to_stripe(): void
    {
        $this->mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
            $mock->shouldReceive('createCheckoutSession')
                ->once()
                ->andReturn('https://checkout.stripe.com/c/pay/cs_test_123');
        });

        $this->actingAs($this->student)->postJson('/cart/add', ['course_id' => $this->course->id]);

        $response = $this->actingAs($this->student)->post(route('pago.procesar'));

        $response->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_123');

        $this->assertDatabaseHas('sales', [
            'user_id' => $this->student->id,
            'payment_method' => 'stripe',
            'payment_status' => 'pendiente',
            'subtotal' => 150.00,
            'discount' => 0.00,
            'total' => 150.00,
        ]);

        $sale = Sale::where('user_id', $this->student->id)->first();

        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $sale->id,
            'course_id' => $this->course->id,
            'price' => 150.00,
        ]);

        $this->assertNotEmpty(session()->get('cart'));
    }

    public function test_process_without_stripe_configured_redirects_with_message(): void
    {
        $this->mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(false);
        });

        $this->actingAs($this->student)->postJson('/cart/add', ['course_id' => $this->course->id]);

        $response = $this->actingAs($this->student)->post(route('pago.procesar'));

        $response->assertRedirect(route('checkout'));
        $response->assertSessionHas('status');
        $this->assertDatabaseCount('sales', 0);
    }

    public function test_checkout_rolls_back_sale_when_sale_item_fails(): void
    {
        $this->mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
            $mock->shouldReceive('createCheckoutSession')->never();
        });

        $response = $this->actingAs($this->student)
            ->withSession([
                'cart' => [
                    999999 => [
                        'course_id' => 999999,
                        'course_name' => 'Curso inexistente',
                        'level' => 'basico',
                        'price' => 150.00,
                    ],
                ],
            ])
            ->post(route('pago.procesar'));

        $response->assertRedirect(route('checkout'));
        $response->assertSessionHas('status');
        $this->assertDatabaseCount('sales', 0);
        $this->assertDatabaseCount('sale_items', 0);
    }

    public function test_success_redirect_confirms_sale_idempotently(): void
    {
        $coupon = Coupon::create([
            'code' => 'IDEMP10',
            'type' => 'porcentaje',
            'value' => 10.00,
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'usage_limit' => 10,
            'times_used' => 0,
            'is_active' => true,
        ]);

        $sale = Sale::factory()->create([
            'user_id' => $this->student->id,
            'coupon_id' => $coupon->id,
            'subtotal' => 150.00,
            'discount' => 15.00,
            'total' => 135.00,
            'payment_method' => 'stripe',
            'payment_status' => 'pendiente',
            'stripe_payment_id' => null,
            'paid_at' => null,
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'course_id' => $this->course->id,
            'price' => 150.00,
        ]);

        $fakeSession = $this->paidStripeSession('cs_test_123', $sale);

        $this->mock(StripeService::class, function ($mock) use ($fakeSession) {
            $mock->shouldReceive('retrieveSession')
                ->twice()
                ->with('cs_test_123')
                ->andReturn($fakeSession);
        });

        $this->actingAs($this->student)
            ->get(route('pago.confirmar', ['session_id' => 'cs_test_123']))
            ->assertRedirect(route('pago.exito'))
            ->assertSessionHas('paid_count', 1);

        $sale->refresh();
        $this->assertSame('pagado', $sale->payment_status);
        $this->assertNotNull($sale->paid_at);
        $this->assertSame('cs_test_123', $sale->stripe_payment_id);
        $this->assertEquals(1, $coupon->fresh()->times_used);
        $this->assertEquals(1, Enrollment::where('user_id', $this->student->id)
            ->where('course_id', $this->course->id)
            ->where('status', 'activo')
            ->count());

        $this->actingAs($this->student)
            ->get(route('pago.confirmar', ['session_id' => 'cs_test_123']))
            ->assertRedirect(route('pago.exito'));

        $this->assertSame('pagado', $sale->fresh()->payment_status);
        $this->assertEquals(1, $coupon->fresh()->times_used);
        $this->assertEquals(1, Enrollment::where('user_id', $this->student->id)
            ->where('course_id', $this->course->id)
            ->count());
    }

    public function test_webhook_confirms_paid_checkout_session(): void
    {
        $sale = Sale::factory()->create([
            'user_id' => $this->student->id,
            'coupon_id' => null,
            'subtotal' => 150.00,
            'discount' => 0.00,
            'total' => 150.00,
            'payment_method' => 'stripe',
            'payment_status' => 'pendiente',
            'stripe_payment_id' => null,
            'paid_at' => null,
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'course_id' => $this->course->id,
            'price' => 150.00,
        ]);

        $event = Event::constructFrom([
            'id' => 'evt_test_webhook',
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_webhook',
                    'object' => 'checkout.session',
                    'payment_status' => 'paid',
                    'metadata' => ['sale_id' => (string) $sale->id],
                ],
            ],
        ]);

        $this->mock(StripeService::class, function ($mock) use ($event) {
            $mock->shouldReceive('handleWebhook')
                ->once()
                ->andReturn($event);
        });

        $response = $this->post(route('stripe.webhook'), [], [
            'Stripe-Signature' => 't=1,v1=fake',
        ]);

        $response->assertOk()->assertJson(['received' => true]);
        $sale->refresh();
        $this->assertSame('pagado', $sale->payment_status);
        $this->assertSame('cs_test_webhook', $sale->stripe_payment_id);
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'activo',
        ]);
    }

    public function test_invalid_webhook_signature_returns_bad_request(): void
    {
        $this->mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('handleWebhook')
                ->once()
                ->andThrow(new RuntimeException('invalid signature'));
        });

        $response = $this->post(route('stripe.webhook'), [], [
            'Stripe-Signature' => 't=1,v1=bad',
        ]);

        $response->assertStatus(400);
        $response->assertSee('Invalid signature');
    }

    public function test_pending_coupon_reservation_blocks_overuse_before_payment_confirmation(): void
    {
        $coupon = Coupon::create([
            'code' => 'ONLYONE',
            'type' => 'porcentaje',
            'value' => 10.00,
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'usage_limit' => 1,
            'times_used' => 0,
            'is_active' => true,
        ]);

        $this->mock(StripeService::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(true);
            $mock->shouldReceive('createCheckoutSession')
                ->twice()
                ->andReturn('https://checkout.stripe.com/c/pay/cs_test_coupon');
        });

        $this->actingAs($this->student)->postJson('/cart/add', ['course_id' => $this->course->id]);
        $this->actingAs($this->student)->postJson(route('cart.coupon.apply'), ['code' => 'ONLYONE']);
        $this->actingAs($this->student)->post(route('pago.procesar'))
            ->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_coupon');

        $firstSale = Sale::where('user_id', $this->student->id)->firstOrFail();
        $this->assertSame($coupon->id, $firstSale->coupon_id);
        $this->assertEquals(15.00, (float) $firstSale->discount);
        $this->assertSame('pendiente', $firstSale->payment_status);

        session()->forget(['cart', 'coupon_code']);
        $secondStudent = User::factory()->create();

        $this->actingAs($secondStudent)->postJson('/cart/add', ['course_id' => $this->course->id]);
        $this->actingAs($secondStudent)->postJson(route('cart.coupon.apply'), ['code' => 'ONLYONE']);
        $this->actingAs($secondStudent)->post(route('pago.procesar'))
            ->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_coupon');

        $secondSale = Sale::where('user_id', $secondStudent->id)->firstOrFail();
        $this->assertNull($secondSale->coupon_id);
        $this->assertEquals(0.00, (float) $secondSale->discount);
        $this->assertEquals(150.00, (float) $secondSale->total);
    }

    protected function paidStripeSession(string $id, Sale $sale): StripeCheckoutSession
    {
        return StripeCheckoutSession::constructFrom([
            'id' => $id,
            'object' => 'checkout.session',
            'payment_status' => 'paid',
            'metadata' => ['sale_id' => (string) $sale->id],
        ]);
    }
}
