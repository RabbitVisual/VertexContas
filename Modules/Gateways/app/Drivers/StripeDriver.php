<?php

namespace Modules\Gateways\Drivers;

use Illuminate\Http\Request;
use Modules\Gateways\Contracts\PaymentGatewayInterface;
use Modules\Gateways\Models\Gateway;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class StripeDriver implements PaymentGatewayInterface
{
    protected $gateway;

    public function __construct()
    {
        $this->gateway = Gateway::where('slug', 'stripe')->firstOrFail();

        Stripe::setApiKey($this->gateway->secret_key);
    }

    /**
     * Create a payment intent.
     */
    public function createPaymentIntent(float $amount, array $metadata = []): array
    {
        $intent = PaymentIntent::create([
            'amount' => (int) ($amount * 100), // Amount in cents
            'currency' => $this->gateway->metadata['currency'] ?? 'brl',
            'metadata' => $metadata,
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return [
            'client_secret' => $intent->client_secret,
            'id' => $intent->id,
        ];
    }

    /**
     * Create checkout session.
     */
    public function createCheckoutSession(float $amount, array $metadata = []): string
    {
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $this->gateway->metadata['currency'] ?? 'brl',
                    'product_data' => [
                        'name' => 'Vertex Contas PRO',
                    ],
                    'unit_amount' => (int) ($amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('paneluser.index') . '?payment=success',
            'cancel_url' => route('user.subscription.index') . '?payment=cancelled',
            'metadata' => $metadata,
        ]);

        return $session->url;
    }

    /**
     * Verify webhook signature.
     */
    public function verifyWebhook(Request $request): bool
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = $this->gateway->webhook_secret;

        try {
            Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
            return true;
        } catch (\UnexpectedValueException $e) {
            return false;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return false;
        }
    }

    /**
     * Handle webhook event.
     */
    public function handleWebhookEvent(array $payload): void
    {
        // Handled by WebhookController using the parsed event
    }

    /**
     * Get public key for frontend.
     */
    public function getPublicKey(): string
    {
        return $this->gateway->api_key;
    }
}
