<?php

namespace Modules\Gateways\Drivers;

use Illuminate\Http\Request;
use Modules\Gateways\Contracts\PaymentGatewayInterface;
use Modules\Gateways\Models\Gateway;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Stripe\Subscription as StripeSubscription;
use Stripe\Invoice;
use Stripe\Refund;

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
     * Create checkout session for recurring subscription (Stripe Subscriptions).
     * @see https://docs.stripe.com/payments/checkout/build-subscriptions
     */
    public function createCheckoutSession(float $amount, array $metadata = []): string
    {
        $currency = $this->gateway->metadata['currency'] ?? 'brl';
        $unitAmount = (int) round($amount * 100); // centavos

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => 'Vertex Contas PRO',
                        'description' => 'Assinatura mensal com 7 dias grátis. Cancele quando quiser.',
                    ],
                    'unit_amount' => $unitAmount,
                    'recurring' => [
                        'interval' => 'month',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'subscription_data' => [
                'trial_period_days' => 7,
                'metadata' => $metadata,
            ],
            'customer_email' => $metadata['email'] ?? null,
            'success_url' => route('paneluser.index') . '?payment=success&session_id={CHECKOUT_SESSION_ID}',
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

    /**
     * Cancel subscription. If in trial, cancel immediately and refund any paid invoice.
     * Otherwise cancel at period end.
     *
     * @return array{success: bool, message: string, immediate: bool}
     */
    public function cancelSubscription(string $externalSubscriptionId): array
    {
        try {
            $sub = StripeSubscription::retrieve($externalSubscriptionId);
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Assinatura não encontrada no gateway.', 'immediate' => false];
        }

        $now = time();
        $inTrial = ! empty($sub->trial_end) && $sub->trial_end > $now;

        if ($inTrial) {
            // Reembolso automático: se houver invoice pago (edge case), reembolsar antes de cancelar
            try {
                $invoices = Invoice::all(['subscription' => $externalSubscriptionId, 'status' => 'paid', 'limit' => 5]);
                foreach ($invoices->data as $inv) {
                    if (! empty($inv->payment_intent)) {
                        Refund::create(['payment_intent' => $inv->payment_intent, 'reason' => 'requested_by_customer']);
                    }
                }
            } catch (\Throwable $e) {
                // Em trial normalmente não há pagamento; ignora falha
            }
            try {
                $sub->cancel();
            } catch (\Throwable $e) {
                return ['success' => false, 'message' => 'Não foi possível cancelar: ' . $e->getMessage(), 'immediate' => false];
            }
            return ['success' => true, 'message' => 'Assinatura cancelada. Durante o período de teste não há cobrança.', 'immediate' => true];
        }

        // Após o trial: cancelar no fim do período (usuário continua PRO até lá)
        try {
            StripeSubscription::update($externalSubscriptionId, ['cancel_at_period_end' => true]);
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Não foi possível agendar cancelamento: ' . $e->getMessage(), 'immediate' => false];
        }
        return ['success' => true, 'message' => 'Cancelamento agendado. Você mantém o PRO até o fim do período já pago.', 'immediate' => false];
    }
}
