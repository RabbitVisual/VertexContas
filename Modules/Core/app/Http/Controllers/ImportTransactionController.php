<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportAuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Category;
use Modules\Core\Models\Transaction;
use Modules\Core\Services\GeminiService;

class ImportTransactionController extends Controller
{
    public function __construct(
        protected GeminiService $geminiService
    ) {
        $this->middleware(['auth', 'verified']);
        $this->middleware('pro');
    }

    /**
     * Upload CSV: parse and store rows in session, return headers + count for column mapping.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $rows = $this->parseCsv($path);
        if (empty($rows)) {
            throw ValidationException::withMessages(['file' => 'O arquivo CSV está vazio ou não pôde ser lido.']);
        }

        $headers = array_map('trim', (array) $rows[0]);
        $dataRows = array_slice($rows, 1);
        $dataRows = array_values(array_filter($dataRows, fn ($row) => ! empty(array_filter($row))));

        Session::put('import_csv_headers', $headers);
        Session::put('import_csv_rows', $dataRows);

        return response()->json([
            'headers' => $headers,
            'count' => count($dataRows),
        ]);
    }

    /**
     * Categorize: use mapping to normalize rows, call Gemini, return rows for review.
     */
    public function categorize(Request $request): JsonResponse
    {
        $request->validate([
            'date_col' => ['required', 'string'],
            'desc_col' => ['required', 'string'],
            'amount_col' => ['required', 'string'],
        ]);

        $headers = Session::get('import_csv_headers', []);
        $rawRows = Session::get('import_csv_rows', []);
        if (empty($headers) || empty($rawRows)) {
            throw ValidationException::withMessages(['session' => 'Sessão de importação expirada. Envie o CSV novamente.']);
        }

        $dateCol = $request->input('date_col');
        $descCol = $request->input('desc_col');
        $amountCol = $request->input('amount_col');
        $keyMap = array_flip($headers);

        $entries = [];
        foreach ($rawRows as $i => $row) {
            $row = array_values($row);
            $dateVal = $row[$keyMap[$dateCol] ?? 0] ?? '';
            $descVal = $row[$keyMap[$descCol] ?? 0] ?? '';
            $amountVal = $row[$keyMap[$amountCol] ?? 0] ?? '';
            $amountVal = $this->normalizeAmount($amountVal);
            if ($descVal === '' && $amountVal === 0) {
                continue;
            }
            $entries[] = [
                'index' => $i,
                'date_raw' => trim((string) $dateVal),
                'description' => trim((string) $descVal),
                'amount' => $amountVal,
            ];
        }

        $user = Auth::user();
        $categories = Category::forUser($user)->get(['id', 'name', 'type', 'type_group']);
        $categorizations = $this->geminiService->categorizeStatementEntries($entries, $categories->toArray());

        $rowsForReview = [];
        foreach ($entries as $entry) {
            $cat = $categorizations[$entry['index']] ?? null;
            $categoryId = $cat['category_id'] ?? null;
            $pillar = $cat['pillar'] ?? null;
            $type = $entry['amount'] >= 0 ? 'income' : 'expense';
            $category = $categories->firstWhere('id', $categoryId);
            if (! $category && $categoryId) {
                $category = $categories->first();
            }
            if (! $category) {
                $category = Category::forUser($user)->where('type', $type)->first();
            }
            $date = $this->parseDate($entry['date_raw']);
            $rowsForReview[] = [
                'index' => $entry['index'],
                'date' => $date?->format('Y-m-d') ?? now()->format('Y-m-d'),
                'description' => $entry['description'],
                'amount' => $entry['amount'],
                'type' => $type,
                'category_id' => $category?->id ?? $cat['category_id'],
                'category_name' => $category?->name ?? 'Outros',
                'pillar' => $pillar,
            ];
        }

        Session::put('import_review_rows', $rowsForReview);

        return response()->json(['rows' => $rowsForReview]);
    }

    /**
     * Persist import: anti-duplication, batch insert, audit log.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'account_id' => ['required', 'integer', 'exists:accounts,id'],
            'rows' => ['required', 'array'],
            'rows.*.date' => ['required', 'date'],
            'rows.*.description' => ['nullable', 'string', 'max:500'],
            'rows.*.amount' => ['required', 'numeric'],
            'rows.*.type' => ['required', 'in:income,expense'],
            'rows.*.category_id' => ['required', 'integer', 'exists:categories,id'],
        ]);

        $account = Account::where('user_id', Auth::id())->findOrFail($request->input('account_id'));
        $rows = $request->input('rows');
        $userId = Auth::id();

        $toInsert = [];
        $duplicates = 0;
        foreach ($rows as $row) {
            $amount = (float) $row['amount'];
            $date = $row['date'];
            $exists = Transaction::where('user_id', $userId)
                ->where('account_id', $account->id)
                ->where('date', $date)
                ->where('amount', $amount)
                ->exists();
            if ($exists) {
                $duplicates++;
                continue;
            }
            $toInsert[] = [
                'user_id' => $userId,
                'account_id' => $account->id,
                'category_id' => (int) $row['category_id'],
                'type' => $row['type'],
                'amount' => $amount,
                'date' => $date,
                'description' => $row['description'] ?? '',
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (! empty($toInsert)) {
            Transaction::insert($toInsert);
        }

        Session::forget(['import_csv_headers', 'import_csv_rows', 'import_review_rows']);

        SupportAuditLog::create([
            'user_id' => $userId,
            'action' => 'user.transactions.imported',
            'metadata' => [
                'imported' => count($toInsert),
                'duplicates_skipped' => $duplicates,
                'account_id' => $account->id,
            ],
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'imported' => count($toInsert),
            'duplicates_skipped' => $duplicates,
            'message' => count($toInsert) > 0
                ? sprintf('%d transação(ões) importada(s). %d duplicada(s) ignorada(s).', count($toInsert), $duplicates)
                : 'Nenhuma transação nova (todas duplicadas ou lista vazia).',
        ]);
    }

    protected function parseCsv(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [];
        }
        while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        return $rows;
    }

    protected function normalizeAmount(string $value): float
    {
        $value = trim(str_replace(['.', 'R$', ' '], '', $value));
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }

    protected function parseDate(string $value): ?\Carbon\Carbon
    {
        if ($value === '') {
            return null;
        }
        $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'd/m/y', 'm/d/Y'];
        foreach ($formats as $fmt) {
            try {
                $d = \Carbon\Carbon::createFromFormat($fmt, trim($value));

                return $d;
            } catch (\Throwable $e) {
                continue;
            }
        }
        try {
            return \Carbon\Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
