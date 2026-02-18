<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function payloads(): HasMany
    {
        return $this->hasMany(GamePayload::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(GameSubmission::class);
    }
}
