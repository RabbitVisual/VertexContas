<?php

declare(strict_types=1);

namespace Modules\Gamification\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Gamification\Models\Medal;

class MedalsSeeder extends Seeder
{
    public function run(): void
    {
        $medals = [
            [
                'title' => 'Alerta 50/30/20',
                'description' => 'Mantenha seus gastos essenciais abaixo de 50% por 2 meses.',
                'icon_name' => 'chart-pie-simple',
                'trigger_key' => 'rule_503020_essential',
                'color' => 'rose',
                'rarity' => 'bronze',
            ],
            [
                'title' => 'Reserva Sólida',
                'description' => 'Saldo equivalente a 6 ou mais meses de despesas.',
                'icon_name' => 'medal',
                'trigger_key' => 'rule_reserve_solid',
                'color' => 'emerald',
                'rarity' => 'gold',
            ],
            [
                'title' => 'Bonus de Consistência',
                'description' => '5 ou mais dias seguidos lançando transações.',
                'icon_name' => 'graduation-cap',
                'trigger_key' => 'rule_consistency_bonus',
                'color' => 'amber',
                'rarity' => 'silver',
            ],
            [
                'title' => 'Vertex PRO',
                'description' => 'Exclusivo para assinantes Vertex PRO. Desbloqueie com o plano premium.',
                'icon_name' => 'crown',
                'trigger_key' => 'rule_platinum_pro',
                'color' => 'indigo',
                'rarity' => 'platinum',
            ],
        ];

        foreach ($medals as $data) {
            Medal::updateOrCreate(
                ['trigger_key' => $data['trigger_key']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
