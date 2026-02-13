<?php

namespace Modules\Gateways\Drivers;

use Illuminate\Http\Request;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use Modules\Gateways\Contracts\PaymentGatewayInterface;
use Modules\Gateways\Models\Gateway;

class MercadoPagoDriver implements PaymentGatewayInterface
{
    protected $gateway;

    public function __construct()
    {
        $this->gateway = Gateway::where('slug', 'mercadopago')->firstOrFail();

        MercadoPagoConfig::setAccessToken($this->gateway->secret_key);
        MercadoPagoConfig::setRuntimeEnviroment('server');
    }

    /**
     * Create a payment intent (Preference in Mercado Pago).
     */
    public function createPaymentIntent(float $amount, array $metadata = []): array
    {
        $client = new PreferenceClient;

        $preference = $client->create([
            'items' => [
                [
                    'title' => 'Vertex Contas Upgrade',
                    'quantity' => 1,
                    'unit_price' => $amount,
                    'currency_id' => 'BRL',
                ],
            ],
            'metadata' => $metadata,
            'back_urls' => [
                'success' => route('paneluser.index'),
                'failure' => route('paneluser.index'),
                'pending' => route('paneluser.index'),
            ],
            'auto_return' => 'approved',
        ]);

        return [
            'preference_id' => $preference->id,
            'init_point' => $this->gateway->isSandbox() ? $preference->sandbox_init_point : $preference->init_point,
        ];
    }

    /**
     * Create checkout session (URL).
     */
    public function createCheckoutSession(float $amount, array $metadata = []): string
    {
        // For Mercado Pago, createPaymentIntent already creates a preference
        // We just need the init_point
        $result = $this->createPaymentIntent($amount, $metadata);

        return $result['init_point'];
    }

    /**
     * Verify webhook signature.
     */
    public function verifyWebhook(Request $request): bool
    {
        // Mercado Pago signature verification logic
        $xSignature = $request->header('x-signature');
        $xRequestId = $request->header('x-request-id');

        if (! $xSignature || ! $xRequestId) {
            return false;
        }

        // Split signature
        $parts = explode(',', $xSignature);
        $ts = null;
        $hash = null;

        foreach ($parts as $part) {
            $keyValue = explode('=', $part);
            if (count($keyValue) == 2) {
                if (trim($keyValue[0]) === 'ts') {
                    $ts = trim($keyValue[1]);
                } elseif (trim($keyValue[0]) === 'v1') {
                    $hash = trim($keyValue[1]);
                }
            }
        }

        if (! $ts || ! $hash) {
            return false;
        }

        $manifest = "id:$xRequestId;request-timestamp:$ts;requestId:$xRequestId;signed_header_list:;signed_header_value:";
        $hmac = hash_hmac('sha256', $manifest, $this->gateway->webhook_secret);

        return hash_equals($hmac, $hash);
    }

    /**
     * Handle webhook event.
     */
    public function handleWebhookEvent(array $payload): void
    {
        // Handled by WebhookController
    }

    /**
     * Get public key (not used directly in same way as Stripe, usually just Public Key).
     */
    public function getPublicKey(): string
    {
        return $this->gateway->api_key;
    }
}
