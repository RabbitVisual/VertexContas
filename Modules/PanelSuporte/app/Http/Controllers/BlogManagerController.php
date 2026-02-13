<?php

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\BlogCategory;
use Modules\Blog\Models\Comment;
use Illuminate\Support\Str;
use Modules\Notifications\Services\NotificationService;

class BlogManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('author', 'category')->orderBy('created_at', 'desc')->paginate(10);
        return view('panelsuporte::blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::all();
        return view('panelsuporte::blog.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required',
            'status' => 'required|in:draft,pending_review,published',
            'is_premium' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_description' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notify_users' => 'boolean',
        ]);

        $slug = Str::slug($validated['title']);
        $count = Post::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog', 'public');
            $imagePath = 'storage/' . $imagePath;
        }

        $ogImagePath = null;
        if ($request->hasFile('og_image')) {
            $ogImagePath = $request->file('og_image')->store('blog/seo', 'public');
            $ogImagePath = 'storage/' . $ogImagePath;
        }

        $post = Post::create([
            'author_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'status' => $validated['status'],
            'is_premium' => $request->boolean('is_premium'),
            'featured_image' => $imagePath,
            'meta_description' => $validated['meta_description'],
            'og_image' => $ogImagePath,
        ]);

        // Send notification if requested and published
        if ($post->status === 'published' && $request->boolean('notify_users')) {
            app(NotificationService::class)->sendSystemWide(
                'Novo Artigo: ' . $post->title,
                'Confira nosso novo artigo financeiro!',
                'info',
                route('blog.show', $post->slug)
            );
        }

        // Check for Urgent Financial Alert
        $category = BlogCategory::find($validated['category_id']);
        if ($post->status === 'published' && $category && Str::slug($category->name) === 'alerta-financeiro') {
             app(NotificationService::class)->sendSystemWide(
                'ALERTA FINANCEIRO URGENTE',
                'Atenção! ' . $post->title,
                'danger', // Or warning, using danger for urgency
                route('blog.show', $post->slug),
                'bolt-lightning' // Icon name
            );
        }

        return redirect()->route('suporte.blog.index')->with('success', 'Post criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = BlogCategory::all();
        return view('panelsuporte::blog.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required',
            'status' => 'required|in:draft,pending_review,published',
            'is_premium' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_description' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->title !== $post->title) {
            $slug = Str::slug($validated['title']);
            $count = Post::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $id)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
            $post->slug = $slug;
        }

        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog', 'public');
            $post->featured_image = 'storage/' . $imagePath;
        }

        if ($request->hasFile('og_image')) {
            $ogImagePath = $request->file('og_image')->store('blog/seo', 'public');
            $post->og_image = 'storage/' . $ogImagePath;
        }

        $post->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'is_premium' => $request->boolean('is_premium'),
            'meta_description' => $validated['meta_description'],
            // 'og_image' is handled above if present, otherwise keeps old one if not updated via update() without assignment
        ]);

        // Explicitly saving if not handled by update array for file paths
        $post->save();

        return redirect()->route('suporte.blog.index')->with('success', 'Post atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('suporte.blog.index')->with('success', 'Post removido com sucesso!');
    }

    /**
     * List pending comments.
     */
    public function comments()
    {
        $comments = Comment::where('is_approved', false)->with('post', 'user')->orderBy('created_at', 'desc')->paginate(20);
        return view('panelsuporte::blog.comments', compact('comments'));
    }

    /**
     * Approve a comment.
     */
    public function approveComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['is_approved' => true]);

        // Notify user
        app(NotificationService::class)->sendToUser(
            $comment->user,
            'Comentário Aprovado',
            'Seu comentário no post "' . $comment->post->title . '" foi aprovado!',
            'success',
            route('blog.show', $comment->post->slug)
        );

        return back()->with('success', 'Comentário aprovado!');
    }

    /**
     * Reject a comment.
     */
    public function rejectComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return back()->with('success', 'Comentário rejeitado e removido.');
    }
}
