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
        ];

        return view('paneladmin::plans.index', compact('limits'));
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
        ]);

        foreach ($validated as $key => $value) {
            $this->settingService->set($key, $value, 'limits', 'integer');
        }

        return back()->with('success', 'Limites do plano gratuito atualizados com sucesso!');
    }
}
