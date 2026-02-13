<?php

namespace Modules\Gateways\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Gateways\Services\GatewayFactory;
use Modules\Gateways\Models\Gateway;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Initiate checkout process.
     */
    public function checkout(Request $request, string $gatewaySlug)
    {
        try {
            $gateway = Gateway::where('slug', $gatewaySlug)->firstOrFail();

            if (!$gateway->is_active) {
                return back()->with('error', 'Este método de pagamento não está disponível no momento.');
            }

            $driver = GatewayFactory::make($gatewaySlug);
            $user = Auth::user();

            // Default PRO Plan Price
            $amount = 29.90;

            $metadata = [
                'user_id' => $user->id,
                'plan_type' => 'pro',
                'email' => $user->email,
            ];

            $redirectUrl = $driver->createCheckoutSession($amount, $metadata);

            return redirect()->away($redirectUrl);

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao iniciar pagamento: ' . $e->getMessage());
        }
    }
}
