<?php

declare(strict_types=1);

namespace Modules\HomePage\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Models\Plan;

class PlanPageController extends Controller
{
    /**
     * Display the public plans page (content from settings + planos dinâmicos da tabela plans).
     */
    public function show()
    {
        $headline = setting('plan_page_headline', 'Assuma o Controle da sua Elite Financeira');
        $subhead = setting('plan_page_subhead', 'Pare de apenas anotar gastos. Comece a construir riqueza com a Inteligência do Vertex PRO.');
        $ctaText = setting('plan_page_cta_text', 'QUERO SER VERTEX PRO');
        $featuresHtml = setting('plan_page_features_html', '');
        $tableHtml = setting('plan_page_table_html', '');

        $planFree = Plan::getDefaultFree();
        $paidPlans = Plan::where('is_free', false)->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

        $monthlyPlan = $paidPlans->firstWhere('billing_interval', 'monthly');
        $yearlyPlan = $paidPlans->firstWhere('billing_interval', 'yearly');
        $monthlyPrice = $monthlyPlan && $monthlyPlan->amount ? number_format((float) $monthlyPlan->amount, 2, ',', '.') : '29,90';
        $yearlyPrice = $yearlyPlan && $yearlyPlan->amount ? number_format((float) $yearlyPlan->amount, 2, ',', '.') : '197,00';
        $yearlySavings = setting('plan_page_yearly_savings', '160,00');

        return view('homepage::planos', compact(
            'headline',
            'subhead',
            'monthlyPrice',
            'yearlyPrice',
            'yearlySavings',
            'ctaText',
            'featuresHtml',
            'tableHtml',
            'planFree',
            'paidPlans'
        ));
    }
}
