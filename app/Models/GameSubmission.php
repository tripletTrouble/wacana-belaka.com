<?php

namespace App\Models;

use App\Contracts\HasUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSubmission extends Model implements HasUser
{
    use \App\Traits\InteractsWithUser;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function payload(): BelongsTo
    {
        return $this->belongsTo(GamePayload::class);
    }
}
