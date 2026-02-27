<?php

namespace App\Traits;

use App\Models\Post;

trait InteractsWithPost
{
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function latestPosts($limit = 1)
    {
        return $this->posts()->latest()->limit($limit)->get();
    }
}
