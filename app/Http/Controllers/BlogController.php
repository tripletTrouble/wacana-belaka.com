<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('blogs/Index', [
            'posts' => fn() => Post::select(['id', 'user_id', 'post_category_id', 'title', 'slug', 'published_at', 'excerpt'])
                ->with(
                    'category:id,name',
                    'user:id,name',
                    'media'
                )
                ->whereNotNull('published_at')
                ->latest('published_at')
                ->paginate(10)
        ]);
    }
}
