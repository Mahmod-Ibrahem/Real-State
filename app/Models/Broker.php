<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    protected $fillable = [
        'name',
        'image',
        'job_title',
        'facebook',
        'instagram',
        'twitter'
        ];
}
