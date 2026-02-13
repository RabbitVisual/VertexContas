<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
