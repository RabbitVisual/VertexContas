<x-paneluser::layouts.master :title="__('Subscription Plan')">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-base font-semibold text-primary-600 uppercase tracking-wide">{{ __('Pricing') }}</h2>
            <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                {{ __('Upgrade your financial control') }}
            </p>
            <p class="mt-4 text-xl text-gray-500 dark:text-gray-400">
                {{ __('Choose the plan that fits your needs. No hidden fees.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-20 items-stretch">
            <!-- Free Plan -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 hover:shadow-lg transition-all duration-300 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Free Plan') }}</h3>
                    <p class="mt-4 flex items-baseline text-gray-900 dark:text-white">
                        <span class="text-5xl font-extrabold tracking-tight">{{ __('Free') }}</span>
                        <span class="ml-1 text-xl font-semibold text-gray-500 dark:text-gray-400">/{{ __('forever') }}</span>
                    </p>
                    <p class="mt-6 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{{ __('Essential features for personal tracking.') }}</p>
                </div>

                <ul role="list" class="mt-6 space-y-4 flex-1">
                    <li class="flex items-start">
                        <i class="fa-solid fa-check text-emerald-500 mt-1 mr-3"></i>
                        <span class="text-gray-600 dark:text-gray-300 text-sm">{{ __('Basic Income & Expense Tracking') }}</span>
                    </li>
                     <li class="flex items-start">
                        <i class="fa-solid fa-check text-emerald-500 mt-1 mr-3"></i>
                        <span class="text-gray-600 dark:text-gray-300 text-sm">{{ __('Limited Accounts (2)') }}</span>
                    </li>
                    <li class="flex items-start text-gray-400 dark:text-gray-500 line-through decoration-gray-400/50">
                        <i class="fa-solid fa-xmark text-gray-300 dark:text-gray-600 mt-1 mr-3"></i>
                        <span class="text-sm">{{ __('Advanced Reports (PDF/CSV)') }}</span>
                    </li>
                    <li class="flex items-start text-gray-400 dark:text-gray-500 line-through decoration-gray-400/50">
                        <i class="fa-solid fa-xmark text-gray-300 dark:text-gray-600 mt-1 mr-3"></i>
                        <span class="text-sm">{{ __('Unlimited Goals') }}</span>
                    </li>
                </ul>

                <button disabled class="mt-8 w-full py-3 px-6 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 font-bold bg-gray-50 dark:bg-gray-900/50 cursor-not-allowed text-sm uppercase tracking-wide">
                    {{ __('Current Plan') }}
                </button>
            </div>

            <!-- PRO Plan -->
            <div class="relative group">
                <!-- Glow Effect -->
                <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-400 to-orange-600 rounded-3xl blur opacity-30 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>

                <div class="relative bg-gray-900 rounded-3xl p-8 flex flex-col h-full border border-gray-800">
                    <div class="absolute top-0 right-0 -mr-1 -mt-1 w-24 h-24 overflow-hidden rounded-tr-3xl">
                         <div class="absolute transform rotate-45 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-bold py-1 right-[-35px] top-[32px] w-[170px] text-center shadow-sm">
                            {{ __('POPULAR') }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-2xl font-bold text-white flex items-center">
                            <i class="fa-solid fa-crown text-amber-400 mr-2"></i>
                            {{ __('Vertex PRO') }}
                        </h3>
                         <p class="mt-4 flex items-baseline text-white">
                            <span class="text-5xl font-extrabold tracking-tight">R$ 29,90</span>
                            <span class="ml-1 text-xl font-semibold text-gray-400">/{{ __('lifetime') }}</span>
                        </p>
                        <p class="mt-6 text-gray-400 text-sm leading-relaxed">{{ __('One-time payment. Full access forever.') }}</p>
                    </div>

                    <ul role="list" class="mt-6 space-y-4 flex-1">
                        <li class="flex items-start">
                            <div class="bg-amber-500/20 p-1 rounded-full mr-3 shrink-0">
                                <i class="fa-solid fa-check text-amber-400 text-xs"></i>
                            </div>
                            <span class="text-gray-300 text-sm font-medium">{{ __('Unlimited Transactions') }}</span>
                        </li>
                        <li class="flex items-start">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3 shrink-0">
                                <i class="fa-solid fa-check text-amber-400 text-xs"></i>
                            </div>
                            <span class="text-gray-300 text-sm">{{ __('Unlimited Accounts & Cards') }}</span>
                        </li>
                        <li class="flex items-start">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3 shrink-0">
                                <i class="fa-solid fa-check text-amber-400 text-xs"></i>
                            </div>
                            <span class="text-gray-300 text-sm">{{ __('Advanced Reports (PDF/CSV)') }}</span>
                        </li>
                        <li class="flex items-start">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3 shrink-0">
                                <i class="fa-solid fa-check text-amber-400 text-xs"></i>
                            </div>
                            <span class="text-gray-300 text-sm">{{ __('Unlimited Goals & Budgets') }}</span>
                        </li>
                         <li class="flex items-start">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3 shrink-0">
                                <i class="fa-solid fa-check text-amber-400 text-xs"></i>
                            </div>
                            <span class="text-gray-300 text-sm">{{ __('VIP Priority Support') }}</span>
                        </li>
                    </ul>

                     <!-- Gateways Selection -->
                     <div x-data="{ open: false }" class="mt-8">
                        @if(session()->has('impersonate_inspection_id'))
                             <button disabled class="w-full py-4 px-6 rounded-xl text-gray-500 font-bold bg-gray-800 cursor-not-allowed border border-gray-700">
                                {{ __('Purchase Disabled (Inspection)') }}
                            </button>
                        @else
                             <button @click="open = !open" type="button" class="w-full py-4 px-6 rounded-xl text-amber-900 font-bold bg-gradient-to-r from-amber-300 to-orange-500 hover:from-amber-400 hover:to-orange-600 shadow-lg shadow-amber-500/20 transform hover:-translate-y-0.5 transition-all flex items-center justify-center text-sm uppercase tracking-wide">
                                <span x-show="!open">{{ __('Get PRO Now') }}</span>
                                <span x-show="open">{{ __('Select Method') }}</span>
                                 <i x-show="!open" class="fa-solid fa-arrow-right ml-2"></i>
                                 <i x-show="open" class="fa-solid fa-chevron-down ml-2"></i>
                            </button>

                            <div x-show="open" x-collapse class="mt-4 space-y-3">
                                 @forelse($gateways as $gateway)
                                    <a href="{{ route('checkout.init', $gateway->slug) }}" class="flex items-center justify-center w-full py-3 px-4 rounded-xl border border-gray-600 bg-gray-800 hover:bg-gray-700 text-white transition-colors gap-2 text-sm font-medium group-link">
                                         @if($gateway->slug === 'stripe')
                                            <i class="fa-brands fa-stripe text-xl group-link-hover:text-indigo-400 transition-colors"></i> {{ __('Pay with Stripe') }}
                                        @elseif($gateway->slug === 'mercadopago')
                                            <i class="fa-solid fa-handshake text-xl text-blue-400"></i> {{ __('Mercado Pago') }}
                                        @else
                                            {{ $gateway->name }}
                                        @endif
                                    </a>
                                 @empty
                                    <div class="text-center text-sm text-gray-500 py-2">
                                        {{ __('No payment methods available.') }}
                                    </div>
                                 @endforelse
                            </div>
                        @endif
                     </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="max-w-5xl mx-auto">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                <i class="fa-solid fa-clock-rotate-left mr-2 text-primary-500"></i>
                {{ __('Payment History') }}
            </h2>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-4">{{ __('Date') }}</th>
                                <th scope="col" class="px-6 py-4">{{ __('Method') }}</th>
                                <th scope="col" class="px-6 py-4">{{ __('Amount') }}</th>
                                <th scope="col" class="px-6 py-4">{{ __('Status') }}</th>
                                <th scope="col" class="px-6 py-4">{{ __('Reference') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $payment->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 capitalize">
                                        {{ $payment->gateway_slug }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $payment->currency }} {{ number_format($payment->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'succeeded' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-gray-400">
                                        {{ Str::limit($payment->external_id, 12) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        {{ __('No payments found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
