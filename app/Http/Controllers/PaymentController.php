<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Enrollment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PaymentController extends Controller
{
    public function __construct(protected StripeService $stripeService)
    {
    }

    public function process(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cursos')
                ->with('status', 'Tu carrito estaba vacio.');
        }

        if (! $this->stripeService->isConfigured()) {
            return redirect()->route('checkout')
                ->with('status', 'La pasarela de pago no esta disponible en este momento. Configura STRIPE_SECRET y STRIPE_WEBHOOK_SECRET.');
        }

        try {
            $sale = DB::transaction(function () use ($cart) {
                $subtotal = (float) collect($cart)->sum(fn ($item) => (float) $item['price']);
                $discount = 0.0;
                $couponId = null;

                if (session()->has('coupon_code')) {
                    $coupon = Coupon::where('code', session('coupon_code'))
                        ->lockForUpdate()
                        ->first();

                    if ($coupon && $coupon->is_valid && $this->couponHasAvailableReservation($coupon)) {
                        $discount = $coupon->calculateDiscount($subtotal);
                        $couponId = $coupon->id;
                    } else {
                        session()->forget('coupon_code');
                    }
                }

                $sale = Sale::create([
                    'user_id' => auth()->id(),
                    'coupon_id' => $couponId,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => max(0.00, $subtotal - $discount),
                    'payment_method' => 'stripe',
                    'payment_status' => 'pendiente',
                    'notes' => 'Pago iniciado via Stripe Checkout.',
                ]);

                foreach ($cart as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'course_id' => $item['course_id'],
                        'price' => $item['price'],
                    ]);
                }

                return $sale->load('user');
            });
        } catch (Throwable $exception) {
            Log::error('Error transaccional al registrar la venta.', [
                'user_id' => auth()->id(),
                'message' => $exception->getMessage(),
            ]);

            return redirect()->route('checkout')
                ->with('status', 'No se pudo registrar la venta. Revisa tu carrito e intentalo nuevamente.');
        }

        $successUrl = route('pago.confirmar').'?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('pago.cancelado', $sale);

        try {
            $checkoutUrl = $this->stripeService->createCheckoutSession($sale, $cart, $successUrl, $cancelUrl);
        } catch (Throwable $exception) {
            $sale->update([
                'payment_status' => 'fallido',
                'notes' => 'No se pudo crear la sesion de pago de Stripe: '.$exception->getMessage(),
            ]);

            Log::error('Error al crear la sesion de Stripe Checkout.', [
                'sale_id' => $sale->id,
                'message' => $exception->getMessage(),
            ]);

            return redirect()->route('checkout')
                ->with('status', 'No se pudo iniciar el pago con Stripe. Por favor, intentalo nuevamente.');
        }

        return redirect()->away($checkoutUrl);
    }

    public function success(Request $request): RedirectResponse
    {
        $sessionId = (string) $request->query('session_id', '');

        if ($sessionId === '') {
            return redirect()->route('checkout')
                ->with('status', 'No se recibio informacion del pago. Si realizaste el cargo, contactanos para verificarlo.');
        }

        try {
            $session = $this->stripeService->retrieveSession($sessionId);
        } catch (Throwable $exception) {
            Log::error('Error al recuperar la sesion de Stripe Checkout.', [
                'session_id' => $sessionId,
                'message' => $exception->getMessage(),
            ]);

            return redirect()->route('checkout')
                ->with('status', 'No pudimos verificar tu pago. Si realizaste el cargo, contactanos para confirmarlo.');
        }

        if ($session->payment_status !== 'paid') {
            return redirect()->route('checkout')
                ->with('status', 'Tu pago no se completo. Puedes intentarlo nuevamente cuando quieras.');
        }

        $saleId = $session->metadata?->sale_id;
        $sale = $saleId ? Sale::find($saleId) : null;

        if (! $sale || $sale->user_id !== auth()->id()) {
            Log::error('Retorno de Stripe: venta no encontrada o no pertenece al usuario.', [
                'sale_id' => $saleId,
                'session_id' => $sessionId,
            ]);

            return redirect()->route('checkout')
                ->with('status', 'No encontramos la venta asociada a este pago. Contactanos para verificarlo.');
        }

        $this->confirmSale($sale, $session->id);

        return redirect()->route('pago.exito')
            ->with('paid_count', $sale->items()->count());
    }

    public function cancel(Sale $sale): RedirectResponse
    {
        if ($sale->user_id !== auth()->id()) {
            abort(403);
        }

        if ($sale->payment_status === 'pendiente') {
            $sale->update([
                'payment_status' => 'fallido',
                'notes' => 'Pago cancelado por el cliente desde Stripe Checkout.',
            ]);
        }

        return redirect()->route('checkout')
            ->with('status', 'Pago cancelado. Tu carrito sigue disponible para intentarlo nuevamente.');
    }

    public function webhook(Request $request): Response
    {
        try {
            $event = $this->stripeService->handleWebhook(
                $request->getContent(),
                (string) $request->header('Stripe-Signature', '')
            );
        } catch (Throwable $exception) {
            Log::error('Firma de webhook de Stripe invalida.', [
                'message' => $exception->getMessage(),
            ]);

            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            if (($session->payment_status ?? null) === 'paid') {
                $saleId = $session->metadata?->sale_id;
                $sale = $saleId ? Sale::find($saleId) : null;

                if ($sale) {
                    $this->confirmSale($sale, $session->id);
                } else {
                    Log::error('Webhook de Stripe: venta no encontrada.', [
                        'sale_id' => $saleId,
                        'session_id' => $session->id ?? null,
                    ]);
                }
            }
        }

        return response()->json(['received' => true]);
    }

    protected function confirmSale(Sale $sale, string $stripeSessionId): void
    {
        $confirmed = false;

        DB::transaction(function () use ($sale, $stripeSessionId, &$confirmed) {
            $sale = Sale::with(['items', 'coupon'])
                ->whereKey($sale->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($sale->payment_status === 'pagado') {
                return;
            }

            $sale->update([
                'payment_status' => 'pagado',
                'paid_at' => now(),
                'stripe_payment_id' => $stripeSessionId,
                'notes' => trim(($sale->notes ? $sale->notes.' ' : '')."Confirmado por Stripe (session: {$stripeSessionId})."),
            ]);

            if ($sale->coupon_id) {
                Coupon::whereKey($sale->coupon_id)
                    ->lockForUpdate()
                    ->first()
                    ?->increment('times_used');
            }

            foreach ($sale->items as $item) {
                $enrollment = Enrollment::firstOrCreate(
                    [
                        'user_id' => $sale->user_id,
                        'course_id' => $item->course_id,
                    ],
                    [
                        'status' => Enrollment::STATUS_ACTIVE,
                        'enrolled_at' => now(),
                    ]
                );

                if (in_array($enrollment->status, [Enrollment::STATUS_PENDING, Enrollment::STATUS_SUSPENDED], true)) {
                    $enrollment->update([
                        'status' => Enrollment::STATUS_ACTIVE,
                        'enrolled_at' => now(),
                    ]);
                }
            }

            $confirmed = true;
        });

        if ($confirmed) {
            Cache::forget('admin_dashboard_stats');
            session()->forget(['cart', 'coupon_code']);
        }
    }

    protected function couponHasAvailableReservation(Coupon $coupon): bool
    {
        if ($coupon->usage_limit === null) {
            return true;
        }

        $confirmedUses = max(
            (int) $coupon->times_used,
            Sale::where('coupon_id', $coupon->id)
                ->where('payment_status', 'pagado')
                ->count()
        );

        $pendingReservations = Sale::where('coupon_id', $coupon->id)
            ->where('payment_status', 'pendiente')
            ->count();

        return ($confirmedUses + $pendingReservations) < $coupon->usage_limit;
    }
}
