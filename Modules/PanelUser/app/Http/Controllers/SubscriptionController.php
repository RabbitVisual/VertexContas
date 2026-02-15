<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Services\SettingService;
use Modules\Gateways\Models\Gateway;
use Modules\Gateways\Models\Subscription;
use Modules\Gateways\Services\SubscriptionService;

class SubscriptionController extends Controller
{
    public function index(SettingService $settingService)
    {
        $user = auth()->user();
        $isPro = $user->isPro();

        $gateways = Gateway::where('is_active', true)->get();
        $payments = \Modules\Gateways\Models\PaymentLog::where('user_id', $user->id)
            ->where('status', 'succeeded')
            ->orderBy('created_at', 'desc')
            ->get();

        $activeSubscription = Subscription::where('user_id', $user->id)
            ->whereIn('status', ['active'])
            ->orderBy('current_period_end', 'desc')
            ->first();

        $limits = [
            'account' => (int) $settingService->get('limit_free_account', 1),
            'income' => (int) $settingService->get('limit_free_income', 5),
            'expense' => (int) $settingService->get('limit_free_expense', 5),
            'goal' => (int) $settingService->get('limit_free_goal', 1),
            'budget' => (int) $settingService->get('limit_free_budget', 1),
        ];

        return view('paneluser::subscription.index', compact(
            'gateways',
            'payments',
            'isPro',
            'activeSubscription',
            'limits'
        ));
    }

    public function cancel(Request $request, SubscriptionService $subscriptionService)
    {
        $request->validate(['confirm' => 'required|in:yes']);

        $result = $subscriptionService->cancelForUser($request->user());

        if ($result['success']) {
            return redirect()->route('user.subscription.index')->with('success', $result['message']);
        }

        return redirect()->route('user.subscription.index')->with('error', $result['message']);
    }
}
