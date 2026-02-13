<?php

namespace Modules\PanelUser\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'path'];

    /**
     * Get the user that owns the photo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the photo URL.
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
