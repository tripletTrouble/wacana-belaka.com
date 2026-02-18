<?php

namespace App\Traits;

use App\Models\GameSubmission;

/**
 * @mixin(\Illuminate\Database\Eloquent\Model)
 */
trait InteractsWithGame
{
    public function submissions()
    {
        return $this->hasMany(GameSubmission::class);
    }
}
