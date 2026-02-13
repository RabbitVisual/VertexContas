<?php

/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA © 2026
 * Email: r.rodriguesjs@gmail.com
 */

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\Comment;
use Modules\Blog\Models\PostLike;
use Modules\Blog\Models\SavedPost;
use Modules\Notifications\Services\NotificationService;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::where('status', 'published')->with('author', 'category')->orderBy('created_at', 'desc')->paginate(12);
        return view('blog::index', compact('posts'));
    }

    /**
     * Show the specified resource.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->where('status', 'published')->with('author', 'category', 'comments.user')->firstOrFail();

        // Increment views
        $post->increment('views');

        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->take(3)
            ->get();

        return view('blog::show', compact('post', 'relatedPosts'));
    }

    /**
     * Store a comment.
     */
    public function storeComment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post = Post::findOrFail($postId);

        // Check settings for guest comments (if not auth) - but middleware usually requires auth
        // Assuming this route is protected by auth

        // Check auto-approve setting
        $settingService = app(\Modules\Core\Services\SettingService::class);
        $autoApprove = $settingService->get('auto_approve_comments', false, 'blog');

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'is_approved' => $autoApprove,
        ]);

        if ($autoApprove) {
            return response()->json(['success' => true, 'message' => 'Comentário publicado!', 'comment' => $comment->load('user')]);
        }

        return response()->json(['success' => true, 'message' => 'Comentário enviado para moderação.']);
    }

    /**
     * Toggle Like.
     */
    public function toggleLike($postId)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        $like = PostLike::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        return response()->json(['success' => true, 'liked' => $liked, 'count' => $post->likes()->count()]);
    }

    /**
     * Toggle Save.
     */
    public function toggleSave($postId)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();

        $saved = SavedPost::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if ($saved) {
            $saved->delete();
            $isSaved = false;
        } else {
            SavedPost::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $isSaved = true;
        }

        return response()->json(['success' => true, 'saved' => $isSaved]);
    }
}
