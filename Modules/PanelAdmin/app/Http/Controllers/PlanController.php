<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Models\Plan;

class PlanController extends Controller
{
    /**
     * List all plans (table).
     */
    public function index(): View
    {
        $plans = Plan::orderBy('sort_order')->orderBy('id')->get();

        return view('paneladmin::plans.index', compact('plans'));
    }

    /**
     * Show form to create a new plan.
     */
    public function create(): View
    {
        return view('paneladmin::plans.create', ['plan' => new Plan]);
    }

    /**
     * Store a new plan.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePlan($request);
        $this->ensureOneFreePlan($validated, null);
        $validated = $this->normalizeLimitFields($validated);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plano criado com sucesso.');
    }

    /**
     * Show form to edit a plan.
     */
    public function edit(Plan $plan): View
    {
        return view('paneladmin::plans.edit', compact('plan'));
    }

    /**
     * Update a plan.
     */
    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $this->validatePlan($request, $plan);
        if ($plan->is_free && ! ($validated['is_free'] ?? false)) {
            $otherFree = Plan::where('is_free', true)->where('id', '!=', $plan->id)->exists();
            if (! $otherFree) {
                return redirect()->back()->withInput()->with('error', 'Deve existir pelo menos um plano gratuito. Crie outro plano como gratuito antes de desmarcar este.');
            }
        }
        $this->ensureOneFreePlan($validated, $plan);
        $validated = $this->normalizeLimitFields($validated);

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plano atualizado com sucesso.');
    }

    /**
     * Delete or deactivate a plan.
     */
    public function destroy(Plan $plan): RedirectResponse
    {
        $defaultFree = Plan::getDefaultFree();
        if ($defaultFree && (int) $defaultFree->id === (int) $plan->id) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Não é possível excluir o único plano gratuito. Desative-o ou defina outro plano como gratuito.');
        }

        $usersCount = $plan->users()->count();
        if ($usersCount > 0) {
            return redirect()->route('admin.plans.index')
                ->with('error', "Existem {$usersCount} usuário(s) neste plano. Reatribua-os antes de excluir ou desative o plano.");
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plano excluído com sucesso.');
    }

    private function validatePlan(Request $request, ?Plan $plan = null): array
    {
        $slugRule = 'required|string|max:60|unique:plans,slug';
        if ($plan) {
            $slugRule = 'required|string|max:60|unique:plans,slug,' . $plan->id;
        }

        return $request->validate([
            'name' => 'required|string|max:100',
            'slug' => $slugRule,
            'billing_interval' => 'required|in:monthly,yearly',
            'is_free' => 'nullable|boolean',
            'limit_account' => 'nullable|integer|min:-1',
            'limit_income' => 'nullable|integer|min:-1',
            'limit_expense' => 'nullable|integer|min:-1',
            'limit_goal' => 'nullable|integer|min:-1',
            'limit_budget' => 'nullable|integer|min:-1',
            'limit_category' => 'nullable|integer|min:-1',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'stripe_price_id' => 'nullable|string|max:255',
            'mercadopago_plan_id' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
        ], [], [
            'name' => 'nome',
            'slug' => 'slug',
            'billing_interval' => 'recorrência',
            'is_free' => 'plano gratuito',
            'limit_account' => 'limite contas',
            'limit_income' => 'limite receitas',
            'limit_expense' => 'limite despesas',
            'limit_goal' => 'limite metas',
            'limit_budget' => 'limite orçamentos',
            'limit_category' => 'limite categorias',
            'sort_order' => 'ordem',
            'is_active' => 'ativo',
        ]);
    }

    private function ensureOneFreePlan(array $validated, ?Plan $exclude): void
    {
        $willBeFree = (bool) ($validated['is_free'] ?? false);
        if (! $willBeFree) {
            return;
        }
        $query = Plan::where('is_free', true);
        if ($exclude) {
            $query->where('id', '!=', $exclude->id);
        }
        if ($query->exists()) {
            $query->update(['is_free' => false]);
        }
    }

    private function normalizeLimitFields(array $validated): array
    {
        foreach (Plan::limitEntities() as $entity) {
            $key = 'limit_' . $entity;
            if (array_key_exists($key, $validated) && ($validated[$key] === '' || $validated[$key] === null)) {
                $validated[$key] = -1;
            }
        }
        $validated['is_free'] = (bool) ($validated['is_free'] ?? false);
        $validated['is_active'] = (bool) ($validated['is_active'] ?? true);
        return $validated;
    }
}
