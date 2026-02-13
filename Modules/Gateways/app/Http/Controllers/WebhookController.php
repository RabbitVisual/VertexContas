<?php

namespace Modules\Gateways\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Modules\Gateways\Services\GatewayFactory;
use App\Models\User;

class WebhookController extends Controller
{
    /**
     * Handle Stripe Webhook.
     */
    public function handleStripe(Request $request)
    {
        Log::info('Stripe Webhook Received', ['payload' => $request->all()]);

        $gateway = GatewayFactory::make('stripe');

        if (!$gateway->verifyWebhook($request)) {
            Log::warning('Stripe webhook signature verification failed.');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $payload = json_decode($request->getContent(), true);
        $type = $payload['type'] ?? null;

        if ($type === 'payment_intent.succeeded') {
            $object = $payload['data']['object'];
            $metadata = $object['metadata'] ?? [];
            $amount = ($object['amount'] ?? 0) / 100;
            $externalId = $object['id'] ?? null;
            $currency = $object['currency'] ?? 'brl';

            $this->processPayment('stripe', $externalId, $amount, $currency, 'succeeded', $metadata, $payload);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle Mercado Pago Webhook.
     */
    public function handleMercadoPago(Request $request)
    {
        Log::info('Mercado Pago Webhook Received', ['payload' => $request->all()]);

        $gateway = GatewayFactory::make('mercadopago');

        if (!$gateway->verifyWebhook($request)) {
            Log::warning('Mercado Pago webhook signature verification failed.');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // For Mercado Pago, we prefer handling "payment.created" or "payment.updated"
        // In a real scenario, we fetch the payment status from API using the ID in payload
        $type = $request->input('type');
        $id = $request->input('data.id');

        if ($type === 'payment' && $id) {
             // Mocking success for the purpose of this implementation as we don't have a live API to fetch
             // In production: $payment = MercadoPago::find($id);
             // checks if status == approved

             // For now, we log it and assume we'd parse metadata if available
             // Since we can't fetch without real creds, we'll log a placeholder
             // ensuring the architectural pattern is correct.

             // $this->processPayment('mercadopago', $id, 29.90, 'BRL', 'succeeded', ['user_id' => 1], $request->all());
             Log::info('Mercado Pago Payment Notification Received', ['id' => $id]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Process payment and upgrade user.
     */
    protected function processPayment($gatewaySlug, $externalId, $amount, $currency, $status, $metadata, $payload)
    {
        $userId = $metadata['user_id'] ?? null;

        DB::transaction(function () use ($gatewaySlug, $externalId, $amount, $currency, $status, $payload, $userId, $metadata) {

            \Modules\Gateways\Models\PaymentLog::create([
                'user_id' => $userId,
                'gateway_slug' => $gatewaySlug,
                'external_id' => $externalId,
                'amount' => $amount,
                'currency' => $currency,
                'status' => $status,
                'payload' => $payload,
            ]);

            if ($userId && $status === 'succeeded') {
                $user = User::find($userId);
                if ($user) {
                     // Check if plan type is pro
                     if (($metadata['plan_type'] ?? '') === 'pro') {
                        if ($user->hasRole('free_user')) {
                            $user->removeRole('free_user');
                            $user->assignRole('pro_user');
                            $user->assignRole('pro_user');
                            Log::info("User {$userId} upgraded to PRO via webhook.");

                            // Send Notification
                            try {
                                app(\Modules\Notifications\Services\NotificationService::class)->sendToUser(
                                    $user,
                                    'Bem-vindo ao Vertex PRO! 👑',
                                    'Seu pagamento foi confirmado e você agora tem acesso ilimitado a todos os recursos.',
                                    'pro',
                                    route('user.index')
                                );
                            } catch (\Exception $e) {
                                Log::error("Failed to send PRO notification: " . $e->getMessage());
                            }
                        }
                     }
                }
            }
        });
    }
}
