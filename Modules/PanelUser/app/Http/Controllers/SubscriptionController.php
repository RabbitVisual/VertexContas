<?php

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Gateways\Models\Gateway;

class SubscriptionController extends Controller
{
    public function index()
    {
        $gateways = Gateway::where('is_active', true)->get();
        $payments = \Modules\Gateways\Models\PaymentLog::where('user_id', auth()->id())
            ->where('status', 'succeeded')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('paneluser::subscription.index', compact('gateways', 'payments'));
    }
}
