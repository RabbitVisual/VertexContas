@php
    $isEdit = isset($plan) && $plan->exists;
    $limitValue = function ($entity) use ($plan) {
        $v = $plan->getAttribute('limit_' . $entity);
        return $v === null || $v === -1 ? '' : (string) $v;
    };
@endphp
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $plan->name) }}" required class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" placeholder="Vertex PRO Anual">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Slug <span class="text-red-500">*</span></label>
        <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}" required class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" placeholder="pro_annual">
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Único, sem espaços (ex: free, pro, pro_annual).</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recorrência</label>
        <select name="billing_interval" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
            <option value="monthly" {{ old('billing_interval', $plan->billing_interval) === 'monthly' ? 'selected' : '' }}>Mensal</option>
            <option value="yearly" {{ old('billing_interval', $plan->billing_interval) === 'yearly' ? 'selected' : '' }}>Anual</option>
        </select>
    </div>
    <div class="flex items-center gap-3 pt-8">
        <input type="hidden" name="is_free" value="0">
        <input type="checkbox" name="is_free" value="1" id="is_free" {{ old('is_free', $plan->is_free) ? 'checked' : '' }} class="rounded border-gray-300 text-[#11C76F] focus:ring-[#11C76F]/20">
        <label for="is_free" class="text-sm font-medium text-gray-700 dark:text-gray-300">Plano gratuito (apenas um deve ser gratuito)</label>
    </div>
</div>

<div class="mt-8">
    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-4">Limites (-1 ou vazio = Ilimitado)</h3>
    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Recomendado para o plano gratuito: Receitas e Despesas = -1 (ilimitado), Contas = 3, Metas = 1, Orçamentos = 1. Para o Pro: use -1 onde quiser ilimitado.</p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Contas</label>
            <input type="number" name="limit_account" value="{{ old('limit_account', $limitValue('account')) }}" min="-1" placeholder="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Receitas</label>
            <input type="number" name="limit_income" value="{{ old('limit_income', $limitValue('income')) }}" min="-1" placeholder="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Despesas</label>
            <input type="number" name="limit_expense" value="{{ old('limit_expense', $limitValue('expense')) }}" min="-1" placeholder="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Metas</label>
            <input type="number" name="limit_goal" value="{{ old('limit_goal', $limitValue('goal')) }}" min="-1" placeholder="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Orçamentos</label>
            <input type="number" name="limit_budget" value="{{ old('limit_budget', $limitValue('budget')) }}" min="-1" placeholder="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Categorias</label>
            <input type="number" name="limit_category" value="{{ old('limit_category', $limitValue('category')) }}" min="-1" placeholder="-1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
        </div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ordem de exibição</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? 0) }}" min="0" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
    </div>
    <div class="flex items-center gap-3 pt-8">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-[#11C76F] focus:ring-[#11C76F]/20">
        <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Ativo</label>
    </div>
</div>

<div class="mt-8">
    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-4">Preço e gateways (opcional)</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Valor (R$)</label>
            <input type="number" name="amount" value="{{ old('amount', $plan->amount) }}" min="0" step="0.01" placeholder="29.90" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Moeda</label>
            <input type="text" name="currency" value="{{ old('currency', $plan->currency ?? 'BRL') }}" maxlength="3" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stripe Price ID</label>
            <input type="text" name="stripe_price_id" value="{{ old('stripe_price_id', $plan->stripe_price_id) }}" placeholder="price_xxx" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mercado Pago Plan ID</label>
            <input type="text" name="mercadopago_plan_id" value="{{ old('mercadopago_plan_id', $plan->mercadopago_plan_id) }}" placeholder="ID do plano" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20">
        </div>
    </div>
</div>

<div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('admin.plans.index') }}" class="px-6 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Cancelar</a>
    <button type="submit" class="px-6 py-2.5 bg-[#11C76F] text-white rounded-xl hover:bg-[#0EA85A] transition-colors font-bold flex items-center gap-2">
        <x-icon name="save" style="duotone" class="w-4 h-4" /> {{ $isEdit ? 'Salvar alterações' : 'Criar plano' }}
    </button>
</div>
