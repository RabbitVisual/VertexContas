<?php

declare(strict_types=1);

namespace Modules\Blog\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\BlogCategory;
use Modules\Blog\Models\Comment;
use Modules\Blog\Models\PostLike;
use Modules\Blog\Models\SavedPost;
use Modules\Core\Services\SettingService;

class BlogService
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    /**
     * Get published posts with optional search and category filter.
     */
    public function getPublishedPosts(array $filters = []): LengthAwarePaginator
    {
        $query = Post::where('status', 'published')
            ->with('author', 'category')
            ->orderBy('created_at', 'desc');

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($qry) use ($q) {
                $qry->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->paginate($filters['per_page'] ?? 12);
    }

    /**
     * Get a published post by slug with relations.
     */
    public function getPostBySlug(string $slug): ?Post
    {
        return Post::where('slug', $slug)
            ->where('status', 'published')
            ->with('author', 'category', 'approvedComments.user')
            ->first();
    }

    /**
     * Generate unique slug from title.
     */
    public function generateSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $query = Post::where('slug', 'like', $slug . '%');
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }
        $count = $query->count();
        return $count > 0 ? $slug . '-' . ($count + 1) : $slug;
    }

    /**
     * Create a new post.
     */
    public function createPost(array $data, int $authorId): Post
    {
        $slug = $data['slug'] ?? $this->generateSlug($data['title']);

        $featuredImage = null;
        if (!empty($data['featured_image'])) {
            $featuredImage = $this->storeUploadedFile($data['featured_image'], 'blog');
        }

        $ogImage = null;
        if (!empty($data['og_image'])) {
            $ogImage = $this->storeUploadedFile($data['og_image'], 'blog/seo');
        }

        return Post::create([
            'author_id' => $authorId,
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'],
            'status' => $data['status'] ?? 'draft',
            'is_premium' => (bool) ($data['is_premium'] ?? false),
            'featured_image' => $featuredImage,
            'meta_description' => $data['meta_description'] ?? null,
            'og_image' => $ogImage,
        ]);
    }

    /**
     * Update an existing post.
     */
    public function updatePost(Post $post, array $data): Post
    {
        if (isset($data['title']) && $data['title'] !== $post->title) {
            $data['slug'] = $data['slug'] ?? $this->generateSlug($data['title'], $post->id);
        }

        if (!empty($data['featured_image'])) {
            $data['featured_image'] = $this->storeUploadedFile($data['featured_image'], 'blog');
        }

        if (!empty($data['og_image'])) {
            $data['og_image'] = $this->storeUploadedFile($data['og_image'], 'blog/seo');
        }

        $fillable = ['title', 'slug', 'category_id', 'content', 'status', 'is_premium', 'meta_description', 'featured_image', 'og_image'];
        $updates = array_intersect_key($data, array_flip($fillable));
        if (isset($updates['is_premium'])) {
            $updates['is_premium'] = (bool) $updates['is_premium'];
        }

        $post->update($updates);
        return $post;
    }

    /**
     * Store a comment; respects auto_approve_comments setting.
     */
    public function storeComment(Post $post, array $data, int $userId): Comment
    {
        $autoApprove = (bool) $this->settingService->get('blog.auto_approve_comments', false);

        return Comment::create([
            'post_id' => $post->id,
            'user_id' => $userId,
            'content' => $data['content'],
            'is_approved' => $autoApprove,
        ]);
    }

    /**
     * Toggle like for a post. Returns ['liked' => bool, 'count' => int].
     */
    public function toggleLike(Post $post, User $user): array
    {
        $like = PostLike::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return ['liked' => false, 'count' => $post->likes()->count()];
        }

        PostLike::create(['post_id' => $post->id, 'user_id' => $user->id]);
        return ['liked' => true, 'count' => $post->likes()->count()];
    }

    /**
     * Toggle save for a post. Returns ['saved' => bool].
     */
    public function toggleSave(Post $post, User $user): array
    {
        $saved = SavedPost::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if ($saved) {
            $saved->delete();
            return ['saved' => false];
        }

        SavedPost::create(['post_id' => $post->id, 'user_id' => $user->id]);
        return ['saved' => true];
    }

    /**
     * Create a category.
     */
    public function storeCategory(array $data): BlogCategory
    {
        return BlogCategory::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'icon' => $data['icon'] ?? null,
        ]);
    }

    /**
     * Update a category.
     */
    public function updateCategory(BlogCategory $category, array $data): BlogCategory
    {
        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'icon' => $data['icon'] ?? $category->icon,
        ]);
        return $category;
    }

    protected function storeUploadedFile($file, string $path): string
    {
        $stored = $file->store($path, 'public');
        return 'storage/' . $stored;
    }
}
