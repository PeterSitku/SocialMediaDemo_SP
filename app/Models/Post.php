<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'content', 'original_post_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function reposts()
    {
        return $this->hasMany(Post::class, 'original_post_id');
    }

    public function originalPost()
    {
        return $this->belongsTo(Post::class, 'original_post_id');
    }
}
