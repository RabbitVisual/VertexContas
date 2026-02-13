<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'slug',
        'content',
        'featured_image',
        'status',
        'is_premium',
        'views'
    ];

    protected $casts = [
        'is_premium' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true)->whereNull('parent_id');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function savedBy()
    {
        return $this->hasMany(SavedPost::class);
    }

    // Helper for checking if a user liked/saved
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isSavedBy($user)
    {
        if (!$user) return false;
        return $this->savedBy()->where('user_id', $user->id)->exists();
    }
}
