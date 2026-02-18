<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSubmission extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function payload(): BelongsTo
    {
        return $this->belongsTo(GamePayload::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
