<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected const API_BASE = 'https://generativelanguage.googleapis.com/v1beta';

    protected const MODEL = 'gemini-1.5-flash';

    protected const TIMEOUT = 10;

    /** Maximum statement entries sent to Gemini for categorization; rest use fallback by type. */
    public const CATEGORIZE_MAX_ENTRIES = 200;

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
            $isTimeout = $e instanceof ConnectException || str_contains($e->getMessage(), 'timed out');
            Log::warning($isTimeout ? 'Gemini API timeout (Vertex Bot fallback)' : 'Gemini API request failed', [
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
                        'maxOutputTokens' => 1024,
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
            $isTimeout = $e instanceof ConnectException || str_contains($e->getMessage(), 'timed out');
            Log::warning($isTimeout ? 'Gemini API timeout (PDF consulting fallback)' : 'Gemini API consulting conclusion failed', [
                'message' => $e->getMessage(),
                'context' => 'consulting_conclusion',
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::warning('Gemini API consulting conclusion error', ['message' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Build prompt for consulting conclusion. Rich context: accounts, income sources, projection.
     *
     * @param  array{budget_analysis: array, financial_score: int, metrics?: array, accounts_summary?: array, income_sources?: \Illuminate\Support\Collection, projection_data?: array}  $contextData
     */
    protected function buildConsultingConclusionPrompt(array $contextData): string
    {
        $score = $contextData['financial_score'] ?? 0;
        $budget = $contextData['budget_analysis'] ?? [];
        $pillars = $budget['pillars'] ?? [];
        $metrics = $contextData['metrics'] ?? [];
        $accountsSummary = $contextData['accounts_summary'] ?? [];
        $incomeSources = $contextData['income_sources'] ?? collect();
        $projectionData = $contextData['projection_data'] ?? [];
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

        $system = "Você é um Consultor de Investimentos de banco privado. Escreva a Conclusão Estratégica para um relatório financeiro (Vertex Business Statement). ";
        $system .= "Tom profissional, objetivo e consultivo (trate o cliente como 'você' e 'sua conta'). ";
        $system .= "Responda em EXATAMENTE dois blocos de texto, sem listas ou tópicos:\n\n";
        $system .= "1) Primeiro parágrafo: Comece com a frase 'Sua saúde financeira este mês apresenta um Score de [X]/100.' (use o score real do contexto). ";
        $system .= "Em seguida, 1 a 2 frases sobre aderência aos pilares 50/30/20 e o principal desvio ou categoria que puxou (ex: 'desejos pessoais impulsionado por Alimentação Fora').\n\n";
        $system .= "2) Segundo parágrafo: Comece com '**Recomendação:**' (em negrito) e escreva UMA recomendação concreta com valores, percentuais ou prazos quando possível ";
        $system .= "(ex: reduzir teto em X%, manter taxa de poupança Y%, meta atingida em Z anos). Use a metodologia Regra 50/30/20 da Vertex. ";
        $system .= "Mencione contas por nome quando relevante. Evite frases genéricas—cite números do contexto. Sem nome real, CPF ou dados pessoais. Responda apenas o texto.\n\n";

        $context = "Contexto:\n";
        $context .= "- Score: {$score}/100 | Poupança real: " . round($savingsPct, 1) . "%\n";
        $context .= "- Desvios 50/30/20: {$devText}\n";
        $context .= "- Renda mensal base: R\$ " . number_format($income, 2, ',', '.') . " | Despesas do mês: R\$ " . number_format($expense, 2, ',', '.') . " | Saldo total: R\$ " . number_format($balance, 2, ',', '.') . "\n";

        if (! empty($accountsSummary)) {
            $accLines = array_map(fn ($a) => $a['name'] . ': R$ ' . number_format($a['balance'], 2, ',', '.'), $accountsSummary);
            $context .= "- Contas: " . implode(' | ', $accLines) . "\n";
        }

        if ($incomeSources->isNotEmpty()) {
            $incLines = $incomeSources->map(fn ($s) => ($s['description'] ?? 'Receita') . ': R$ ' . number_format($s['amount'], 2, ',', '.'))->implode(' | ');
            $context .= "- Fontes de renda (fixas/recorrentes): {$incLines}\n";
        }

        if (! empty($projectionData)) {
            $reserveMonths = $projectionData['reserve_months'] ?? 0;
            $savingsRate = $projectionData['savings_rate'] ?? 0;
            $context .= "- Reserva de emergência: " . round($reserveMonths, 1) . " meses de despesas | Taxa de poupança: " . round($savingsRate, 1) . "%\n";
        }

        return $system . $context . "\nConclusão estratégica:";
    }

    /**
     * Generate 6 personalized tips for the consulting report.
     * Uses client data (accounts, savings, 50/30/20) to create actionable tips.
     * Returns array of strings or null on failure.
     *
     * @param  array{budget_analysis: array, financial_score: int, metrics?: array, accounts_summary?: array, income_sources?: \Illuminate\Support\Collection, projection_data?: array}  $contextData
     * @return array<int, string>|null
     */
    public function generateConsultingTips(array $contextData): ?array
    {
        $apiKey = $this->getApiKey();
        if (! $apiKey) {
            return null;
        }

        $prompt = $this->buildConsultingTipsPrompt($contextData);

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
                        'maxOutputTokens' => 1200,
                        'temperature' => 0.6,
                        'topP' => 0.9,
                    ],
                ],
                RequestOptions::TIMEOUT => 30,
            ]);

            $body = json_decode((string) $response->getBody(), true);
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($text === null || trim($text) === '') {
                return null;
            }

            $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', trim($text))));
            $tips = [];
            foreach ($lines as $line) {
                $line = preg_replace('/^\d+[\.\)]\s*/', '', $line);
                if (strlen($line) > 10) {
                    $tips[] = $line;
                }
            }

            return array_slice($tips, 0, 6) ?: null;
        } catch (GuzzleException $e) {
            Log::warning('Gemini API consulting tips failed', [
                'message' => $e->getMessage(),
                'context' => 'consulting_tips',
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::warning('Gemini API consulting tips error', ['message' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Build prompt for consulting tips. Rich context with real values in R$ — AI MUST cite them.
     *
     * @param  array{budget_analysis: array, financial_score: int, accounts_summary?: array, income_sources?: \Illuminate\Support\Collection, projection_data?: array, goals_summary?: array, budgets_summary?: array, rich_ai_context?: array}  $contextData
     */
    protected function buildConsultingTipsPrompt(array $contextData): string
    {
        $score = $contextData['financial_score'] ?? 0;
        $rich = $contextData['rich_ai_context'] ?? [];
        $accountsSummary = $contextData['accounts_summary'] ?? [];
        $incomeSources = $contextData['income_sources'] ?? collect();
        $goalsSummary = $contextData['goals_summary'] ?? [];
        $budgetsSummary = $contextData['budgets_summary'] ?? [];

        $fmt = fn (float $v) => 'R$ ' . number_format($v, 2, ',', '.');

        $system = "Você é um Consultor de Investimentos certificado. Baseie suas dicas em fontes seguras e nos DADOS REAIS abaixo.\n\n";
        $system .= "FONTES SEGURAS (princípios reconhecidos):\n";
        $system .= "- Regra 50/30/20 (Elizabeth Warren): 50% necessários, 30% estilo de vida, 20% poupança/investimentos.\n";
        $system .= "- Reserva de emergência: 3 a 6 meses de despesas (Banco Central, CVM, planejadores financeiros).\n";
        $system .= "- Poupança mínima recomendada: 20% da renda líquida.\n";
        $system .= "- Orçamento por categoria: controle preventivo evita endividamento.\n\n";
        $system .= "INSTRUÇÃO OBRIGATÓRIA: Cada dica DEVE citar valores reais do bloco DADOS REAIS — R$, nomes de contas, percentuais exatos. ";
        $system .= "Estruture 6 dicas cobrindo: (1) saldo em contas e reserva em meses; (2) poupança atual vs meta 20%; ";
        $system .= "(3-4) pilares 50/30/20 com metas e gastos em R$; (5) metas cadastradas ou orçamentos se houver; (6) ação concreta. ";
        $system .= "PROIBIDO: 'Priorize aportes', 'reduza gastos', 'pilar abaixo da meta' sem números. ";
        $system .= "Use os dados. Tom consultivo. Sem PII. Responda APENAS 6 linhas numeradas (1. 2. 3. ...).\n\n";

        $ctx = "DADOS REAIS DO CLIENTE (use estes valores nas dicas):\n\n";
        $ctx .= "Contas e saldo total:\n";
        $totalBalance = 0.0;
        foreach ($accountsSummary as $a) {
            $b = (float) ($a['balance'] ?? 0);
            $totalBalance += $b;
            $ctx .= "- {$a['name']}: {$fmt($b)}\n";
        }
        if (empty($accountsSummary)) {
            $totalBalance = $rich['balance_brl'] ?? 0;
            $ctx .= "- Saldo total: {$fmt($totalBalance)}\n";
        } else {
            $ctx .= "- Saldo total em contas: {$fmt($totalBalance)}\n";
        }

        $income = $rich['income_brl'] ?? 0;
        $expenses = $rich['expenses_brl'] ?? 0;
        $targetSavings = $rich['target_savings_brl'] ?? 0;
        $currentSavings = $rich['current_savings_brl'] ?? 0;
        $needToSave = $rich['need_to_save_brl'] ?? 0;
        $reserveMonths = $rich['reserve_months'] ?? 0;

        $ctx .= "\nRenda, despesas e poupança:\n";
        $ctx .= "- Renda mensal base: {$fmt($income)}\n";
        $ctx .= "- Despesas do mês: {$fmt($expenses)}\n";
        $ctx .= "- Poupança atual: {$fmt($currentSavings)} (" . round($rich['savings_pct'] ?? 0, 1) . "% da renda)\n";
        $ctx .= "- Meta de poupança (20%): {$fmt($targetSavings)}\n";
        $ctx .= "- Para alcançar a meta precisa economizar mais: {$fmt($needToSave)}/mês\n";
        $ctx .= "- Reserva de emergência: " . round($reserveMonths, 1) . " meses de despesas\n";

        $pillarsBrl = $rich['pillars_brl'] ?? [];
        if (! empty($pillarsBrl)) {
            $ctx .= "\nRegra 50/30/20 (meta vs gasto real em R\$):\n";
            foreach ($pillarsBrl as $p) {
                $ctx .= "- {$p['label']}: meta {$fmt($p['target_brl'])}, gastou {$fmt($p['actual_brl'])} ({$p['status']})\n";
            }
        }

        if (! empty($incomeSources) && $incomeSources->isNotEmpty()) {
            $ctx .= "\nFontes de renda:\n";
            foreach ($incomeSources as $s) {
                $desc = $s['description'] ?? 'Receita';
                $amt = (float) (is_array($s) ? ($s['amount'] ?? 0) : 0);
                $ctx .= "- " . $desc . ': ' . $fmt($amt) . "\n";
            }
        }

        if (! empty($goalsSummary)) {
            $ctx .= "\nMetas cadastradas:\n";
            foreach ($goalsSummary as $g) {
                $cur = (float) ($g['current_amount'] ?? 0);
                $tgt = (float) ($g['target_amount'] ?? 0);
                $rem = (float) ($g['remaining_amount'] ?? 0);
                $prg = $g['progress_pct'] ?? 0;
                $ctx .= "- " . ($g['name'] ?? '') . ': ' . $fmt($cur) . ' de ' . $fmt($tgt) . " ({$prg}%) — faltam " . $fmt($rem) . "\n";
            }
        }

        if (! empty($budgetsSummary)) {
            $ctx .= "\nOrçamentos por categoria:\n";
            foreach ($budgetsSummary as $b) {
                $spent = (float) ($b['spent_amount'] ?? 0);
                $limit = (float) ($b['limit_amount'] ?? 0);
                $pct = $b['usage_pct'] ?? 0;
                $ctx .= "- " . ($b['category'] ?? '') . ': gastou ' . $fmt($spent) . ' de ' . $fmt($limit) . " ({$pct}%)\n";
            }
        }

        $ctx .= "\nScore financeiro: {$score}/100\n";

        return $system . $ctx . "\n6 dicas personalizadas (cite os valores acima, uma por linha):";
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
            $isTimeout = $e instanceof ConnectException || str_contains($e->getMessage(), 'timed out');
            Log::warning($isTimeout ? 'Gemini API timeout (projection fallback)' : 'Gemini API projection failed', [
                'message' => $e->getMessage(),
                'context' => 'one_year_projection',
            ]);

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

    /**
     * Categorize statement entries using Gemini: map each description to category_id and pillar.
     * Entries: [ ['index' => int, 'description' => string, 'amount' => float], ... ]
     * Categories: [ ['id' => int, 'name' => string, 'type' => 'income'|'expense', 'type_group' => string], ... ]
     * Returns: [ index => ['category_id' => int, 'pillar' => 'essential'|'lifestyle'|'financial'], ... ]
     */
    public function categorizeStatementEntries(array $entries, array $categories): array
    {
        $apiKey = $this->getApiKey();
        if (! $apiKey || empty($entries) || empty($categories)) {
            return $this->fallbackCategorize($entries, $categories);
        }

        $prompt = $this->buildCategorizePrompt($entries, $categories);

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
                        'maxOutputTokens' => 4096,
                        'temperature' => 0.3,
                        'topP' => 0.9,
                    ],
                ],
                RequestOptions::TIMEOUT => 30,
            ]);

            $body = json_decode((string) $response->getBody(), true);
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($text === null || trim($text) === '') {
                return $this->fallbackCategorize($entries, $categories);
            }

            $text = trim($text);
            if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $text, $m)) {
                $text = trim($m[1]);
            }
            $decoded = json_decode($text, true);
            if (! is_array($decoded)) {
                return $this->fallbackCategorize($entries, $categories);
            }

            $result = [];
            $categoryIds = array_column($categories, 'id');
            $byType = [];
            foreach ($categories as $c) {
                $t = $c['type'] ?? 'expense';
                if (! isset($byType[$t])) {
                    $byType[$t] = (int) $c['id'];
                }
            }
            $defaultCat = (int) ($categoryIds[0] ?? 0);
            foreach ($entries as $entry) {
                $idx = $entry['index'] ?? 0;
                $item = $decoded[$idx] ?? $decoded[(string) $idx] ?? null;
                $type = ($entry['amount'] ?? 0) >= 0 ? 'income' : 'expense';
                if ($item !== null && isset($item['category_id']) && in_array((int) $item['category_id'], $categoryIds, true)) {
                    $catId = (int) $item['category_id'];
                    $pillar = isset($item['pillar']) && in_array($item['pillar'], ['essential', 'lifestyle', 'financial'], true)
                        ? $item['pillar']
                        : 'lifestyle';
                } else {
                    $catId = $byType[$type] ?? $defaultCat;
                    $pillar = 'lifestyle';
                }
                $result[$idx] = ['category_id' => $catId, 'pillar' => $pillar];
            }

            return $result;
        } catch (GuzzleException $e) {
            Log::warning('Gemini API categorize statement failed', ['message' => $e->getMessage()]);

            return $this->fallbackCategorize($entries, $categories);
        } catch (\Throwable $e) {
            Log::warning('Gemini API categorize error', ['message' => $e->getMessage()]);

            return $this->fallbackCategorize($entries, $categories);
        }
    }

    protected function buildCategorizePrompt(array $entries, array $categories): string
    {
        $catList = [];
        foreach ($categories as $c) {
            $catList[] = sprintf('id=%d name="%s" type=%s pillar=%s', $c['id'], $c['name'] ?? '', $c['type'] ?? 'expense', $c['type_group'] ?? 'lifestyle');
        }
        $entriesList = [];
        foreach ($entries as $e) {
            $entriesList[] = sprintf('index=%d description="%s" amount=%s', $e['index'], addslashes($e['description'] ?? ''), $e['amount'] ?? 0);
        }

        $system = "Você é um assistente de categorização financeira. Dado uma lista de categorias válidas e linhas de extrato (descrição e valor), retorne um JSON onde cada chave é o index da linha e o valor é { \"category_id\": <id da categoria>, \"pillar\": \"essential\" ou \"lifestyle\" ou \"financial\" }.\n";
        $system .= "Regras: use APENAS category_id que existem na lista. Para receitas (amount > 0) use categorias type=income; para despesas (amount < 0) use type=expense. pillar: essential = necessidades, lifestyle = desejos, financial = investimentos/poupança. Responda SOMENTE com o JSON, sem explicação.\n\n";
        $system .= "Categorias disponíveis:\n" . implode("\n", $catList) . "\n\n";
        $limit = (int) (config('vertex.import_categorize_max_entries') ?? self::CATEGORIZE_MAX_ENTRIES);
        $system .= "Linhas do extrato:\n" . implode("\n", array_slice($entriesList, 0, $limit)) . "\n\nJSON:";

        return $system;
    }

    /**
     * Fallback when Gemini is unavailable: assign first matching category by type.
     */
    protected function fallbackCategorize(array $entries, array $categories): array
    {
        $byType = [];
        foreach ($categories as $c) {
            $t = $c['type'] ?? 'expense';
            if (! isset($byType[$t])) {
                $byType[$t] = (int) $c['id'];
            }
        }
        $result = [];
        foreach ($entries as $e) {
            $type = ($e['amount'] ?? 0) >= 0 ? 'income' : 'expense';
            $result[$e['index'] ?? 0] = [
                'category_id' => $byType[$type] ?? (int) ($categories[0]['id'] ?? 0),
                'pillar' => 'lifestyle',
            ];
        }

        return $result;
    }
}
