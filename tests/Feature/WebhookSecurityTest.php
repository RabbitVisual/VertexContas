<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Modules\Gateways\Models\Gateway;
use Tests\TestCase;

class WebhookSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Stripe Gateway
        Gateway::create([
            'name' => 'Stripe',
            'slug' => 'stripe',
            'api_key' => 'pk_test_123',
            'secret_key' => 'sk_test_123',
            'webhook_secret' => 'whsec_123',
            'mode' => 'sandbox',
            'is_active' => true,
        ]);

        // Create Mercado Pago Gateway
        Gateway::create([
            'name' => 'Mercado Pago',
            'slug' => 'mercadopago',
            'api_key' => 'APP_USR-123',
            'secret_key' => 'TEST-123',
            'webhook_secret' => 'mp_secret_123',
            'mode' => 'sandbox',
            'is_active' => true,
        ]);
    }

    public function test_stripe_webhook_unverified_does_not_log_payload()
    {
        Log::spy();

        $payload = [
            'id' => 'evt_123',
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_123',
                    'customer_email' => 'victim@example.com'
                ]
            ]
        ];

        $response = $this->postJson(route('webhooks.stripe'), $payload, [
            'Stripe-Signature' => 't=123,v1=invalid'
        ]);

        $response->assertStatus(400);

        // Check that no INFO log was made with the full payload
        Log::shouldNotHaveReceived('info');

        // Warning log for failed verification is allowed
        Log::shouldHaveReceived('warning')->with('Stripe webhook signature verification failed.');
    }

    public function test_mercadopago_webhook_unverified_does_not_log_payload()
    {
        Log::spy();

        $payload = [
            'type' => 'payment',
            'data' => [
                'id' => '123456',
                'payer' => ['email' => 'victim@example.com']
            ]
        ];

        $response = $this->postJson(route('webhooks.mercadopago'), $payload, [
            'x-signature' => 'ts=123,v1=invalid',
            'x-request-id' => 'req_123'
        ]);

        $response->assertStatus(400);

        // Check that no INFO log was made with the full payload
        Log::shouldNotHaveReceived('info');

        // Warning log for failed verification is allowed
        Log::shouldHaveReceived('warning')->with('Mercado Pago webhook signature verification failed.');
    }

    public function test_mercadopago_webhook_verified_logs_only_safe_fields()
    {
        Log::spy();

        $payload = [
            'type' => 'payment',
            'data' => [
                'id' => '123456',
                'payer' => ['email' => 'victim@example.com']
            ]
        ];

        $ts = time();
        $xRequestId = 'req_123';
        $manifest = "id:$xRequestId;request-timestamp:$ts;requestId:$xRequestId;signed_header_list:;signed_header_value:";
        $hash = hash_hmac('sha256', $manifest, 'mp_secret_123');

        $response = $this->postJson(route('webhooks.mercadopago'), $payload, [
            'x-signature' => "ts=$ts,v1=$hash",
            'x-request-id' => $xRequestId
        ]);

        $response->assertStatus(200);

        // Check that info log was made BUT without the full payload
        Log::shouldHaveReceived('info')->with('Mercado Pago Webhook Received and Verified', [
            'type' => 'payment',
            'id' => '123456'
        ]);

        Log::shouldNotHaveReceived('info', function ($message, $context) {
            return isset($context['payload']);
        });
    }
}
