<?php

namespace Modules\Lead\Models;

use Modules\Property\Models\Property;
use Illuminate\Database\Eloquent\Model;

class PropertyLead extends Model
{
    protected $fillable = [
        'property_id',
        'full_name',
        'email',
        'phone',
        'message',
    ];

    public function property(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
