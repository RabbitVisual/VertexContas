<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $fillable = ['comment_id', 'user_id'];
    public $timestamps = true;
}
