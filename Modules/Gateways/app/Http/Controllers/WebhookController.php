<?php

declare(strict_types=1);

namespace Modules\Gateways\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Invoice\InvoiceClient;
use MercadoPago\Client\PreApproval\PreApprovalClient;
use MercadoPago\MercadoPagoConfig;
use Modules\Gateways\Models\Gateway;
use Modules\Gateways\Models\PaymentLog;
use Modules\Gateways\Models\Subscription;
use Modules\Gateways\Services\GatewayFactory;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends Controller
{
    /**
     * Handle Stripe Webhook (Subscriptions).
     * @see https://docs.stripe.com/payments/checkout/build-subscriptions
     * Events: checkout.session.completed, invoice.paid, invoice.payment_failed
     */
    public function handleStripe(Request $request)
    {
        $gateway = Gateway::where('slug', 'stripe')->firstOrFail();
        Stripe::setApiKey($gateway->secret_key);

        if (! $this->verifyStripeWebhook($request, $gateway->webhook_secret)) {
            Log::warning('Stripe webhook signature verification failed.');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $payload = json_decode($request->getContent(), true);
        $type = $payload['type'] ?? null;

        Log::info('Stripe Webhook Received', ['type' => $type]);

        match ($type) {
            'checkout.session.completed' => $this->handleStripeCheckoutCompleted($payload),
            'invoice.paid' => $this->handleStripeInvoicePaid($payload),
            'invoice.payment_failed' => $this->handleStripeInvoiceFailed($payload),
            default => null,
        };

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle Mercado Pago Webhook (Assinaturas recorrentes).
     * @see https://www.mercadopago.com.br/developers/pt/docs/subscriptions/additional-content/your-integrations/notifications/webhooks
     * Events: subscription_preapproval, subscription_authorized_payment
     */
    public function handleMercadoPago(Request $request)
    {
        $gateway = Gateway::where('slug', 'mercadopago')->firstOrFail();
        MercadoPagoConfig::setAccessToken($gateway->secret_key);

        // POST com headers: verificar assinatura. GET (notification_url): sem assinatura, validar via API
        $needsVerify = $request->isMethod('POST') && $request->header('x-signature');
        if ($needsVerify && ! $this->verifyMercadoPagoWebhook($request, $gateway)) {
            Log::warning('Mercado Pago webhook signature verification failed.');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $type = $request->input('type') ?: $request->query('topic');
        $id = $request->input('data.id') ?? $request->query('id');

        Log::info('Mercado Pago Webhook Received', ['type' => $type, 'id' => $id]);

        if (! $id) {
            return response()->json(['status' => 'success']);
        }

        match ($type) {
            'subscription_preapproval' => $this->handleMpSubscriptionPreapproval($id, $gateway),
            'subscription_authorized_payment' => $this->handleMpAuthorizedPayment($id, $gateway),
            default => null,
        };

        return response()->json(['status' => 'success']);
    }

    protected function verifyStripeWebhook(Request $request, ?string $secret): bool
    {
        if (! $secret) {
            return true;
        }
        try {
            Webhook::constructEvent($request->getContent(), $request->header('Stripe-Signature') ?? '', $secret);
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    protected function verifyMercadoPagoWebhook(Request $request, Gateway $gateway): bool
    {
        $driver = GatewayFactory::make('mercadopago');
        return $driver->verifyWebhook($request);
    }

    protected function handleStripeCheckoutCompleted(array $payload): void
    {
        $session = $payload['data']['object'] ?? [];
        $metadata = $session['metadata'] ?? [];
        $userId = $metadata['user_id'] ? (int) $metadata['user_id'] : null;
        $subscriptionId = $session['subscription'] ?? null;
        $customerId = $session['customer'] ?? null;

        if (! $subscriptionId || ! $userId) {
            Log::warning('Stripe checkout.session.completed missing subscription or user_id', $session);
            return;
        }

        $subscription = \Stripe\Subscription::retrieve($subscriptionId);
        $periodEnd = isset($subscription->current_period_end)
            ? \Carbon\Carbon::createFromTimestamp($subscription->current_period_end)
            : null;
        $amount = 0;
        if (isset($subscription->items->data[0]->price->unit_amount)) {
            $amount = $subscription->items->data[0]->price->unit_amount / 100;
        }

        DB::transaction(function () use ($userId, $subscriptionId, $customerId, $amount, $periodEnd, $metadata) {
            Subscription::updateOrCreate(
                ['external_subscription_id' => $subscriptionId],
                [
                    'user_id' => $userId,
                    'gateway_slug' => 'stripe',
                    'external_customer_id' => $customerId,
                    'status' => 'active',
                    'amount' => $amount ?: 29.90,
                    'currency' => 'BRL',
                    'current_period_end' => $periodEnd,
                    'metadata' => $metadata,
                ]
            );

            $this->upgradeUserToPro($userId, $metadata);
        });
    }

    protected function handleStripeInvoicePaid(array $payload): void
    {
        $invoice = $payload['data']['object'] ?? [];
        $subscriptionId = $invoice['subscription'] ?? null;
        $amount = ($invoice['amount_paid'] ?? 0) / 100;
        $currency = $invoice['currency'] ?? 'brl';
        $externalId = $invoice['id'] ?? $invoice['payment_intent'] ?? null;

        $metadata = ['plan_type' => 'pro'];
        $sub = Subscription::where('external_subscription_id', $subscriptionId)->first();
        if ($sub) {
            $metadata['user_id'] = $sub->user_id;
            $metadata['plan_type'] = 'pro';

            $sub->update([
                'current_period_end' => isset($invoice['period_end'])
                    ? \Carbon\Carbon::createFromTimestamp($invoice['period_end'])
                    : $sub->current_period_end,
            ]);
        } else {
            $metadata['user_id'] = null;
        }

        $this->processPayment(
            'stripe',
            (string) $externalId,
            $amount,
            strtoupper($currency),
            'succeeded',
            $metadata,
            $payload,
            $sub?->id
        );
    }

    protected function handleStripeInvoiceFailed(array $payload): void
    {
        $invoice = $payload['data']['object'] ?? [];
        $subscriptionId = $invoice['subscription'] ?? null;
        $amount = ($invoice['amount_due'] ?? 0) / 100;
        $currency = $invoice['currency'] ?? 'brl';
        $externalId = $invoice['id'] ?? null;

        $sub = Subscription::where('external_subscription_id', $subscriptionId)->first();
        $metadata = ['plan_type' => 'pro', 'user_id' => $sub?->user_id];

        $this->processPayment(
            'stripe',
            (string) $externalId,
            $amount,
            strtoupper($currency),
            'failed',
            $metadata,
            $payload,
            $sub?->id
        );

        if ($sub) {
            $sub->update(['status' => 'past_due']);
        }
    }

    protected function handleMpSubscriptionPreapproval(string $id, Gateway $gateway): void
    {
        try {
            $client = new PreApprovalClient();
            $pa = $client->get($id);
        } catch (\Throwable $e) {
            Log::error('Mercado Pago PreApproval fetch failed', ['id' => $id, 'error' => $e->getMessage()]);
            return;
        }

        $status = $pa->status ?? '';
        if ($status !== 'authorized') {
            Log::info('Mercado Pago PreApproval not authorized', ['id' => $id, 'status' => $status]);
            return;
        }

        $userId = (int) ($pa->external_reference ?? 0);
        if (! $userId) {
            Log::warning('Mercado Pago PreApproval missing external_reference', ['id' => $id]);
            return;
        }

        $amount = 29.90;
        if (isset($pa->auto_recurring->transaction_amount)) {
            $amount = (float) $pa->auto_recurring->transaction_amount;
        }
        $nextEnd = isset($pa->next_payment_date)
            ? \Carbon\Carbon::parse($pa->next_payment_date)
            : null;

        DB::transaction(function () use ($id, $userId, $amount, $nextEnd) {
            Subscription::updateOrCreate(
                ['external_subscription_id' => $id],
                [
                    'user_id' => $userId,
                    'gateway_slug' => 'mercadopago',
                    'external_customer_id' => null,
                    'status' => 'active',
                    'amount' => $amount,
                    'currency' => 'BRL',
                    'current_period_end' => $nextEnd,
                    'metadata' => ['plan_type' => 'pro'],
                ]
            );

            $this->upgradeUserToPro($userId, ['plan_type' => 'pro']);
        });
    }

    protected function handleMpAuthorizedPayment(string $id, Gateway $gateway): void
    {
        try {
            $client = new InvoiceClient();
            $invoice = $client->get((int) $id);
        } catch (\Throwable $e) {
            Log::error('Mercado Pago AuthorizedPayment fetch failed', ['id' => $id, 'error' => $e->getMessage()]);
            return;
        }

        $status = strtolower($invoice->status ?? '');
        if (! in_array($status, ['paid', 'approved'], true)) {
            Log::info('Mercado Pago AuthorizedPayment not paid', ['id' => $id, 'status' => $status]);
            return;
        }

        $preapprovalId = $invoice->preapproval_id ?? null;
        $externalRef = $invoice->external_reference ?? null;
        $amount = (float) ($invoice->transaction_amount ?? 29.90);
        $currency = $invoice->currency_id ?? 'BRL';

        $sub = Subscription::where('external_subscription_id', $preapprovalId)->first();
        $userId = $sub?->user_id ?? (int) $externalRef;

        $metadata = ['plan_type' => 'pro', 'user_id' => $userId];

        $this->processPayment('mercadopago', $id, $amount, $currency, 'succeeded', $metadata, [], $sub?->id);
    }

    protected function processPayment(
        string $gatewaySlug,
        string $externalId,
        float $amount,
        string $currency,
        string $status,
        array $metadata,
        $payload,
        ?int $subscriptionId = null
    ): void {
        $userId = $metadata['user_id'] ?? null;

        DB::transaction(function () use ($gatewaySlug, $externalId, $amount, $currency, $status, $payload, $userId, $metadata, $subscriptionId) {
            // Idempotency: avoid duplicate PaymentLog on webhook retries
            $exists = PaymentLog::where('gateway_slug', $gatewaySlug)
                ->where('external_id', $externalId)
                ->exists();

            if ($exists) {
                Log::info('PaymentLog already exists, skipping (idempotent)', [
                    'gateway' => $gatewaySlug,
                    'external_id' => $externalId,
                ]);
                return;
            }

            PaymentLog::create([
                'user_id' => $userId,
                'subscription_id' => $subscriptionId,
                'gateway_slug' => $gatewaySlug,
                'external_id' => $externalId,
                'amount' => $amount,
                'currency' => $currency,
                'status' => $status,
                'payload' => is_array($payload) ? $payload : [],
            ]);

            if ($userId && $status === 'succeeded') {
                $user = User::find($userId);
                if ($user && ($metadata['plan_type'] ?? '') === 'pro') {
                    if ($user->hasRole('free_user')) {
                        $user->removeRole('free_user');
                        $user->assignRole('pro_user');
                        Log::info("User {$userId} upgraded to PRO via webhook.");
                    }
                    try {
                        app(\Modules\Notifications\Services\NotificationService::class)->sendToUser(
                            $user,
                            'Bem-vindo ao Vertex PRO! 👑',
                            'Seu pagamento foi confirmado e você agora tem acesso ilimitado a todos os recursos.',
                            'pro',
                            route('paneluser.index')
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to send PRO notification: ' . $e->getMessage());
                    }
                }
            }
        });
    }

    protected function upgradeUserToPro(int $userId, array $metadata): void
    {
        $user = User::find($userId);
        if (! $user || ($metadata['plan_type'] ?? '') !== 'pro') {
            return;
        }
        if ($user->hasRole('free_user')) {
            $user->removeRole('free_user');
            $user->assignRole('pro_user');
            Log::info("User {$userId} upgraded to PRO via webhook.");
        }
        try {
            app(\Modules\Notifications\Services\NotificationService::class)->sendToUser(
                $user,
                'Bem-vindo ao Vertex PRO! 👑',
                'Seu pagamento foi confirmado e você agora tem acesso ilimitado a todos os recursos.',
                'pro',
                route('paneluser.index')
            );
        } catch (\Exception $e) {
            Log::error('Failed to send PRO notification: ' . $e->getMessage());
        }
    }
}
