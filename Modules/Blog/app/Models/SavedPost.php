<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class SavedPost extends Model
{
    protected $fillable = ['post_id', 'user_id'];
    public $timestamps = true;
}
