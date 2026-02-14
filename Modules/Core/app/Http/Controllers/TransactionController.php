<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Requests\StoreTransactionRequest;
use Modules\Core\Http\Requests\UpdateTransactionRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Budget;
use Modules\Core\Models\Category;
use Modules\Core\Models\Transaction;
use Modules\Core\Services\SubscriptionLimitService;
use Modules\Core\Services\TransferService;

class TransactionController extends Controller
{
    protected TransferService $transferService;

    protected SubscriptionLimitService $limitService;

    public function __construct(TransferService $transferService, SubscriptionLimitService $limitService)
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:core.view')->only(['index', 'show']);
        $this->middleware('permission:core.create')->only(['create', 'store', 'transfer', 'processTransfer']);
        $this->transferService = $transferService;
        $this->limitService = $limitService;
    }

    public function index(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id())
            ->with(['account', 'category']);

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('min_amount') && is_numeric($request->min_amount)) {
            $query->where('amount', '>=', (float) $request->min_amount);
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(20);

        // Apenas baseline (Planejamento) para o widget de comparação
        $recurringTransactions = \Modules\Core\Models\RecurringTransaction::where('user_id', Auth::id())
            ->where('is_baseline', true)
            ->active()
            ->get();

        return view('core::transactions.index', compact('transactions', 'recurringTransactions'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Transaction::class);

        $accounts = Account::where('user_id', Auth::id())->get();
        $categories = Category::forUser(Auth::user())->get();
        $type = $request->query('type', 'expense');
        $recurringTransactions = \Modules\Core\Models\RecurringTransaction::where('user_id', Auth::id())
            ->where('is_baseline', true)
            ->active()
            ->get();

        return view('core::transactions.create', compact('accounts', 'categories', 'type', 'recurringTransactions'));
    }

    public function store(StoreTransactionRequest $request)
    {
        // Check limit
        if (! $this->limitService->canCreate(Auth::user(), $request->type)) {
            return view('core::limits.reached-transaction', ['type' => $request->type]);
        }

        $this->authorize('create', Transaction::class);

        if ($request->type === 'expense' && $request->status === 'completed') {
            $blocking = Budget::getBlockingBudgetIfExceeded(
                (int) Auth::id(),
                (int) $request->category_id,
                (float) $request->amount,
                $request->date
            );
            if ($blocking !== null) {
                $categoryName = $blocking->category?->name ?? 'Categoria';
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'amount' => "O orçamento da categoria \"{$categoryName}\" não permite ultrapassar o limite (bloqueio de excessos ativo).",
                    ]);
            }
        }

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'account_id' => $request->account_id,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        // PRO: Create automated recurrence if requested
        if (Auth::user()->isPro() && $request->boolean('is_recurring')) {
            $day = (int) date('d', strtotime($request->date));
            $nextDate = \Carbon\Carbon::parse($request->date)->addMonth();

            \Modules\Core\Models\RecurringTransaction::create([
                'user_id' => Auth::id(),
                'account_id' => $request->account_id,
                'category_id' => $request->category_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'frequency' => 'monthly',
                'recurrence_day' => $day,
                'next_date' => $nextDate,
                'description' => $request->description,
                'is_active' => true,
                'is_baseline' => false,
            ]);
        }

        return redirect()->route('core.transactions.index')
            ->with('success', $request->boolean('is_recurring')
                ? 'Transação registrada e agendamento recorrente criado!'
                : 'Transação criada com sucesso!');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $accounts = Account::where('user_id', Auth::id())->get();

        // Fetch categories + ensure current category is included (handling expired Pro case)
        $categories = Category::forUser(Auth::user())
            ->orWhere('id', $transaction->category_id)
            ->get();
        $recurringTransactions = \Modules\Core\Models\RecurringTransaction::where('user_id', Auth::id())
            ->where('is_baseline', true)
            ->active()
            ->get();

        return view('core::transactions.edit', compact('transaction', 'accounts', 'categories', 'recurringTransactions'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        if ($request->type === 'expense' && $request->status === 'completed') {
            $blocking = Budget::getBlockingBudgetIfExceeded(
                (int) Auth::id(),
                (int) $request->category_id,
                (float) $request->amount,
                $request->date,
                $transaction->id
            );
            if ($blocking !== null) {
                $categoryName = $blocking->category?->name ?? 'Categoria';
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'amount' => "O orçamento da categoria \"{$categoryName}\" não permite ultrapassar o limite (bloqueio de excessos ativo).",
                    ]);
            }
        }

        $transaction->update([
            'account_id' => $request->account_id,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('core.transactions.index')
            ->with('success', 'Transação atualizada com sucesso!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('core.transactions.index')
            ->with('success', 'Transação excluída com sucesso!');
    }

    /**
     * Show transfer form.
     */
    public function transfer()
    {
        $accounts = Account::where('user_id', Auth::id())->get();
        $categories = Category::where('user_id', Auth::id())->get();

        return view('core::transactions.transfer', compact('accounts', 'categories'));
    }

    /**
     * Process transfer between accounts.
     */
    public function processTransfer(Request $request)
    {
        $request->validate([
            'from_account_id' => ['required', 'exists:accounts,id'],
            'to_account_id' => ['required', 'exists:accounts,id', 'different:from_account_id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['nullable', 'string', 'max:500'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ], [
            'from_account_id.required' => 'Selecione a conta de origem.',
            'to_account_id.required' => 'Selecione a conta de destino.',
            'to_account_id.different' => 'As contas devem ser diferentes.',
            'amount.required' => 'Informe o valor.',
            'amount.min' => 'O valor deve ser maior que zero.',
        ]);

        try {
            $fromAccount = Account::findOrFail($request->from_account_id);
            $toAccount = Account::findOrFail($request->to_account_id);

            $this->transferService->transfer(
                $fromAccount,
                $toAccount,
                $request->amount,
                $request->description,
                $request->category_id
            );

            return redirect()->route('core.transactions.index')
                ->with('success', 'Transferência realizada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
