<?php

namespace Modules\Core\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Transaction;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportService
{
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
     * Get cash flow data for the last N months.
     * Excludes transfers (internal movements) to show real income/expense.
     *
     * @param  int|null  $accountId  Optional account filter (PRO)
     */
    public function getCashFlow(User $user, int $months = 6, ?int $accountId = null): Collection
    {
        $startDate = now()->subMonths($months)->startOfMonth();

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
        $startDate = now()->subMonths($months)->startOfMonth();

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
        $startDate = now()->subMonths($months)->startOfMonth();

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
        $startDate = now()->subMonths($months)->startOfMonth();

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
        $startDate = now()->subMonths($months)->startOfMonth();
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
        $startDate = now()->subMonths($months)->startOfMonth();
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
            $totalsRow = ['TOTAL', 'R$ ' . number_format($totalIncome, 2, ',', '.'), 'R$ ' . number_format($totalExpense, 2, ',', '.'), 'R$ ' . number_format($totalBalance, 2, ',', '.')];
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
            'Total' => 'R$ ' . number_format($item['total'], 2, ',', '.'),
        ]);

        $totalsRow = null;
        if ($ranking->isNotEmpty()) {
            $total = $ranking->sum('total');
            $totalsRow = ['TOTAL', $ranking->sum('count'), 'R$ ' . number_format($total, 2, ',', '.')];
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
                'Crédito' => 'R$ ' . number_format($item['credit'], 2, ',', '.'),
                'Débito' => 'R$ ' . number_format($item['debit'], 2, ',', '.'),
                'Saldo' => 'R$ ' . number_format($item['balance'], 2, ',', '.'),
            ];
        });

        $totals = $this->getBankStatementTotals($statement);
        $totalsRow = ['TOTAL', '', '', '', 'R$ ' . number_format($totals['total_credit'], 2, ',', '.'), 'R$ ' . number_format($totals['total_debit'], 2, ',', '.'), 'R$ ' . number_format($totals['final_balance'], 2, ',', '.')];

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
}
