<?php

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Services\SettingService;

class PlanController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Show the plans and limits configuration page.
     */
    public function index()
    {
        $limits = [
            'income' => $this->settingService->get('limit_free_income', 5),
            'expense' => $this->settingService->get('limit_free_expense', 5),
            'goal' => $this->settingService->get('limit_free_goal', 1),
            'budget' => $this->settingService->get('limit_free_budget', 1),
            'account' => $this->settingService->get('limit_free_account', 1),
            'category' => $this->settingService->get('limit_free_category', 0),
        ];

        $planFreeName = $this->settingService->get('plan_free_name', 'Plano Gratuito');
        $planProName = $this->settingService->get('plan_pro_name', 'Vertex PRO');
        $proHasLimits = (bool) $this->settingService->get('pro_has_limits', 0);
        $limitsPro = [
            'income' => (int) $this->settingService->get('limit_pro_income', -1),
            'expense' => (int) $this->settingService->get('limit_pro_expense', -1),
            'goal' => (int) $this->settingService->get('limit_pro_goal', -1),
            'budget' => (int) $this->settingService->get('limit_pro_budget', -1),
            'account' => (int) $this->settingService->get('limit_pro_account', -1),
            'category' => (int) $this->settingService->get('limit_pro_category', -1),
        ];

        return view('paneladmin::plans.index', compact('limits', 'planFreeName', 'planProName', 'proHasLimits', 'limitsPro'));
    }

    /**
     * Update the plans and limits.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'limit_free_income' => 'required|integer|min:0',
            'limit_free_expense' => 'required|integer|min:0',
            'limit_free_goal' => 'required|integer|min:0',
            'limit_free_budget' => 'required|integer|min:0',
            'limit_free_account' => 'required|integer|min:0',
            'limit_free_category' => 'required|integer|min:0',
            'plan_free_name' => 'nullable|string|max:100',
            'plan_pro_name' => 'nullable|string|max:100',
            'pro_has_limits' => 'nullable|in:0,1',
            'limit_pro_income' => 'nullable|integer|min:-1',
            'limit_pro_expense' => 'nullable|integer|min:-1',
            'limit_pro_goal' => 'nullable|integer|min:-1',
            'limit_pro_budget' => 'nullable|integer|min:-1',
            'limit_pro_account' => 'nullable|integer|min:-1',
            'limit_pro_category' => 'nullable|integer|min:-1',
        ]);

        $stringKeys = ['plan_free_name', 'plan_pro_name'];
        $integerKeys = [
            'limit_free_income', 'limit_free_expense', 'limit_free_goal',
            'limit_free_budget', 'limit_free_account', 'limit_free_category',
            'pro_has_limits',
            'limit_pro_income', 'limit_pro_expense', 'limit_pro_goal',
            'limit_pro_budget', 'limit_pro_account', 'limit_pro_category',
        ];

        foreach ($validated as $key => $value) {
            if (in_array($key, $stringKeys, true)) {
                $this->settingService->set($key, $value ?? '', 'limits', 'string');
            } elseif (in_array($key, $integerKeys, true)) {
                $this->settingService->set($key, (int) $value, 'limits', 'integer');
            }
        }

        if (empty($validated['pro_has_limits']) || (int) $validated['pro_has_limits'] === 0) {
            foreach (['income', 'expense', 'goal', 'budget', 'account', 'category'] as $entity) {
                $this->settingService->set("limit_pro_{$entity}", -1, 'limits', 'integer');
            }
        }

        return back()->with('success', 'Configurações de planos atualizadas com sucesso!');
    }
}
