<?php

namespace App\Models;

use App\Contracts\HasUser;
use App\Traits\InteractsWithUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as MediaModel;

class Post extends Model implements HasUser, HasMedia
{
    use InteractsWithUser, InteractsWithMedia, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function casts()
    {
        return [
            'content' => 'array',
            'tags' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    /**
     * Accessor for the featured image media item.
     *
     * Returns the first media in the `featured_image` collection when available.
     */
    public function getFeaturedImageAttribute(): ?MediaModel
    {
        // If loaded, return the first media item in the 'featured_image' collection, for better accesibility
        if ($this->relationLoaded('media')) {
            return $this->media->firstWhere('collection_name', 'featured_image') ?? null;
        }

        // Media is not needed, so return null instead.
        return null;
    }
}
