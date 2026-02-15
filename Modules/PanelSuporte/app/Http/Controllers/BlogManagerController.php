<?php

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\BlogCategory;
use Modules\Blog\Models\Comment;
use Modules\Blog\Services\BlogService;
use Modules\Notifications\Services\NotificationService;

class BlogManagerController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
        protected BlogService $blogService
    ) {}
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
            'meta_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notify_users' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'is_premium' => $request->boolean('is_premium'),
            'meta_description' => $validated['meta_description'] ?? null,
        ];
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image');
        }
        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image');
        }

        $post = $this->blogService->createPost($data, (int) auth()->id());

        if ($post->status === 'published' && $request->boolean('notify_users')) {
            $this->notificationService->sendSystemWide(
                'Novo Artigo: ' . $post->title,
                'Confira nosso novo artigo financeiro!',
                'info',
                route('paneluser.blog.show', $post->slug)
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
            'meta_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'is_premium' => $request->boolean('is_premium'),
            'meta_description' => $validated['meta_description'] ?? null,
        ];
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image');
        }
        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image');
        }

        $this->blogService->updatePost($post, $data);

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

        $this->notificationService->sendToUser(
            $comment->user,
            'Comentário Aprovado',
            'Seu comentário no post "' . $comment->post->title . '" foi aprovado!',
            'success',
            route('paneluser.blog.show', $comment->post->slug)
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
