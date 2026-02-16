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
}
