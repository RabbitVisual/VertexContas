<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Http\Requests\StoreAccountRequest;
use Modules\Core\Http\Requests\UpdateAccountRequest;
use Modules\Core\Models\Account;

use Modules\Core\Services\SubscriptionLimitService;

class AccountController extends Controller
{
    protected SubscriptionLimitService $limitService;

    public function __construct(SubscriptionLimitService $limitService)
    {
        $this->limitService = $limitService;
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:core.view')->only(['index', 'show']);
        $this->middleware('permission:core.create')->only(['create', 'store']);
    }

    /**
     * Display a listing of accounts.
     */
    public function index()
    {
        $accounts = Account::where('user_id', auth()->id())
            ->withCount('transactions')
            ->get();

        $totalBalance = $accounts->sum('balance');

        return view('core::accounts.index', compact('accounts', 'totalBalance'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        // Check limit before authorization to show premium page instead of 403
        if (!$this->limitService->canCreate(auth()->user(), 'account')) {
            return view('core::limits.reached-account');
        }

        $this->authorize('create', Account::class);

        return view('core::accounts.create');
    }

    /**
     * Store a newly created account.
     */
    public function store(StoreAccountRequest $request)
    {
        // Check limit again on store
        if (!$this->limitService->canCreate(auth()->user(), 'account')) {
            return view('core::limits.reached-account');
        }

        $this->authorize('create', Account::class);

        $account = Account::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'type' => $request->type,
            'balance' => $request->balance,
        ]);

        return redirect()->route('core.accounts.index')
            ->with('success', 'Conta criada com sucesso!');
    }

    /**
     * Display the specified account.
     */
    public function show(Account $account)
    {
        $this->authorize('view', $account);

        $account->load(['transactions' => function ($query) {
            $query->orderBy('date', 'desc')->limit(10);
        }]);

        return view('core::accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit(Account $account)
    {
        $this->authorize('update', $account);

        return view('core::accounts.edit', compact('account'));
    }

    /**
     * Update the specified account.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $this->authorize('update', $account);

        $account->update([
            'name' => $request->name,
            'type' => $request->type,
            'balance' => $request->balance,
        ]);

        return redirect()->route('core.accounts.index')
            ->with('success', 'Conta atualizada com sucesso!');
    }

    /**
     * Remove the specified account.
     */
    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        // Check if account has transactions
        if ($account->transactions()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma conta com transações. Exclua as transações primeiro.');
        }

        $account->delete();

        return redirect()->route('core.accounts.index')
            ->with('success', 'Conta excluída com sucesso!');
    }
}
