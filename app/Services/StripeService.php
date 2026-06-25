<?php

namespace App\Services;

use App\Models\Sale;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeService
{
    protected string $secretKey;
    protected string $webhookSecret;
    protected string $currency;
    protected ?StripeClient $client = null;

    public function __construct()
    {
        $this->secretKey = (string) config('stripe.secret', '');
        $this->webhookSecret = (string) config('stripe.webhook_secret', '');
        $this->currency = (string) config('stripe.currency', 'pen');
    }

    public function isConfigured(): bool
    {
        return $this->secretKey !== '' && $this->webhookSecret !== '';
    }

    public function createCheckoutSession(Sale $sale, array $cartItems, string $successUrl, string $cancelUrl): string
    {
        if (empty($cartItems)) {
            throw new RuntimeException('No se puede crear una sesion de Stripe con el carrito vacio.');
        }

        $lineItems = collect($cartItems)->map(function (array $item) {
            $amount = (int) round(((float) ($item['price'] ?? 0)) * 100);

            if ($amount <= 0) {
                throw new RuntimeException('El precio del curso debe ser mayor que cero para Stripe.');
            }

            return [
                'price_data' => [
                    'currency' => $this->currency,
                    'product_data' => [
                        'name' => (string) ($item['course_name'] ?? 'Curso'),
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ];
        })->values()->all();

        $params = [
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'sale_id' => (string) $sale->id,
            ],
        ];

        if ($sale->user?->email) {
            $params['customer_email'] = $sale->user->email;
        }

        if ((float) $sale->discount > 0) {
            $coupon = $this->client()->coupons->create([
                'amount_off' => (int) round(((float) $sale->discount) * 100),
                'currency' => $this->currency,
                'duration' => 'once',
                'name' => 'Descuento aplicado',
            ]);

            $params['discounts'] = [
                ['coupon' => $coupon->id],
            ];
        }

        $session = $this->client()->checkout->sessions->create($params);

        if (empty($session->url)) {
            throw new RuntimeException('Stripe no devolvio una URL de checkout.');
        }

        return (string) $session->url;
    }

    public function retrieveSession(string $sessionId): Session
    {
        return $this->client()->checkout->sessions->retrieve($sessionId);
    }

    public function handleWebhook(string $payload, string $signatureHeader): Event
    {
        if ($this->webhookSecret === '') {
            throw new RuntimeException('STRIPE_WEBHOOK_SECRET no esta configurado.');
        }

        return Webhook::constructEvent($payload, $signatureHeader, $this->webhookSecret);
    }

    protected function client(): StripeClient
    {
        if ($this->secretKey === '') {
            throw new RuntimeException('STRIPE_SECRET no esta configurado.');
        }

        return $this->client ??= new StripeClient($this->secretKey);
    }
}
