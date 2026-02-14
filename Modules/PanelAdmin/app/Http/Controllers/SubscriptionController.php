<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Gateways\Models\Subscription;

class SubscriptionController extends Controller
{
    /**
     * Lista assinaturas ativas (Stripe / Mercado Pago).
     */
    public function index(Request $request)
    {
        $query = Subscription::with('user')->latest();

        if ($request->filled('gateway')) {
            $query->where('gateway_slug', $request->gateway);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->paginate(15)->withQueryString();

        $activeCount = Subscription::whereIn('status', ['active'])->count();
        $pastDueCount = Subscription::where('status', 'past_due')->count();
        $canceledCount = Subscription::where('status', 'canceled')->count();

        return view('paneladmin::subscriptions.index', compact(
            'subscriptions',
            'activeCount',
            'pastDueCount',
            'canceledCount'
        ));
    }
}
