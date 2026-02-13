<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Http\Requests\StoreCategoryRequest;
use Modules\Core\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
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
        // Enforce Pro-only for custom categories
        if (! auth()->user()->isPro()) {
            return view('core::limits.reached-category');
        }

        return view('core::categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        // Enforce Pro-only for custom categories
        if (! auth()->user()->isPro()) {
            return view('core::limits.reached-category');
        }

        Category::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $request->icon ?? 'circle-dollar',
            'color' => $request->color ?? '#64748b',
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
