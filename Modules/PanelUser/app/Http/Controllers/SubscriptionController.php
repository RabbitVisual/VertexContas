<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Models\Plan;
use Modules\Gateways\Models\Gateway;
use Modules\Gateways\Models\Subscription;
use Modules\Gateways\Services\SubscriptionService;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isPro = $user->isPro();

        $returnUrl = $request->query('return');
        if ($returnUrl !== null && $returnUrl !== '') {
            $returnUrl = (string) $returnUrl;
            if (str_starts_with($returnUrl, '/')) {
                $returnUrl = url($returnUrl);
            } elseif (! str_starts_with($returnUrl, config('app.url'))
                && ! str_starts_with($returnUrl, 'http://')
                && ! str_starts_with($returnUrl, 'https://')) {
                $returnUrl = null;
            }
        } else {
            $returnUrl = null;
        }

        $gateways = Gateway::where('is_active', true)->get();
        $payments = \Modules\Gateways\Models\PaymentLog::where('user_id', $user->id)
            ->where('status', 'succeeded')
            ->orderBy('created_at', 'desc')
            ->get();

        $activeSubscription = Subscription::where('user_id', $user->id)
            ->whereIn('status', ['active'])
            ->orderBy('current_period_end', 'desc')
            ->first();

        $planFree = Plan::getDefaultFree();
        $planPro = Plan::getDefaultPaid();
        $paidPlans = Plan::where('is_free', false)->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

        $limits = [
            'account' => 1,
            'income' => 15,
            'expense' => 15,
            'goal' => 1,
            'budget' => 1,
            'category' => 0,
        ];
        $limitsPro = [
            'account' => -1,
            'income' => -1,
            'expense' => -1,
            'goal' => -1,
            'budget' => -1,
            'category' => -1,
        ];
        $proHasLimits = false;

        if ($planFree) {
            foreach (Plan::limitEntities() as $entity) {
                $val = $planFree->getLimit($entity);
                $limits[$entity] = $val === 'unlimited' ? -1 : (int) $val;
            }
        }
        if ($planPro) {
            foreach (Plan::limitEntities() as $entity) {
                $val = $planPro->getLimit($entity);
                $limitsPro[$entity] = $val === 'unlimited' ? -1 : (int) $val;
            }
            $proHasLimits = collect($limitsPro)->contains(fn ($v) => $v >= 0);
        }

        $hasUsedTrial = $user->hasUsedTrial();

        return view('paneluser::subscription.index', compact(
            'gateways',
            'payments',
            'isPro',
            'activeSubscription',
            'limits',
            'limitsPro',
            'proHasLimits',
            'hasUsedTrial',
            'planFree',
            'planPro',
            'paidPlans',
            'returnUrl'
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
