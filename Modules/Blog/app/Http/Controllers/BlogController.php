<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\BlogCategory;
use Modules\Blog\Models\Comment;
use Modules\Blog\Models\CommentLike;
use Modules\Blog\Models\PostLike;
use Modules\Blog\Models\SavedPost;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::where('status', 'published')->with('author', 'category');

        if ($request->has('category') && $request->category) {
            $category = BlogCategory::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = BlogCategory::all();

        return view('blog::index', compact('posts', 'categories'));
    }

    /**
     * Show the specified resource.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->where('status', 'published')->with(['author', 'category', 'approvedComments.user'])->firstOrFail();

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
     * Toggle Like on Post.
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
     * Toggle Like on Comment.
     */
    public function toggleCommentLike($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $user = auth()->user();

        $like = CommentLike::where('comment_id', $comment->id)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            CommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        return response()->json(['success' => true, 'liked' => $liked, 'count' => $comment->likes()->count()]);
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

    /**
     * Track Conversion (Click on Upgrade).
     */
    public function trackConversion($postId)
    {
        $post = Post::findOrFail($postId);
        $post->increment('conversion_clicks');
        return response()->json(['success' => true]);
    }
}
