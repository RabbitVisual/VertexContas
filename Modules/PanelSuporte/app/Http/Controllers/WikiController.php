<?php

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\WikiArticle;
use Modules\Core\Models\WikiCategory;
use Modules\Core\Models\WikiSuggestion;

class WikiController extends Controller
{
    public function storeSuggestion(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        WikiSuggestion::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Sua sugestão foi enviada com sucesso! Obrigado por colaborar.');
    }

    public function index(Request $request)
    {
        $query = WikiArticle::with('category')->where('is_published', true);

        if ($request->has('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $articles = $query->latest()->paginate(12);
        $categories = WikiCategory::withCount(['articles' => function ($q) {
            $q->where('is_published', true);
        }])->orderBy('order')->get();

        return view('panelsuporte::wiki.index', compact('articles', 'categories'));
    }

    public function show(WikiArticle $article)
    {
        if (! $article->is_published) {
            abort(404);
        }

        $article->increment('views');
        $related = WikiArticle::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('is_published', true)
            ->limit(5)
            ->get();

        return view('panelsuporte::wiki.show', compact('article', 'related'));
    }
}
