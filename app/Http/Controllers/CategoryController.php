<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('settings/PostCategories', [
            'categories' => fn () => PostCategory::orderBy('name')->paginate(10)->withQueryString(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191', 'unique:post_categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        // always generate slug in backend from the name
        $data['slug'] = Str::of(Arr::get($data, 'name'))->limit(255)->slug();

        PostCategory::create($data);

        Inertia::flash('success', 'Kategori dibuat.');

        return redirect()->route('settings.post-categories.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostCategory $postCategory)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191', 'unique:post_categories,name,' . data_get($postCategory, 'id')],
            'description' => ['nullable', 'string'],
        ]);

        // always generate/normalize slug from the name on update
        $data['slug'] = Str::of(Arr::get($data, 'name'))->limit(255)->slug();

        $postCategory->update($data);

        Inertia::flash('success', 'Kategori diperbarui.');

        return redirect()->route('settings.post-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostCategory $postCategory)
    {
        $postCategory->delete();

        Inertia::flash('success', 'Kategori dihapus.');

        return back();
    }
}
