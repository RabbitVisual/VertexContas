<?php

declare(strict_types=1);

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\Comment;
use Modules\Blog\Models\PostLike;
use Modules\Blog\Models\SavedPost;
use Modules\Blog\Models\BlogCategory;

class BlogController extends Controller
{
    /**
     * List published posts with optional search and category filter.
     */
    public function index(Request $request)
    {
        $query = Post::where('status', 'published')
            ->with('author', 'category')
            ->orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($qry) use ($q) {
                $qry->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $posts = $query->paginate(12)->withQueryString();
        $categories = BlogCategory::orderBy('name')->get();

        return view('paneluser::blog.index', compact('posts', 'categories'));
    }

    /**
     * Show a single post. When content is premium and user is not Pro, do NOT pass full content (security).
     */
    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with('author', 'category', 'approvedComments.user')
            ->firstOrFail();

        $post->increment('views');

        $user = auth()->user();
        $isLocked = $post->is_premium && ! $user?->isPro();

        if ($isLocked) {
            $postSnippet = Str::limit(strip_tags($post->content), 500);
            $post->content = $postSnippet;
        }

        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->take(3)
            ->get();

        return view('paneluser::blog.show', compact('post', 'isLocked', 'relatedPosts'));
    }

    /**
     * Store a comment (auth required).
     */
    public function storeComment(Request $request, Post $post)
    {
        $request->validate(['content' => 'required|string|max:1000']);

        $settingService = app(\Modules\Core\Services\SettingService::class);
        $autoApprove = (bool) $settingService->get('blog.auto_approve_comments', false);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
            'is_approved' => $autoApprove,
        ]);

        if ($autoApprove) {
            return response()->json([
                'success' => true,
                'message' => 'Comentário publicado!',
                'comment' => $comment->load('user'),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Comentário enviado para moderação.']);
    }

    /**
     * Toggle like (auth required).
     */
    public function toggleLike(Post $post)
    {
        $user = auth()->user();
        $like = PostLike::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            PostLike::create(['post_id' => $post->id, 'user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'count' => $post->likes()->count(),
        ]);
    }

    /**
     * Toggle save (auth required).
     */
    public function toggleSave(Post $post)
    {
        $user = auth()->user();
        $saved = SavedPost::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if ($saved) {
            $saved->delete();
            $isSaved = false;
        } else {
            SavedPost::create(['post_id' => $post->id, 'user_id' => $user->id]);
            $isSaved = true;
        }

        return response()->json(['success' => true, 'saved' => $isSaved]);
    }
}
