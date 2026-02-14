<?php

namespace Modules\Gateways\Drivers;

use Illuminate\Http\Request;
use MercadoPago\Client\PreApproval\PreApprovalClient;
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
     * Create a payment intent (legacy one-time preference - kept for interface).
     */
    public function createPaymentIntent(float $amount, array $metadata = []): array
    {
        return [
            'init_point' => $this->createCheckoutSession($amount, $metadata),
        ];
    }

    /**
     * Create checkout session (assinatura recorrente via PreApproval).
     * @see https://www.mercadopago.com.br/developers/pt/reference/subscriptions/_preapproval/post
     */
    public function createCheckoutSession(float $amount, array $metadata = []): string
    {
        $client = new PreApprovalClient();

        $payerEmail = $metadata['email'] ?? null;
        if (!$payerEmail) {
            throw new \InvalidArgumentException('payer_email é obrigatório para assinaturas Mercado Pago.');
        }

        $userId = $metadata['user_id'] ?? 'unknown';
        $backUrl = route('paneluser.index');

        $notificationUrl = route('webhooks.mercadopago') . '?source_news=webhooks';

        $request = [
            'reason' => 'Vertex Contas PRO - Assinatura mensal recorrente',
            'external_reference' => (string) $userId,
            'payer_email' => $payerEmail,
            'auto_recurring' => [
                'frequency' => 1,
                'frequency_type' => 'months',
                'transaction_amount' => (float) $amount,
                'currency_id' => 'BRL',
            ],
            'back_url' => $backUrl,
            'notification_url' => $notificationUrl,
        ];

        $preapproval = $client->create($request);
        $initPoint = $preapproval->init_point ?? null;

        if (!$initPoint) {
            throw new \RuntimeException('Mercado Pago não retornou URL de checkout para a assinatura.');
        }

        return $initPoint;
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
