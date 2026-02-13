@props(['entity'])

@php
    $user = auth()->user();

    // Limits check is only relevant for free users
    if (!$user->hasRole('free_user')) {
        return;
    }

    $service = app(\Modules\Core\Services\SubscriptionLimitService::class);
    $stats = $service->getUsageStats($user, $entity);

    $entityLabels = [
        'account' => 'Contas',
        'income' => 'Receitas',
        'expense' => 'Despesas',
        'goal' => 'Metas',
        'budget' => 'Orçamentos',
        'transaction' => 'Transações', // Generic fallback
    ];

    // Handle generic 'transactions' entity which maps to income or expense count sum?
    // Actually the user request says: "Free users have 5 Transactions (Income/Expense)"
    // The service has separate limits for income/expense. I will stick to what the service provides.
    // However, the request asks to inject into `transactions/index.blade.php`.
    // I should probably check if the entity passed is 'transactions' and sum up or just pick one.
    // LimitService has 'income' and 'expense'.
    // If entity is 'transactions', I'll default to showing usage for 'income' + 'expense' vs separate limits?
    // User request: "Free users have 5 Transactions (Income/Expense)". This implies a collective limit or separate?
    // Looking at service: 'income' => 5, 'expense' => 5.
    // So if the view is transactions index, I might need to show two bars or aggregate.
    // For simplicity and sleekness, if entity is 'transactions', I'll show the one closer to the limit or just pick 'expense' as it's more critical?
    // Let's verify the existing service limits.
    // Service says: 'income' => 5, 'expense' => 5.
    // The banner should probably be specific.
    // But the request says: "Inject ... entity='transactions'".
    // I will handle 'transactions' by checking both and showing the worst case or a combined message?
    // Let's aggregate for 'transactions' entity type for the banner display.

    if ($entity === 'transactions') {
        $incomeStats = $service->getUsageStats($user, 'income');
        $expenseStats = $service->getUsageStats($user, 'expense');

        // Show the one with higher percentage
        $stats = $incomeStats['percentage'] > $expenseStats['percentage'] ? $incomeStats : $expenseStats;
        $label = $incomeStats['percentage'] > $expenseStats['percentage'] ? 'Receitas' : 'Despesas';

        // OR better: show "Transações" and sum them up?
        // Service limits are separate constants.
        // Let's stick to the high water mark to warn the user.
        $entityLabel = $label;
    } else {
        $entityLabel = $entityLabels[$entity] ?? ucfirst($entity);
    }

    $percentage = $stats['percentage'];
    $current = $stats['current'];
    $limit = $stats['limit'];

    // Colors based on percentage
    if ($percentage >= 100) {
        $color = 'bg-rose-500';
        $textColor = 'text-rose-600 dark:text-rose-400';
        $bgBar = 'bg-rose-100 dark:bg-rose-900/30';
    } elseif ($percentage >= 75) {
        $color = 'bg-amber-500';
        $textColor = 'text-amber-600 dark:text-amber-400';
        $bgBar = 'bg-amber-100 dark:bg-amber-900/30';
    } else {
        $color = 'bg-primary';
        $textColor = 'text-primary dark:text-blue-400';
        $bgBar = 'bg-blue-100 dark:bg-blue-900/30';
    }
@endphp

<div class="mb-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 relative overflow-hidden">
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
             <span class="flex h-2 w-2 rounded-full {{ $color }}"></span>
             <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                Uso de {{ $entityLabel }}
             </h3>
        </div>
        <span class="text-xs font-bold {{ $textColor }}">
            {{ $current }} / {{ $limit }}
        </span>
    </div>

    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2 mb-3">
        <div class="{{ $color }} h-2 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
    </div>

    <div class="flex items-center justify-between">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Você está usando {{ $percentage }}% do seu plano gratuito.
        </p>

        @if($percentage >= 80)
            <a href="{{ route('user.subscription.index') }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 flex items-center hover:underline">
                <x-icon name="crown" style="solid" class="w-3 h-3 mr-1" />
                Fazer Upgrade
            </a>
        @endif
    </div>
</div>
