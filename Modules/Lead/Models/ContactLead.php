<?php

namespace Modules\Lead\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLead extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'subject',
        'phone',
        'message',
    ];
}
