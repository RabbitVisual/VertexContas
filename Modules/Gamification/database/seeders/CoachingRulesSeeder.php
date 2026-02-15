<?php

declare(strict_types=1);

namespace Modules\Gamification\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Gamification\Models\CoachingRule;
use Modules\Gamification\Models\Medal;

class CoachingRulesSeeder extends Seeder
{
    public function run(): void
    {
        $rule503020 = Medal::where('trigger_key', 'rule_503020_essential')->first();
        $ruleReserve = Medal::where('trigger_key', 'rule_reserve_solid')->first();
        $ruleConsistency = Medal::where('trigger_key', 'rule_consistency_bonus')->first();

        $rules = [
            [
                'trigger_key' => 'rule_503020_essential',
                'condition_type' => 'pillar_threshold',
                'condition_params' => ['pillar' => 'essential', 'operator' => '>', 'value' => 50],
                'level' => 'danger',
                'medal_id' => $rule503020?->id,
                'priority' => 100,
                'message_override' => 'Seus gastos essenciais ultrapassam 50% da renda. Revise sua distribuição 50/30/20.',
            ],
            [
                'trigger_key' => 'rule_reserve_solid',
                'condition_type' => 'reserve_months',
                'condition_params' => ['operator' => '>=', 'value' => 6],
                'level' => 'success',
                'medal_id' => $ruleReserve?->id,
                'priority' => 90,
                'message_override' => 'Sua reserva está sólida! Você tem o equivalente a 6 ou mais meses de despesas guardados.',
            ],
            [
                'trigger_key' => 'rule_consistency_bonus',
                'condition_type' => 'consecutive_days',
                'condition_params' => ['operator' => '>=', 'value' => 5],
                'level' => 'success',
                'medal_id' => $ruleConsistency?->id,
                'priority' => 80,
                'message_override' => 'Bonus de consistência! 5 dias seguidos lançando transações.',
            ],
        ];

        foreach ($rules as $data) {
            CoachingRule::updateOrCreate(
                ['trigger_key' => $data['trigger_key']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
