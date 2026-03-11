<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin(\Illuminate\Database\Eloquent\Model)
 */
trait InteractsWithUser
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[Scope]
    public function currentUser($query)
    {
        if (auth()->check()) {
            $query->where('user_id', auth()->user()->id);
        }
    }
}
