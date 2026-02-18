<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Http\Requests\StoreCategoryRequest;
use Modules\Core\Models\Category;
use Modules\Core\Services\SubscriptionLimitService;

class CategoryController extends Controller
{
    public function __construct(
        protected SubscriptionLimitService $limitService
    ) {
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:core.view')->only(['index']);
        $this->middleware('permission:core.create')->only(['create', 'store']);
    }

    public function index()
    {
        $categories = Category::forUser(auth()->user())
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        return view('core::categories.index', compact('categories'));
    }

    public function create()
    {
        $user = auth()->user();
        if (! $this->limitService->canCreate($user, 'category')) {
            return redirect()->route('core.categories.index')
                ->with('error', $this->limitService->getLimitReachedMessage($user, 'category'));
        }

        return view('core::categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $user = auth()->user();
        if (! $this->limitService->canCreate($user, 'category')) {
            return redirect()->route('core.categories.index')
                ->with('error', $this->limitService->getLimitReachedMessage($user, 'category'));
        }

        $typeGroup = $request->type === 'expense' ? ($request->type_group ?? 'lifestyle') : null;
        $pillar = match ($typeGroup) {
            'essential' => 'essential',
            'lifestyle' => 'want',
            'financial' => 'savings',
            default => null,
        };

        Category::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $request->icon ?? 'circle-dollar',
            'color' => $request->color ?? '#64748b',
            'type_group' => $typeGroup,
            'pillar' => $pillar,
        ]);

        return redirect()->route('core.categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        // Check if category has transactions
        if ($category->transactions()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma categoria com transações.');
        }

        $category->delete();

        return redirect()->route('core.categories.index')
            ->with('success', 'Categoria excluída com sucesso!');
    }
}
