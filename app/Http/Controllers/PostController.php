<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('posts/Index', [
            'posts' => fn() => Post::with('user')->paginate(10)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('posts/Form', [
            'categories' => fn() => PostCategory::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|array',
            'category_id' => 'nullable|exists:post_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'excerpt' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:1024',
        ]);

        $validated['content'] = (new \Tiptap\Editor)->sanitize($validated['content']);
        $validated['slug'] = \Str::of($validated['title'])->limit(219)->slug() . '-' . \Str::uuid()->toString();

        /**
         * @var \App\Models\User
         */
        $user = $request->user();

        $post = $user->posts()->create(Arr::except($validated, 'image'));

        if (Arr::get($validated, 'image')) {
            $post->addMedia($validated['image'])->toMediaCollection('featured_image');
        }

        Inertia::flash('success', 'Postingan berhasil dibuat.');

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return Inertia::render('posts/Show', [
            'post' => $post->load(['category', 'media', 'user'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $post->load(['category', 'media', 'user']);

        if (auth()->id() !== $post->user_id) {
            Inertia::flash('error', 'Anda tidak memiliki izin untuk mengedit postingan ini.');

            return back();
        }

        return Inertia::render('posts/Form', [
            'post' => $post,
            'categories' => fn() => PostCategory::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $post->load('user');

        // Authorize: only owner can update
        if ($request->user()->id !== $post->user->id) {
            Inertia::flash('error', 'Anda tidak memiliki izin untuk mengedit postingan ini.');

            return back();
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|array',
            'category_id' => 'nullable|exists:post_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'excerpt' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|max:1024',
            // legacy/form compatibility: accept `image` field as well
            'image' => 'nullable|image|max:1024',
            'remove_featured_image' => 'nullable|boolean',
        ]);

        // consult raw posted values if needed
        $posted = $request->post();

        $validated['content'] = (new \Tiptap\Editor)->sanitize($validated['content']);
        $validated['slug'] = \Str::of($validated['title'])->limit(219)->slug() . '-' . \Str::uuid()->toString();

        $post->update(Arr::except($validated, 'featured_image'));

        // If user requested to remove the featured image, clear it
        if (Arr::get($posted, 'remove_featured_image')) {
            $post->clearMediaCollection('featured_image');
        }

        // Handle newly uploaded image (support both `featured_image` and legacy `image` field)
        if ($request->hasFile('featured_image') || $request->hasFile('image')) {
            $uploaded = $request->file('featured_image') ?: $request->file('image');
            $post->clearMediaCollection('featured_image');
            $post->addMedia($uploaded)->toMediaCollection('featured_image');
        }

        Inertia::flash('success', 'Postingan berhasil diperbarui.');

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->load('user');
        // Authorize: only owner can delete
        $user = auth()->user();
        if (! $user || $user->id !== $post->user->id) {
            Inertia::flash('error', 'Anda tidak memiliki izin untuk menghapus postingan ini.');

            return back();
        }

        $post->delete();

        Inertia::flash('success', 'Postingan berhasil dihapus.');

        return back();
    }

    public function forceDelete(string $id)
    {
        $post = Post::onlyTrashed()->with('user')->findOrFail($id);

        // Authorize: only owner can permanently delete
        $user = auth()->user();
        if (! $user || $user->id !== $post->user->id) {
            Inertia::flash('error', 'Anda tidak memiliki izin untuk menghapus permanen postingan ini.');

            return back();
        }

        $post->forceDelete();

        return back()->with('success', 'Postingan berhasil dihapus permanen.');
    }

    public function archived()
    {
        return Inertia::render('posts/Archived', [
            'posts' => fn() => Post::onlyTrashed()->with('user')->paginate(10)->withQueryString()
        ]);
    }

    /**
     * Display a listing of all posts for administrators.
     */
    public function adminIndex(Request $request)
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole('admin')) {
            abort(403);
        }

        $query = Post::with(['user', 'category']);

        // Search by title
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where('title', 'ilike', "%{$q}%");
        }

        // Filter by publish status: published, unpublished, all
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'published') {
                $query->whereNotNull('published_at');
            } elseif ($status === 'unpublished') {
                $query->whereNull('published_at');
            }
        }

        return Inertia::render('admin/Posts', [
            'posts' => fn() => $query->paginate(10)->withQueryString(),
            'filters' => fn() => $request->only(['q', 'status']),
        ]);
    }

    public function restore(string $id)
    {
        $post = Post::onlyTrashed()->with('user')->findOrFail($id);

        // Authorize: only owner can restore
        $user = auth()->user();
        if (! $user || $user->id !== $post->user->id) {
            Inertia::flash('error', 'Anda tidak memiliki izin untuk memulihkan postingan ini.');

            return back();
        }

        $post->restore();

        Inertia::flash('success', 'Postingan berhasil dipulihkan.');

        return back();
    }

    public function togglePublish(Post $post)
    {
        $post->load('user');

        // Authorize: only owner can toggle publish status
        $user = auth()->user();
        if (! $user || $user->id !== $post->user->id) {
            Inertia::flash('error', 'Anda tidak memiliki izin untuk mengubah status publikasi postingan ini.');

            return back();
        }

        $post->is_published = ! $post->is_published;
        $post->save();

        Inertia::flash('success', 'Status publikasi postingan berhasil diubah.');

        return back();
    }
}
