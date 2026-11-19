<?php

namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;

class BasicContent extends Model
{
    protected $fillable=[
        'name',
        'type',
        'content'
    ];
}
