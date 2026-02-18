<?php

namespace Modules\Core\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Account;
use Modules\Core\Models\Budget;
use Modules\Core\Models\Goal;
use Modules\Core\Models\Transaction;
use Modules\Gamification\Models\UserMedal;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportService
{
    public function __construct(
        protected GeminiService $geminiService,
        protected SettingService $settingService
    ) {}

    /**
     * Base query for cash flow (excludes transfers - internal movements).
     */
    protected function cashFlowBaseQuery(User $user, $startDate, ?int $accountId = null)
    {
        $query = Transaction::where('transactions.user_id', $user->id)
            ->where('transactions.date', '>=', $startDate)
            ->whereNull('transactions.destination_account_id')
            ->whereNull('transactions.parent_id');

        if ($accountId) {
            $query->where('transactions.account_id', $accountId);
        }

        return $query;
    }

    /**
     * Get cash flow data for the current month + (N-1) months back (e.g. 6 = atual + 5 atrás).
     * Excludes transfers (internal movements) to show real income/expense.
     *
     * @param  int|null  $accountId  Optional account filter (PRO)
     */
    public function getCashFlow(User $user, int $months = 6, ?int $accountId = null): Collection
    {
        $startDate = now()->subMonths($months - 1)->startOfMonth();

        $transactions = $this->cashFlowBaseQuery($user, $startDate, $accountId)->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get();

        // Transform data into a more usable format
        $cashFlow = collect();
        $currentMonth = $startDate->copy();

        for ($i = 0; $i < $months; $i++) {
            $monthKey = $currentMonth->format('Y-m');
            $monthName = $currentMonth->locale('pt_BR')->translatedFormat('M/Y');

            $income = $transactions->where('month', $monthKey)->where('type', 'income')->sum('total');
            $expense = $transactions->where('month', $monthKey)->where('type', 'expense')->sum('total');

            $cashFlow->push([
                'month' => $monthName,
                'month_key' => $monthKey,
                'income' => (float) $income,
                'expense' => (float) $expense,
                'balance' => (float) ($income - $expense),
            ]);

            $currentMonth->addMonth();
        }

        return $cashFlow;
    }

    /**
     * Get cash flow broken down by account (month, account_name, income, expense, balance).
     *
     * @param  int|null  $accountId  Optional account filter (when set, returns single account)
     */
    public function getCashFlowByAccount(User $user, int $months = 6, ?int $accountId = null): Collection
    {
        $startDate = now()->subMonths($months - 1)->startOfMonth();

        $rows = $this->cashFlowBaseQuery($user, $startDate, $accountId)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->select(
                DB::raw('DATE_FORMAT(transactions.date, "%Y-%m") as month'),
                'accounts.name as account_name',
                'transactions.account_id',
                DB::raw('SUM(CASE WHEN transactions.type = "income" THEN transactions.amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN transactions.type = "expense" THEN transactions.amount ELSE 0 END) as expense')
            )
            ->groupBy(DB::raw('DATE_FORMAT(transactions.date, "%Y-%m")'), 'accounts.id', 'accounts.name', 'transactions.account_id')
            ->orderBy('month')
            ->orderBy('account_name')
            ->get();

        return $rows->map(fn ($r) => [
            'month' => $r->month,
            'month_name' => \Carbon\Carbon::parse($r->month . '-01')->locale('pt_BR')->translatedFormat('M/Y'),
            'account_name' => $r->account_name,
            'account_id' => $r->account_id,
            'income' => (float) $r->income,
            'expense' => (float) $r->expense,
            'balance' => (float) ($r->income - $r->expense),
        ]);
    }

    /**
     * Get top expense categories by month for cash flow period.
     *
     * @param  int|null  $accountId  Optional account filter
     * @param  int  $limit  Max categories per month
     */
    public function getCashFlowByCategory(User $user, int $months = 6, ?int $accountId = null, int $limit = 5): Collection
    {
        $startDate = now()->subMonths($months - 1)->startOfMonth();

        $rows = $this->cashFlowBaseQuery($user, $startDate, $accountId)
            ->where('transactions.type', 'expense')
            ->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                DB::raw('DATE_FORMAT(transactions.date, "%Y-%m") as month'),
                'transactions.category_id',
                DB::raw('MAX(COALESCE(categories.name, "Sem categoria")) as category_name'),
                DB::raw('MAX(COALESCE(categories.color, "#64748b")) as color'),
                DB::raw('SUM(transactions.amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('DATE_FORMAT(transactions.date, "%Y-%m")'), 'transactions.category_id')
            ->orderBy('month')
            ->orderByDesc('total')
            ->get();

        $result = collect();
        $grouped = $rows->groupBy('month');
        foreach ($grouped as $month => $items) {
            $monthName = \Carbon\Carbon::parse($month . '-01')->locale('pt_BR')->translatedFormat('M/Y');
            foreach ($items->sortByDesc('total')->take($limit) as $r) {
                $result->push([
                    'month' => $month,
                    'month_name' => $monthName,
                    'category' => $r->category_name ?? 'Sem categoria',
                    'color' => $r->color ?? '#64748b',
                    'total' => (float) $r->total,
                    'count' => (int) $r->count,
                ]);
            }
        }

        return $result;
    }

    /**
     * Get top categories for entire period (for dashboard summary).
     */
    public function getTopCategoriesForPeriod(User $user, int $months = 6, ?int $accountId = null, int $limit = 5): Collection
    {
        $startDate = now()->subMonths($months - 1)->startOfMonth();

        return $this->cashFlowBaseQuery($user, $startDate, $accountId)
            ->where('type', 'expense')
            ->with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(fn ($item) => [
                'category' => $item->category?->name ?? 'Sem categoria',
                'total' => (float) $item->total,
                'count' => (int) $item->count,
                'color' => $item->category?->color ?? '#64748b',
            ]);
    }

    /**
     * Get detailed transactions for cash flow export (with account, category).
     *
     * @param  int|null  $accountId  Optional account filter
     */
    public function getCashFlowDetail(User $user, int $months = 6, ?int $accountId = null): Collection
    {
        $startDate = now()->subMonths($months - 1)->startOfMonth();
        $endDate = now()->endOfMonth();

        return $this->cashFlowBaseQuery($user, $startDate, $accountId)
            ->with(['category', 'account'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get income/expense summary for cash flow period (excludes transfers).
     */
    public function getCashFlowSummary(User $user, int $months = 6, ?int $accountId = null): array
    {
        $startDate = now()->subMonths($months - 1)->startOfMonth();
        $endDate = now()->endOfMonth();

        $income = $this->cashFlowBaseQuery($user, $startDate, $accountId)
            ->where('type', 'income')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $expense = $this->cashFlowBaseQuery($user, $startDate, $accountId)
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return [
            'income' => (float) $income,
            'expense' => (float) $expense,
            'balance' => (float) ($income - $expense),
            'savings_rate' => $income > 0 ? ((float) (($income - $expense) / $income) * 100) : 0,
        ];
    }

    /**
     * Get category ranking by spending.
     *
     * @param  int|null  $accountId  Optional account filter (PRO)
     */
    public function getCategoryRanking(User $user, Carbon $startDate, Carbon $endDate, ?int $accountId = null): Collection
    {
        $query = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($accountId) {
            $query->where('account_id', $accountId);
        }

        return $query->with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category?->name ?? 'Sem categoria',
                    'total' => (float) $item->total,
                    'count' => (int) $item->count,
                    'icon' => $item->category?->icon ?? 'circle-dollar',
                    'color' => $item->category?->color ?? '#64748b',
                ];
            });
    }

    /**
     * Get bank statement (extrato): individual transactions with running balance.
     *
     * @param  int|null  $accountId  Optional account filter
     * @param  string|null  $type  Optional: 'income', 'expense', or null for all
     */
    public function getBankStatement(User $user, ?Carbon $startDate, ?Carbon $endDate, ?int $accountId = null, ?string $type = null): Collection
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        $query = Transaction::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['category', 'account'])
            ->orderBy('date')
            ->orderBy('id');

        if ($accountId) {
            $query->where('account_id', $accountId);
        }

        if ($type && in_array($type, ['income', 'expense'], true)) {
            $query->where('type', $type);
        }

        $transactions = $query->get();
        $runningBalance = 0;
        $result = collect();

        foreach ($transactions as $t) {
            $amount = (float) $t->amount;
            if ($t->type === 'income') {
                $runningBalance += $amount;
            } else {
                $runningBalance -= $amount;
            }
            $result->push([
                'transaction' => $t,
                'credit' => $t->type === 'income' ? $amount : 0,
                'debit' => $t->type === 'expense' ? $amount : 0,
                'balance' => $runningBalance,
            ]);
        }

        return $result;
    }

    /**
     * Get totals for bank statement.
     */
    public function getBankStatementTotals(Collection $statement): array
    {
        $totalCredit = $statement->sum('credit');
        $totalDebit = $statement->sum('debit');
        $finalBalance = $statement->isNotEmpty() ? $statement->last()['balance'] : 0;

        return [
            'total_credit' => $totalCredit,
            'total_debit' => $totalDebit,
            'final_balance' => $finalBalance,
        ];
    }

    /**
     * Export data to CSV with optional header block and totals row.
     */
    public function exportToCsv(
        Collection $data,
        string $filename,
        ?array $headerBlock = null,
        ?array $totalsRow = null
    ): string {
        $csvPath = storage_path('app/exports/' . $filename . '.csv');

        if (!file_exists(dirname($csvPath))) {
            mkdir(dirname($csvPath), 0755, true);
        }

        $file = fopen($csvPath, 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        if ($headerBlock) {
            foreach ($headerBlock as $row) {
                fputcsv($file, is_array($row) ? $row : [$row], ';');
            }
            fputcsv($file, [], ';');
        }

        if ($data->isNotEmpty()) {
            fputcsv($file, array_keys($data->first()), ';');
            foreach ($data as $row) {
                fputcsv($file, $row, ';');
            }
        }

        if ($totalsRow) {
            fputcsv($file, [], ';');
            fputcsv($file, $totalsRow, ';');
        }

        fclose($file);

        return $csvPath;
    }

    /**
     * Export cash flow to CSV with professional header and totals.
     */
    public function exportCashFlowToCsv(Collection $cashFlow, string $filename, string $companyName, string $periodLabel): string
    {
        $headerBlock = [
            [$companyName],
            ['Relatório: Fluxo de Caixa'],
            ['Período: ' . $periodLabel],
            ['Gerado em: ' . now()->format('d/m/Y H:i')],
        ];

        $totalsRow = null;
        if ($cashFlow->isNotEmpty()) {
            $totalIncome = $cashFlow->sum('income');
            $totalExpense = $cashFlow->sum('expense');
            $totalBalance = $cashFlow->sum('balance');
            $totalsRow = ['TOTAL', format_currency($totalIncome, 'R$', false), format_currency($totalExpense, 'R$', false), format_currency($totalBalance, 'R$', false)];
        }

        return $this->exportToCsv($cashFlow, $filename, $headerBlock, $totalsRow);
    }

    /**
     * Export cash flow to XLSX with multiple sheets: Resumo, Por Conta, Por Categoria, Detalhes.
     */
    public function exportCashFlowToXlsx(
        Collection $cashFlow,
        Collection $byAccount,
        Collection $byCategory,
        Collection $detail,
        string $filename,
        string $brandLabel,
        string $periodLabel
    ): string {
        $path = storage_path('app/exports/' . $filename . '.xlsx');
        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $spreadsheet = new Spreadsheet;

        $headerStyle = function ($sheet, $row, $colCount) {
            $range = 'A' . $row . ':' . Coordinate::stringFromColumnIndex($colCount) . $row;
            $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('0d9488');
            $sheet->getStyle($range)->getFont()->setBold(true)->setColor(new Color('FFFFFF'));
        };

        $totalStyle = function ($sheet, $row, $colCount) {
            $range = 'A' . $row . ':' . Coordinate::stringFromColumnIndex($colCount) . $row;
            $sheet->getStyle($range)->getFont()->setBold(true);
            $sheet->getStyle($range)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        };

        // Sheet 1: Resumo
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Resumo');
        $r = 1;
        $sheet->setCellValue('A' . $r, $brandLabel);
        $sheet->mergeCells('A' . $r . ':D' . $r);
        $sheet->getStyle('A' . $r)->getFont()->setBold(true)->setSize(14);
        $r++;
        $sheet->setCellValue('A' . $r, 'Relatório: Fluxo de Caixa');
        $sheet->mergeCells('A' . $r . ':D' . $r);
        $r++;
        $sheet->setCellValue('A' . $r, 'Período: ' . $periodLabel);
        $sheet->mergeCells('A' . $r . ':D' . $r);
        $r += 2;
        foreach (['Mês', 'Receitas', 'Despesas', 'Saldo'] as $c => $h) {
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c + 1) . $r, $h);
        }
        $headerStyle($sheet, $r, 4);
        $r++;
        foreach ($cashFlow as $item) {
            $sheet->setCellValue('A' . $r, $item['month']);
            $sheet->setCellValue('B' . $r, $item['income']);
            $sheet->setCellValue('C' . $r, $item['expense']);
            $sheet->setCellValue('D' . $r, $item['balance']);
            $sheet->getStyle('B' . $r . ':D' . $r)->getNumberFormat()->setFormatCode('#,##0.00');
            if ($item['balance'] < 0) {
                $sheet->getStyle('D' . $r)->getFont()->setColor(new Color('dc2626'));
            }
            $r++;
        }
        if ($cashFlow->isNotEmpty()) {
            $sheet->setCellValue('A' . $r, 'TOTAL');
            $sheet->setCellValue('B' . $r, $cashFlow->sum('income'));
            $sheet->setCellValue('C' . $r, $cashFlow->sum('expense'));
            $sheet->setCellValue('D' . $r, $cashFlow->sum('balance'));
            $sheet->getStyle('B' . $r . ':D' . $r)->getNumberFormat()->setFormatCode('#,##0.00');
            $totalStyle($sheet, $r, 4);
        }

        // Sheet 2: Por Conta
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Por Conta');
        $r = 1;
        $sheet2->setCellValue('A' . $r, $brandLabel);
        $sheet2->mergeCells('A' . $r . ':F' . $r);
        $r++;
        $sheet2->setCellValue('A' . $r, 'Fluxo de Caixa por Conta');
        $sheet2->mergeCells('A' . $r . ':F' . $r);
        $r++;
        $sheet2->setCellValue('A' . $r, 'Período: ' . $periodLabel);
        $sheet2->mergeCells('A' . $r . ':F' . $r);
        $r += 2;
        foreach (['Mês', 'Conta', 'Receitas', 'Despesas', 'Saldo'] as $c => $h) {
            $sheet2->setCellValue(Coordinate::stringFromColumnIndex($c + 1) . $r, $h);
        }
        $headerStyle($sheet2, $r, 5);
        $r++;
        foreach ($byAccount as $item) {
            $sheet2->setCellValue('A' . $r, $item['month_name']);
            $sheet2->setCellValue('B' . $r, $item['account_name']);
            $sheet2->setCellValue('C' . $r, $item['income']);
            $sheet2->setCellValue('D' . $r, $item['expense']);
            $sheet2->setCellValue('E' . $r, $item['balance']);
            $sheet2->getStyle('C' . $r . ':E' . $r)->getNumberFormat()->setFormatCode('#,##0.00');
            $r++;
        }

        // Sheet 3: Por Categoria
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Por Categoria');
        $r = 1;
        $sheet3->setCellValue('A' . $r, $brandLabel);
        $sheet3->mergeCells('A' . $r . ':E' . $r);
        $r++;
        $sheet3->setCellValue('A' . $r, 'Top Categorias de Despesa por Mês');
        $sheet3->mergeCells('A' . $r . ':E' . $r);
        $r++;
        $sheet3->setCellValue('A' . $r, 'Período: ' . $periodLabel);
        $sheet3->mergeCells('A' . $r . ':E' . $r);
        $r += 2;
        foreach (['Mês', 'Categoria', 'Total Despesas', 'Qtd'] as $c => $h) {
            $sheet3->setCellValue(Coordinate::stringFromColumnIndex($c + 1) . $r, $h);
        }
        $headerStyle($sheet3, $r, 4);
        $r++;
        foreach ($byCategory as $item) {
            $sheet3->setCellValue('A' . $r, $item['month_name']);
            $sheet3->setCellValue('B' . $r, $item['category']);
            $sheet3->setCellValue('C' . $r, $item['total']);
            $sheet3->setCellValue('D' . $r, $item['count']);
            $sheet3->getStyle('C' . $r)->getNumberFormat()->setFormatCode('#,##0.00');
            $r++;
        }

        // Sheet 4: Detalhes
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Detalhes');
        $r = 1;
        $sheet4->setCellValue('A' . $r, $brandLabel);
        $sheet4->mergeCells('A' . $r . ':F' . $r);
        $r++;
        $sheet4->setCellValue('A' . $r, 'Transações Detalhadas (Conta, Categoria)');
        $sheet4->mergeCells('A' . $r . ':F' . $r);
        $r++;
        $sheet4->setCellValue('A' . $r, 'Período: ' . $periodLabel);
        $sheet4->mergeCells('A' . $r . ':F' . $r);
        $r += 2;
        foreach (['Data', 'Descrição', 'Categoria', 'Conta', 'Tipo', 'Valor'] as $c => $h) {
            $sheet4->setCellValue(Coordinate::stringFromColumnIndex($c + 1) . $r, $h);
        }
        $headerStyle($sheet4, $r, 6);
        $r++;
        foreach ($detail as $t) {
            $sheet4->setCellValue('A' . $r, $t->date->format('d/m/Y'));
            $sheet4->setCellValue('B' . $r, $t->description ?? '—');
            $sheet4->setCellValue('C' . $r, $t->category?->name ?? '—');
            $sheet4->setCellValue('D' . $r, $t->account?->name ?? '—');
            $sheet4->setCellValue('E' . $r, $t->type === 'income' ? 'Receita' : 'Despesa');
            $sheet4->setCellValue('F' . $r, $t->amount);
            $sheet4->getStyle('F' . $r)->getNumberFormat()->setFormatCode('#,##0.00');
            if ($t->type === 'expense') {
                $sheet4->getStyle('F' . $r)->getFont()->setColor(new Color('dc2626'));
            }
            $r++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return $path;
    }

    /**
     * Export category ranking to CSV with professional header and totals.
     */
    public function exportCategoryRankingToCsv(Collection $ranking, string $filename, string $companyName, string $periodLabel): string
    {
        $headerBlock = [
            [$companyName],
            ['Relatório: Ranking de Categorias'],
            ['Período: ' . $periodLabel],
            ['Gerado em: ' . now()->format('d/m/Y H:i')],
        ];

        $rows = $ranking->map(fn ($item) => [
            'Categoria' => $item['category'],
            'Transações' => $item['count'],
            'Total' => format_currency($item['total'], 'R$', false),
        ]);

        $totalsRow = null;
        if ($ranking->isNotEmpty()) {
            $total = $ranking->sum('total');
            $totalsRow = ['TOTAL', $ranking->sum('count'), format_currency($total, 'R$', false)];
        }

        return $this->exportToCsv($rows, $filename, $headerBlock, $totalsRow);
    }

    /**
     * Export bank statement to CSV with professional header and totals.
     */
    public function exportBankStatementToCsv(Collection $statement, string $filename, string $companyName, string $periodLabel): string
    {
        $headerBlock = [
            [$companyName],
            ['Relatório: Extrato Vertex'],
            ['Período: ' . $periodLabel],
            ['Gerado em: ' . now()->format('d/m/Y H:i')],
        ];

        $rows = $statement->map(function ($item) {
            return [
                'Data' => $item['transaction']->date->format('d/m/Y'),
                'Descrição' => $item['transaction']->description ?? '—',
                'Categoria' => $item['transaction']->category?->name ?? '—',
                'Conta' => $item['transaction']->account?->name ?? '—',
                'Crédito' => format_currency($item['credit'], 'R$', false),
                'Débito' => format_currency($item['debit'], 'R$', false),
                'Saldo' => format_currency($item['balance'], 'R$', false),
            ];
        });

        $totals = $this->getBankStatementTotals($statement);
        $totalsRow = ['TOTAL', '', '', '', format_currency($totals['total_credit'], 'R$', false), format_currency($totals['total_debit'], 'R$', false), format_currency($totals['final_balance'], 'R$', false)];

        return $this->exportToCsv($rows, $filename, $headerBlock, $totalsRow);
    }

    /**
     * Export bank statement to XLSX with professional formatting (colors, zebra, totals).
     */
    public function exportBankStatementToXlsx(
        Collection $statement,
        string $filename,
        string $brandLabel,
        string $periodLabel
    ): string {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Extrato Vertex');

        $row = 1;

        // Header block
        $sheet->setCellValue('A' . $row, $brandLabel);
        $sheet->mergeCells('A' . $row . ':G' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
        $row++;

        $sheet->setCellValue('A' . $row, 'Relatório: Extrato Vertex');
        $sheet->mergeCells('A' . $row . ':G' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setSize(11);
        $row++;

        $sheet->setCellValue('A' . $row, 'Período: ' . $periodLabel);
        $sheet->mergeCells('A' . $row . ':G' . $row);
        $row++;

        $sheet->setCellValue('A' . $row, 'Gerado em: ' . now()->format('d/m/Y H:i'));
        $sheet->mergeCells('A' . $row . ':G' . $row);
        $row += 2;

        // Table headers
        $headers = ['Data', 'Descrição', 'Categoria', 'Conta', 'Crédito', 'Débito', 'Saldo'];
        foreach ($headers as $col => $h) {
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($col + 1) . $row, $h);
        }
        $headerRow = $row;
        $sheet->getStyle('A' . $headerRow . ':G' . $headerRow)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('0d9488');
        $sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->getFont()->setBold(true)->setColor(new Color('FFFFFF'));
        $sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('E' . $headerRow . ':G' . $headerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $row++;

        $totals = $this->getBankStatementTotals($statement);
        $startDataRow = $row;

        foreach ($statement as $i => $item) {
            $sheet->setCellValue('A' . $row, $item['transaction']->date->format('d/m/Y'));
            $sheet->setCellValue('B' . $row, $item['transaction']->description ?? '—');
            $sheet->setCellValue('C' . $row, $item['transaction']->category?->name ?? '—');
            $sheet->setCellValue('D' . $row, $item['transaction']->account?->name ?? '—');
            $sheet->setCellValue('E' . $row, $item['credit'] > 0 ? $item['credit'] : '');
            $sheet->setCellValue('F' . $row, $item['debit'] > 0 ? $item['debit'] : '');
            $sheet->setCellValue('G' . $row, $item['balance']);

            $sheet->getStyle('E' . $row . ':G' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('E' . $row . ':G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            if ($i % 2 === 1) {
                $sheet->getStyle('A' . $row . ':G' . $row)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('f8fafc');
            }

            if ($item['balance'] < 0) {
                $sheet->getStyle('G' . $row)->getFont()->setColor(new Color('dc2626'));
            }
            $row++;
        }

        // Totals row
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('E' . $row, $totals['total_credit']);
        $sheet->setCellValue('F' . $row, $totals['total_debit']);
        $sheet->setCellValue('G' . $row, $totals['final_balance']);

        $sheet->getStyle('A' . $row . ':G' . $row)->getFont()->setBold(true);
        $sheet->getStyle('E' . $row . ':G' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('E' . $row . ':G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A' . $row . ':G' . $row)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);
        if ($totals['final_balance'] < 0) {
            $sheet->getStyle('G' . $row)->getFont()->setColor(new Color('dc2626'));
        }
        $row++;

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(28);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(16);
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->getColumnDimension('F')->setWidth(14);
        $sheet->getColumnDimension('G')->setWidth(14);

        $path = storage_path('app/exports/' . $filename . '.xlsx');
        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return $path;
    }

    /**
     * Get income vs expense summary for a period.
     *
     * @param  int|null  $accountId  Optional account filter (PRO)
     */
    public function getIncomeExpenseSummary(User $user, Carbon $startDate, Carbon $endDate, ?int $accountId = null): array
    {
        $incomeQuery = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->where('status', 'completed')
            ->whereBetween('date', [$startDate, $endDate]);
        if ($accountId) {
            $incomeQuery->where('account_id', $accountId);
        }
        $income = $incomeQuery->sum('amount');

        $expenseQuery = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->whereBetween('date', [$startDate, $endDate]);
        if ($accountId) {
            $expenseQuery->where('account_id', $accountId);
        }
        $expense = $expenseQuery->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'savings_rate' => $income > 0 ? (($income - $expense) / $income) * 100 : 0,
        ];
    }

    /**
     * Get consulting data for Premium Financial Report (PRO only).
     * Aggregates budget analysis, financial score, medals, accounts, projection, income sources.
     * AI conclusion and projection are NOT generated here; controller uses AiConsultingReport or generates via Gemini.
     *
     * @return array{budget_analysis: array, financial_score: int, medals: \Illuminate\Support\Collection, period_label: string, accounts_summary: array, projection_data: array, income_sources: \Illuminate\Support\Collection, metrics: array}
     */
    public function getConsultingData(User $user): array
    {
        $financialHealth = app(FinancialHealthService::class);
        $gamification = app(GamificationService::class);

        $budgetAnalysis = $financialHealth->getBudgetHealthAnalysis($user);
        $gamificationData = $gamification->analyzeUser($user);
        $projectionData = $financialHealth->getProjectionData($user);
        $incomeSources = $financialHealth->getIncomeSourcesForConsulting($user);

        $accountsSummary = Account::where('user_id', $user->id)
            ->orderBy('name')
            ->get(['name', 'balance'])
            ->map(fn ($a) => ['name' => $a->name, 'balance' => (float) $a->balance])
            ->values()
            ->all();

        // 2 conquistas mais importantes do mês = 2 mais recentes (unlocked_at). Sem campo priority no Medal; manter ordenação por data.
        $medals = UserMedal::where('user_id', $user->id)
            ->whereBetween('unlocked_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->with('medal')
            ->orderByDesc('unlocked_at')
            ->get()
            ->take(2)
            ->map(fn ($um) => [
                'title' => $um->medal?->title ?? '—',
                'description' => $um->medal?->description ?? '',
                'icon' => $um->medal?->icon_name ?? 'medal',
                'color' => medal_color_hex($um->medal?->color ?? 'slate'),
                'unlocked_at' => $um->unlocked_at,
            ]);

        $goalsSummary = Goal::where('user_id', $user->id)
            ->orderBy('created_at')
            ->get()
            ->map(fn ($g) => [
                'name' => $g->name,
                'target_amount' => (float) $g->target_amount,
                'current_amount' => (float) $g->current_amount,
                'remaining_amount' => (float) $g->remaining_amount,
                'progress_pct' => round($g->progress_percentage, 1),
            ])
            ->values()
            ->all();

        $budgetsSummary = Budget::where('user_id', $user->id)
            ->with('category')
            ->get()
            ->map(fn ($b) => [
                'category' => $b->category?->name ?? 'Orçamento',
                'limit_amount' => (float) $b->limit_amount,
                'spent_amount' => (float) $b->spent_amount,
                'remaining_amount' => (float) $b->remaining_amount,
                'usage_pct' => round($b->usage_percentage, 1),
            ])
            ->values()
            ->all();

        return [
            'budget_analysis' => $budgetAnalysis,
            'financial_score' => $gamificationData['financial_score'],
            'medals' => $medals,
            'period_label' => now()->locale('pt_BR')->translatedFormat('F Y'),
            'accounts_summary' => $accountsSummary,
            'projection_data' => $projectionData,
            'income_sources' => $incomeSources,
            'metrics' => $gamificationData['metrics'] ?? [],
            'goals_summary' => $goalsSummary,
            'budgets_summary' => $budgetsSummary,
        ];
    }

    /**
     * Build enriched context for consulting conclusion and tips prompts.
     * Includes real values in R$ for AI to cite: accounts, pillars, savings goal, goals, budgets.
     */
    public function buildConsultingContextForAi(array $consultingData): array
    {
        $budget = $consultingData['budget_analysis'] ?? [];
        $income = (float) ($budget['baseline_income'] ?? 0);
        $expenses = (float) ($budget['total_expenses'] ?? 0);
        $savingsPct = (float) ($budget['savings_pct'] ?? 0);
        $pillars = $budget['pillars'] ?? [];
        $projection = $consultingData['projection_data'] ?? [];
        $balance = (float) ($projection['balance'] ?? 0);
        $reserveMonths = (float) ($projection['reserve_months'] ?? 0);

        $targetSavingsBrl = $income > 0 ? round($income * 0.20, 2) : 0.0;
        $currentSavingsBrl = max(0, $income - $expenses);
        $needToSaveBrl = max(0, $targetSavingsBrl - $currentSavingsBrl);

        $pillarsBrl = [];
        $labels = ['essential' => 'Essencial', 'lifestyle' => 'Estilo de Vida', 'financial' => 'Financeiro'];
        $targets = ['essential' => 0.50, 'lifestyle' => 0.30, 'financial' => 0.20];
        foreach (['essential', 'lifestyle', 'financial'] as $key) {
            $p = $pillars[$key] ?? [];
            $targetPct = $targets[$key];
            $actualPct = (float) ($p['actual_pct'] ?? 0);
            $pillarsBrl[$key] = [
                'label' => $labels[$key],
                'target_brl' => round($income * $targetPct, 2),
                'actual_brl' => round($income * ($actualPct / 100), 2),
                'status' => $p['status'] ?? 'ok',
                'deviation_pct' => $p['deviation'] ?? 0,
            ];
        }

        return [
            'budget_analysis' => $budget,
            'financial_score' => $consultingData['financial_score'] ?? 0,
            'metrics' => $consultingData['metrics'] ?? [],
            'accounts_summary' => $consultingData['accounts_summary'] ?? [],
            'income_sources' => $consultingData['income_sources'] ?? collect(),
            'projection_data' => $projection,
            'goals_summary' => $consultingData['goals_summary'] ?? [],
            'budgets_summary' => $consultingData['budgets_summary'] ?? [],
            'rich_ai_context' => [
                'income_brl' => $income,
                'expenses_brl' => $expenses,
                'balance_brl' => $balance,
                'savings_pct' => $savingsPct,
                'target_savings_brl' => $targetSavingsBrl,
                'current_savings_brl' => $currentSavingsBrl,
                'need_to_save_brl' => $needToSaveBrl,
                'reserve_months' => $reserveMonths,
                'pillars_brl' => $pillarsBrl,
            ],
        ];
    }

    /**
     * Generate AI conclusion, projection and 6 tips for consulting report.
     *
     * @return array{conclusion: string|null, projection: string|null, ai_tips: array<int, string>}
     */
    public function generateConsultingAiContent(array $consultingData): array
    {
        $contextData = $this->buildConsultingContextForAi($consultingData);
        $conclusion = $this->geminiService->generateConsultingConclusion($contextData);
        $aiTips = $this->geminiService->generateConsultingTips($contextData);

        if ($aiTips === null || empty($aiTips)) {
            $aiTips = $this->buildPersonalizedTipsFromData($contextData);
            while (count($aiTips) < 6) {
                $aiTips[] = 'Use as metas e orçamentos da Vertex Contas para acompanhar seus gastos mensalmente.';
            }
            $aiTips = array_slice($aiTips, 0, 6);
        }

        $projectionData = $consultingData['projection_data'] ?? [];
        $projectionContext = [
            'reserve_months' => $projectionData['reserve_months'] ?? 0,
            'savings_rate' => $projectionData['savings_rate'] ?? 0,
            'balance' => $projectionData['balance'] ?? 0,
            'monthly_income' => $projectionData['monthly_income'] ?? 0,
            'monthly_expense' => $projectionData['monthly_expense'] ?? 0,
        ];
        $projection = $this->geminiService->generateOneYearProjection($projectionContext);

        return [
            'conclusion' => $conclusion !== null && trim($conclusion) !== '' ? trim($conclusion) : null,
            'projection' => $projection !== null && trim($projection) !== '' ? trim($projection) : null,
            'ai_tips' => array_values($aiTips),
        ];
    }

    /**
     * Build personalized tips with real values when Gemini fails.
     * Uses rich_ai_context to cite accounts, amounts, savings goal, pillars, goals, budgets.
     *
     * @return array<int, string>
     */
    public function buildPersonalizedTipsFromData(array $contextData): array
    {
        $rich = $contextData['rich_ai_context'] ?? [];
        $accountsSummary = $contextData['accounts_summary'] ?? [];
        $goalsSummary = $contextData['goals_summary'] ?? [];
        $budgetsSummary = $contextData['budgets_summary'] ?? [];

        if (empty($rich)) {
            return $this->buildRecommendations($contextData['budget_analysis'] ?? []);
        }

        $tips = [];
        $fmt = fn (float $v) => 'R$ ' . number_format($v, 2, ',', '.');

        $income = (float) ($rich['income_brl'] ?? 0);
        $balance = (float) ($rich['balance_brl'] ?? 0);
        $expenses = (float) ($rich['expenses_brl'] ?? 0);
        $needToSave = (float) ($rich['need_to_save_brl'] ?? 0);
        $reserveMonths = (float) ($rich['reserve_months'] ?? 0);
        $savingsPct = (float) ($rich['savings_pct'] ?? 0);
        $targetSavings = (float) ($rich['target_savings_brl'] ?? 0);
        $currentSavings = (float) ($rich['current_savings_brl'] ?? 0);
        $pillarsBrl = $rich['pillars_brl'] ?? [];

        if (! empty($accountsSummary)) {
            $firstAccount = $accountsSummary[0];
            $name = $firstAccount['name'] ?? 'conta';
            $bal = (float) ($firstAccount['balance'] ?? 0);
            $tips[] = "Na sua conta {$name} você tem {$fmt($bal)}. Sua reserva cobre " . round($reserveMonths, 1) . " meses de despesas (meta: 3 a 6 meses, conforme recomenda o Banco Central).";
        } elseif ($balance > 0) {
            $tips[] = "Seu saldo total é {$fmt($balance)}, o que equivale a " . round($reserveMonths, 1) . " meses de despesas. A reserva de emergência recomendada é de 3 a 6 meses.";
        }

        if ($savingsPct < 20 && $income > 0) {
            $tips[] = "Sua taxa de poupança está em " . round($savingsPct, 1) . "%. A Regra 50/30/20 recomenda 20% (meta {$fmt($targetSavings)}). Você economiza {$fmt($currentSavings)} — faltam {$fmt($needToSave)} por mês para atingir a meta.";
        } elseif ($savingsPct >= 20) {
            $tips[] = "Parabéns! Sua poupança de " . round($savingsPct, 1) . "% está na meta (economiza {$fmt($currentSavings)}/mês). Mantenha a disciplina.";
        }

        foreach ($pillarsBrl as $p) {
            $label = $p['label'] ?? '';
            $status = $p['status'] ?? 'ok';
            $targetBrl = (float) ($p['target_brl'] ?? 0);
            $actualBrl = (float) ($p['actual_brl'] ?? 0);
            if ($status === 'over') {
                $tips[] = "Pilar {$label}: sua meta é {$fmt($targetBrl)} (regra 50/30/20), mas você gastou {$fmt($actualBrl)}. Revise contratos fixos ou gastos variáveis nesta categoria.";
            } elseif ($status === 'under' && $label === 'Financeiro') {
                $tips[] = "Pilar Financeiro: meta {$fmt($targetBrl)}, gastou {$fmt($actualBrl)}. Aumente aportes em investimentos e reserva — use metas na Vertex Contas para acompanhar.";
            }
        }

        foreach (array_slice($goalsSummary, 0, 2) as $g) {
            $name = $g['name'] ?? 'Meta';
            $rem = (float) ($g['remaining_amount'] ?? 0);
            if ($rem > 0) {
                $tips[] = "Na meta {$name} faltam {$fmt($rem)}. Defina um valor mensal fixo para aportar e acompanhe no painel de metas da Vertex Contas.";
            }
        }

        foreach (array_slice($budgetsSummary, 0, 2) as $b) {
            $cat = $b['category'] ?? 'Categoria';
            $pct = $b['usage_pct'] ?? 0;
            if ($pct >= 80) {
                $tips[] = "O orçamento de {$cat} está em " . round($pct, 0) . "% de uso. Ajuste as próximas semanas ou revise o limite no painel da Vertex Contas.";
            }
        }

        if (count($tips) < 3) {
            $tips = array_merge($tips, $this->buildRecommendations($contextData['budget_analysis'] ?? []));
        }

        return $tips;
    }

    /**
     * Build dynamic recommendations based on 50/30/20 deviations.
     *
     * @return array<int, string>
     */
    public function buildRecommendations(array $budgetAnalysis): array
    {
        $recommendations = [];
        $pillars = $budgetAnalysis['pillars'] ?? [];
        $savingsPct = $budgetAnalysis['savings_pct'] ?? 0;

        if (($pillars['essential']['status'] ?? '') === 'over') {
            $recommendations[] = 'Sugerimos revisar contratos de serviços fixos ou buscar economia em supermercado e combustível.';
        }

        if ($savingsPct < 20) {
            $recommendations[] = 'Sua taxa de investimento está abaixo do recomendado. Reserve ao menos 20% da renda para sua segurança futura.';
        }

        if (($pillars['lifestyle']['status'] ?? '') === 'over') {
            $recommendations[] = 'Gastos com estilo de vida acima do ideal. Avalie assinaturas e lazer para reequilibrar.';
        }

        if (($pillars['financial']['status'] ?? '') === 'under') {
            $recommendations[] = 'O pilar financeiro (investimentos e reserva) está abaixo da meta. Priorize aportes mensais.';
        }

        $allOk = empty($recommendations);
        foreach (['essential', 'lifestyle', 'financial'] as $key) {
            if (($pillars[$key]['status'] ?? '') !== 'ok') {
                $allOk = false;
                break;
            }
        }
        if ($savingsPct < 20) {
            $allOk = false;
        }

        if ($allOk) {
            $recommendations = ['Parabéns! Seus gastos estão alinhados à regra 50/30/20.'];
        }

        return $recommendations;
    }
}
