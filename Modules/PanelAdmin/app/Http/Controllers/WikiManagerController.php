<?php

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Models\WikiCategory;
use Modules\Core\Models\WikiArticle;
use Modules\Core\Models\WikiSuggestion;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class WikiManagerController extends Controller
{
    // Suggestions
    public function suggestions()
    {
        $suggestions = WikiSuggestion::with('user')->latest()->paginate(20);
        return view('paneladmin::wiki.suggestions.index', compact('suggestions'));
    }

    public function updateSuggestionStatus(Request $request, WikiSuggestion $suggestion)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,implementing,completed,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $suggestion->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Status da sugestão atualizado!');
    }

    public function destroySuggestion(WikiSuggestion $suggestion)
    {
        $suggestion->delete();
        return back()->with('success', 'Sugestão removida!');
    }

    // Categories
    public function categories()
    {
        $categories = WikiCategory::withCount('articles')->orderBy('order')->get();
        return view('paneladmin::wiki.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        WikiCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? 'book',
            'description' => $request->description,
            'order' => WikiCategory::count() + 1,
        ]);

        return back()->with('success', 'Categoria criada com sucesso!');
    }

    public function updateCategory(Request $request, WikiCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? 'book',
            'description' => $request->description,
        ]);

        return back()->with('success', 'Categoria atualizada!');
    }

    public function destroyCategory(WikiCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Categoria removida!');
    }

    // Articles
    public function articles()
    {
        $articles = WikiArticle::with(['category', 'author'])->latest()->paginate(15);
        $categories = WikiCategory::orderBy('name')->get();
        return view('paneladmin::wiki.articles.index', compact('articles', 'categories'));
    }

    public function createArticle()
    {
        $categories = WikiCategory::orderBy('name')->get();
        return view('paneladmin::wiki.articles.create', compact('categories'));
    }

    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:wiki_categories,id',
            'content' => 'required|string',
        ]);

        WikiArticle::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'category_id' => $request->category_id,
            'content' => $request->content,
            'is_published' => $request->has('is_published'),
            'author_id' => Auth::id(),
        ]);

        return redirect()->route('admin.wiki.articles')->with('success', 'Artigo criado com sucesso!');
    }

    public function editArticle(WikiArticle $article)
    {
        $categories = WikiCategory::orderBy('name')->get();
        return view('paneladmin::wiki.articles.edit', compact('article', 'categories'));
    }

    public function updateArticle(Request $request, WikiArticle $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:wiki_categories,id',
            'content' => 'required|string',
        ]);

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . substr($article->slug, -5),
            'category_id' => $request->category_id,
            'content' => $request->content,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.wiki.articles')->with('success', 'Artigo atualizado!');
    }

    public function destroyArticle(WikiArticle $article)
    {
        $article->delete();
        return back()->with('success', 'Artigo removido!');
    }
}
