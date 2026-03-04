<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface HasUser
{
    public function user(): BelongsTo;
}
