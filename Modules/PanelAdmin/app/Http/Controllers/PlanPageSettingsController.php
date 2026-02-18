<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Services\SettingService;

class PlanPageSettingsController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    /**
     * Show the form to edit the public plan page copy.
     */
    public function index()
    {
        $homepage = $this->settingService->getByGroup('homepage');
        $headline = $homepage->get('plan_page_headline', 'Assuma o Controle da sua Elite Financeira');
        $subhead = $homepage->get('plan_page_subhead', 'Pare de apenas anotar gastos. Comece a construir riqueza com a Inteligência do Vertex PRO.');
        $monthlyPrice = $homepage->get('plan_page_monthly_price', '29,90');
        $yearlyPrice = $homepage->get('plan_page_yearly_price', '197,00');
        $yearlySavings = $homepage->get('plan_page_yearly_savings', '160,00');
        $ctaText = $homepage->get('plan_page_cta_text', 'QUERO SER VERTEX PRO');
        $featuresHtml = $homepage->get('plan_page_features_html', '');
        $tableHtml = $homepage->get('plan_page_table_html', '');

        return view('paneladmin::plan.index', compact(
            'headline',
            'subhead',
            'monthlyPrice',
            'yearlyPrice',
            'yearlySavings',
            'ctaText',
            'featuresHtml',
            'tableHtml'
        ));
    }

    /**
     * Update the public plan page copy.
     */
    public function update(Request $request)
    {
        $request->validate([
            'plan_page_headline' => 'nullable|string|max:255',
            'plan_page_subhead' => 'nullable|string|max:1000',
            'plan_page_monthly_price' => 'nullable|string|max:20',
            'plan_page_yearly_price' => 'nullable|string|max:20',
            'plan_page_yearly_savings' => 'nullable|string|max:20',
            'plan_page_cta_text' => 'nullable|string|max:100',
            'plan_page_features_html' => 'nullable|string|max:50000',
            'plan_page_table_html' => 'nullable|string|max:50000',
        ]);

        $keys = [
            'plan_page_headline',
            'plan_page_subhead',
            'plan_page_monthly_price',
            'plan_page_yearly_price',
            'plan_page_yearly_savings',
            'plan_page_cta_text',
            'plan_page_features_html',
            'plan_page_table_html',
        ];

        foreach ($keys as $key) {
            $this->settingService->set($key, $request->input($key, ''), 'homepage');
        }

        return redirect()->route('admin.plan.index')->with('success', 'Conteúdo da página de planos atualizado com sucesso!');
    }
}
