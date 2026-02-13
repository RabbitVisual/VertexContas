<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikiCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'order',
    ];

    public function articles()
    {
        return $this->hasMany(WikiArticle::class, 'category_id')->orderBy('created_at', 'desc');
    }
}
