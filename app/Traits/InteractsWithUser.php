<?php

namespace App\Traits;

use App\Models\User;
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
}
