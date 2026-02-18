<?php

namespace App\Models;

use App\Contracts\HasUser;
use App\Traits\InteractsWithUser;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasUser, HasMedia
{
    use InteractsWithUser, InteractsWithMedia;

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
