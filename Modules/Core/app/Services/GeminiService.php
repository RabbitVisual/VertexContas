<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected const API_BASE = 'https://generativelanguage.googleapis.com/v1beta';

    protected const MODEL = 'gemini-1.5-flash';

    protected const TIMEOUT = 8;

    protected Client $http;

    public function __construct(
        protected SettingService $settingService,
        ?Client $http = null
    ) {
        $this->http = $http ?? new Client(['timeout' => self::TIMEOUT]);
    }

    /**
     * Check if Gemini is enabled and API key is configured.
     */
    public function isAvailable(): bool
    {
        return $this->getApiKey() !== null && $this->getApiKey() !== '';
    }

    /**
     * Generate an insight for the Vertex Bot via Gemini API.
     * Returns null on failure (timeout, API error) for fallback to local insights.
     *
     * @param  array{financial_score: int, coaching_stats: array, trigger: string, metrics?: array, blog_titles?: array}  $contextData
     */
    public function generateInsight(array $contextData, string $trigger, bool $isPro): ?string
    {
        $apiKey = $this->getApiKey();
        if (! $apiKey) {
            return null;
        }

        $prompt = $this->buildPrompt($contextData, $trigger, $isPro);

        try {
            $url = self::API_BASE . '/models/' . self::MODEL . ':generateContent?key=' . $apiKey;
            $response = $this->http->post($url, [
                RequestOptions::JSON => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => $isPro ? 512 : 256,
                        'temperature' => 0.7,
                        'topP' => 0.9,
                    ],
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($text === null || trim($text) === '') {
                return null;
            }

            return trim($text);
        } catch (GuzzleException $e) {
            Log::warning('Gemini API request failed', [
                'trigger' => $trigger,
                'message' => $e->getMessage(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::warning('Gemini API unexpected error', [
                'trigger' => $trigger,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Resolve API key: Panel setting (encrypted) or env fallback.
     */
    protected function getApiKey(): ?string
    {
        $key = $this->settingService->get('gemini_api_key');
        if ($key && is_string($key) && trim($key) !== '') {
            return trim($key);
        }

        $envKey = env('GEMINI_API_KEY');
        if ($envKey && is_string($envKey) && trim($envKey) !== '') {
            return trim($envKey);
        }

        return null;
    }

    /**
     * Build the system prompt for Gemini. LGPD: only metrics, no PII.
     */
    protected function buildPrompt(array $contextData, string $trigger, bool $isPro): string
    {
        $score = $contextData['financial_score'] ?? 0;
        $stats = $contextData['coaching_stats'] ?? [];
        $metrics = $contextData['metrics'] ?? [];
        $blogTitles = $contextData['blog_titles'] ?? [];

        $essentialPct = $stats['essential_pct'] ?? 0;
        $savingsPct = $stats['savings_pct'] ?? 0;
        $lifestylePct = $stats['lifestyle_pct'] ?? 0;

        $triggerLabels = [
            'low_balance' => 'reserva de emergência baixa',
            'budget_reached' => 'orçamento de categoria atingido ou ultrapassado',
            'savings_milestone' => 'ótima taxa de poupança (gastou menos de 50% da renda)',
            'daily_tip' => 'dica do dia',
        ];
        $triggerDesc = $triggerLabels[$trigger] ?? $trigger;
        $triggerExtra = $contextData['trigger_extra'] ?? [];
        if ($trigger === 'budget_reached' && ! empty($triggerExtra)) {
            $triggerDesc .= sprintf(' (categoria: %s, uso: %s%%)', $triggerExtra['category'] ?? 'N/A', $triggerExtra['percent'] ?? 0);
        }

        $isLowScore = $score <= 40;
        $isMidScore = $score > 40 && $score <= 70;

        $system = "Você é o Vertex Bot - um mentor financeiro amigável e acolhedor. Dê uma dica personalizada em português do Brasil. ";
        $system .= "NUNCA use nome, CPF ou dados pessoais. Responda apenas com o texto da dica, sem introduções.\n\n";
        $system .= "VARIE sempre o tema: não repita a mesma ideia (ex.: evitar ficar só em 'reserva de emergência' e 'rotativo'). ";
        $system .= "Alternar entre: controle de gastos, orçamento por categoria, metas, lazer sem culpa, assinaturas, parcelas, regra 50/30/20, pequenas economias, comparação de preços, etc.\n\n";

        if ($isLowScore) {
            $system .= "IMPORTANTE - usuário com score baixo (provavelmente leigo em finanças): ";
            $system .= "Use linguagem MUITO simples e didática. Evite jargão. Se precisar mencionar um conceito, explique entre parênteses. ";
            $system .= "Exemplos: 'reserva de emergência' = 'dinheiro guardado para imprevistos'; 'rotativo' = 'juros altos do cartão quando não paga a fatura inteira'. ";
            $system .= "Seja como um amigo que quer ajudar, não como um banco. Use termos do dia a dia: 'guardar dinheiro', 'evitar dívidas caras', 'planejar gastos'.\n\n";
        } elseif ($isMidScore) {
            $system .= "Use linguagem clara e objetiva. Pode mencionar conceitos básicos (reserva de emergência, planejar gastos) mas explique quando necessário.\n\n";
        } else {
            $system .= "Pode usar termos de mercado (CDB, IPCA+, juros compostos, reserva de emergência, regra 50/30/20) de forma natural.\n\n";
        }

        $context = "Contexto do usuário (métricas anônimas):\n";
        $context .= "- Score Financeiro: {$score}/100\n";
        $context .= "- Regra 50/30/20 atual: Necessários {$essentialPct}%, Desejos {$lifestylePct}%, Poupança {$savingsPct}%\n";
        if (! empty($metrics)) {
            $context .= "- Renda mensal: R\$ " . number_format($metrics['income'] ?? 0, 2, ',', '.') . "\n";
            $context .= "- Despesas mensais: R\$ " . number_format($metrics['expense'] ?? 0, 2, ',', '.') . "\n";
            $context .= "- Saldo em conta: R\$ " . number_format($metrics['account_balance'] ?? 0, 2, ',', '.') . "\n";
        }
        $context .= "- Situação: {$triggerDesc}\n";

        if ($isPro && ! empty($blogTitles)) {
            $context .= "- Artigos recentes do blog: " . implode(', ', $blogTitles) . "\n";
        }

        if ($isPro) {
            $system .= "Para usuário PRO: faça uma análise mais profunda, cruzando os gastos com os artigos do blog quando relevante. Resposta entre 2 e 4 frases.\n\n";
        } else {
            $system .= "Para usuário FREE: dica curta em 1 ou 2 frases.\n\n";
        }

        return $system . $context . "\nDica:";
    }

    /**
     * Generate a 3-paragraph strategic conclusion for the consulting PDF.
     * Formal, technical style. Cites "Regra 50/30/20 da Vertex". Returns null on failure.
     *
     * @param  array{budget_analysis: array, financial_score: int, metrics?: array}  $contextData
     */
    public function generateConsultingConclusion(array $contextData): ?string
    {
        $apiKey = $this->getApiKey();
        if (! $apiKey) {
            return null;
        }

        $prompt = $this->buildConsultingConclusionPrompt($contextData);

        try {
            $url = self::API_BASE . '/models/' . self::MODEL . ':generateContent?key=' . $apiKey;
            $response = $this->http->post($url, [
                RequestOptions::JSON => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 512,
                        'temperature' => 0.6,
                        'topP' => 0.9,
                    ],
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($text === null || trim($text) === '') {
                return null;
            }

            return trim($text);
        } catch (GuzzleException $e) {
            Log::warning('Gemini API consulting conclusion failed', ['message' => $e->getMessage()]);

            return null;
        } catch (\Throwable $e) {
            Log::warning('Gemini API consulting conclusion error', ['message' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Build prompt for consulting conclusion. Token-optimized, formal.
     *
     * @param  array{budget_analysis: array, financial_score: int, metrics?: array}  $contextData
     */
    protected function buildConsultingConclusionPrompt(array $contextData): string
    {
        $score = $contextData['financial_score'] ?? 0;
        $budget = $contextData['budget_analysis'] ?? [];
        $pillars = $budget['pillars'] ?? [];
        $metrics = $contextData['metrics'] ?? [];
        $savingsPct = $budget['savings_pct'] ?? 0;
        $income = $metrics['income'] ?? 0;
        $expense = $metrics['expense'] ?? 0;
        $balance = $metrics['account_balance'] ?? 0;

        $essential = $pillars['essential'] ?? [];
        $lifestyle = $pillars['lifestyle'] ?? [];
        $financial = $pillars['financial'] ?? [];

        $deviations = [];
        if (($essential['status'] ?? '') === 'over') {
            $deviations[] = 'Essencial acima da meta (' . ($essential['deviation'] ?? 0) . '%)';
        }
        if (($essential['status'] ?? '') === 'under') {
            $deviations[] = 'Essencial abaixo da meta';
        }
        if (($lifestyle['status'] ?? '') === 'over') {
            $deviations[] = 'Estilo de vida acima da meta (' . ($lifestyle['deviation'] ?? 0) . '%)';
        }
        if (($financial['status'] ?? '') === 'under') {
            $deviations[] = 'Financeiro abaixo da meta';
        }
        if ($savingsPct < 20) {
            $deviations[] = 'Taxa de poupança ' . round($savingsPct, 1) . '% (meta 20%)';
        }

        $devText = empty($deviations) ? 'Aderência adequada à regra' : implode('; ', $deviations);

        $system = "Você é o CFO da Vertex. Escreva a Conclusão Estratégica do Especialista para um relatório financeiro. ";
        $system .= "3 parágrafos, formal e técnico. Use metodologia Regra 50/30/20 da Vertex. ";
        $system .= "Cite oportunidades baseadas nos desvios encontrados. Sem nome ou dados pessoais. Responda apenas o texto.\n\n";

        $context = "Contexto:\n";
        $context .= "- Score: {$score}/100 | Poupança real: " . round($savingsPct, 1) . "%\n";
        $context .= "- Desvios: {$devText}\n";
        $context .= "- Renda: R\$ " . number_format($income, 2, ',', '.') . " | Despesas: R\$ " . number_format($expense, 2, ',', '.') . " | Saldo: R\$ " . number_format($balance, 2, ',', '.') . "\n";

        return $system . $context . "\nConclusão estratégica:";
    }

    /**
     * Generate 1-year patrimony projection based on current savings rate.
     *
     * @param  array{reserve_months: float, savings_rate: float, balance: float, monthly_income: float, monthly_expense: float}  $contextData
     */
    public function generateOneYearProjection(array $contextData): ?string
    {
        $apiKey = $this->getApiKey();
        if (! $apiKey) {
            return null;
        }

        $prompt = $this->buildProjectionPrompt($contextData);

        try {
            $url = self::API_BASE . '/models/' . self::MODEL . ':generateContent?key=' . $apiKey;
            $response = $this->http->post($url, [
                RequestOptions::JSON => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 256,
                        'temperature' => 0.6,
                        'topP' => 0.9,
                    ],
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($text === null || trim($text) === '') {
                return null;
            }

            return trim($text);
        } catch (GuzzleException $e) {
            Log::warning('Gemini API projection failed', ['message' => $e->getMessage()]);

            return null;
        } catch (\Throwable $e) {
            Log::warning('Gemini API projection error', ['message' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * @param  array{reserve_months: float, savings_rate: float, balance: float, monthly_income: float, monthly_expense: float}  $contextData
     */
    protected function buildProjectionPrompt(array $contextData): string
    {
        $reserveMonths = $contextData['reserve_months'] ?? 0;
        $savingsRate = $contextData['savings_rate'] ?? 0;
        $balance = $contextData['balance'] ?? 0;
        $income = $contextData['monthly_income'] ?? 0;
        $expense = $contextData['monthly_expense'] ?? 0;

        $system = "Você é o Vertex Bot CFO. Projete o patrimônio do usuário em 1 ano com base nos dados atuais. ";
        $system .= "2 a 4 frases, português Brasil. Cite a Regra 50/30/20 da Vertex. Sem nome ou PII. Apenas o texto.\n\n";

        $context = "Saldo: R\$ " . number_format($balance, 2, ',', '.') . " | Reserva: " . round($reserveMonths, 1) . " meses | ";
        $context .= "Taxa poupança: " . round($savingsRate, 1) . "% | Renda: R\$ " . number_format($income, 2, ',', '.') . " | Despesas: R\$ " . number_format($expense, 2, ',', '.') . "\n";

        return $system . $context . "\nProjeção em 1 ano:";
    }
}
