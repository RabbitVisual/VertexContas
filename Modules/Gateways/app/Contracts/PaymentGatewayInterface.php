<?php

namespace Modules\Gateways\Contracts;

use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Create a payment intent.
     */
    public function createPaymentIntent(float $amount, array $metadata = []): array;

    /**
     * Create a checkout session/preference.
     *
     * @return string Redirect URL
     */
    public function createCheckoutSession(float $amount, array $metadata = []): string;

    /**
     * Verify webhook signature.
     */
    public function verifyWebhook(Request $request): bool;

    /**
     * Handle webhook event.
     */
    public function handleWebhookEvent(array $payload): void;

    /**
     * Get public key for frontend.
     */
    public function getPublicKey(): string;
}
