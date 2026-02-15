<?php

declare(strict_types=1);

namespace Modules\Gateways\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Gateways\Models\Subscription;
use Modules\Gateways\Services\GatewayFactory;

class SubscriptionService
{
    /**
     * Cancel the active subscription for the user.
     * - If in trial: cancel immediately; refund any paid invoice (edge case).
     * - If after trial: cancel at period end (user keeps PRO until then).
     *
     * @return array{success: bool, message: string}
     */
    public function cancelForUser(User $user): array
    {
        $subscription = Subscription::where('user_id', $user->id)
            ->whereIn('status', ['active'])
            ->orderBy('current_period_end', 'desc')
            ->first();

        if (! $subscription) {
            return ['success' => false, 'message' => 'Nenhuma assinatura ativa encontrada.'];
        }

        if ($subscription->gateway_slug === 'stripe') {
            $driver = GatewayFactory::make('stripe');
            if (! method_exists($driver, 'cancelSubscription')) {
                return ['success' => false, 'message' => 'Cancelamento não disponível para este gateway.'];
            }
            $result = $driver->cancelSubscription($subscription->external_subscription_id);
        } elseif ($subscription->gateway_slug === 'mercadopago') {
            // Mercado Pago: cancel preapproval via API (pode ser implementado no MercadoPagoDriver)
            $result = ['success' => false, 'message' => 'Cancelamento pelo painel: acesse Mercado Pago ou entre em contato com o suporte.'];
        } else {
            $result = ['success' => false, 'message' => 'Gateway não suporta cancelamento por aqui.'];
        }

        if (! ($result['success'] ?? false)) {
            return $result;
        }

        $immediate = $result['immediate'] ?? false;
        DB::transaction(function () use ($subscription, $immediate, $user) {
            if ($immediate) {
                $subscription->update(['status' => 'canceled', 'canceled_at' => now()]);
                if ($user->hasRole('pro_user')) {
                    $user->removeRole('pro_user');
                    $user->assignRole('free_user');
                    Log::info("User {$user->id} downgraded to free after immediate subscription cancel.");
                }
            } else {
                $meta = $subscription->metadata ?? [];
                $meta['cancel_at_period_end'] = true;
                $subscription->update(['metadata' => $meta]);
            }
        });

        try {
            app(\Modules\Notifications\Services\NotificationService::class)->sendToUser(
                $user,
                'Assinatura Vertex PRO cancelada',
                $result['message'],
                'info',
                route('user.subscription.index')
            );
        } catch (\Throwable $e) {
            Log::warning('SubscriptionService: failed to send cancel notification. ' . $e->getMessage());
        }

        return $result;
    }
}
