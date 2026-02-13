<?php

namespace Modules\Core\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Transaction;

class ReportService
{
    /**
     * Get cash flow data for the last N months.
     */
    public function getCashFlow(User $user, int $months = 6): Collection
    {
        $startDate = now()->subMonths($months)->startOfMonth();

        $transactions = Transaction::where('user_id', $user->id)
            // ->where('status', 'completed') // Include pending for now as users might not mark them
            ->where('date', '>=', $startDate)
            ->select(
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
     * Get category ranking by spending.
     */
    public function getCategoryRanking(User $user, Carbon $startDate, Carbon $endDate): Collection
    {
        return Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            // ->where('status', 'completed')
            ->whereBetween('date', [$startDate, $endDate])
            ->with('category')
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
     * Export data to CSV.
     */
    public function exportToCsv(Collection $data, string $filename): string
    {
        $csvPath = storage_path('app/exports/' . $filename . '.csv');

        // Ensure directory exists
        if (!file_exists(dirname($csvPath))) {
            mkdir(dirname($csvPath), 0755, true);
        }

        $file = fopen($csvPath, 'w');

        // Add BOM for UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Write headers
        if ($data->isNotEmpty()) {
            fputcsv($file, array_keys($data->first()), ';');

            // Write data
            foreach ($data as $row) {
                fputcsv($file, $row, ';');
            }
        }

        fclose($file);

        return $csvPath;
    }

    /**
     * Export data to PDF.
     * Note: Requires dompdf/dompdf package
     */
    public function exportToPdf(Collection $data, string $title, string $filename): string
    {
        // Check if DomPDF is available
        if (!class_exists(\Dompdf\Dompdf::class)) {
            throw new \Exception('DomPDF package is not installed. Run: composer require dompdf/dompdf');
        }

        $html = view('core::reports.pdf-template', [
            'title' => $title,
            'data' => $data,
            'generated_at' => now()->format('d/m/Y H:i'),
        ])->render();

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfPath = storage_path('app/exports/' . $filename . '.pdf');

        // Ensure directory exists
        if (!file_exists(dirname($pdfPath))) {
            mkdir(dirname($pdfPath), 0755, true);
        }

        file_put_contents($pdfPath, $dompdf->output());

        return $pdfPath;
    }

    /**
     * Get income vs expense summary for a period.
     */
    public function getIncomeExpenseSummary(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $income = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->where('status', 'completed')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $expense = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->where('status', 'completed')
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'savings_rate' => $income > 0 ? (($income - $expense) / $income) * 100 : 0,
        ];
    }
}
