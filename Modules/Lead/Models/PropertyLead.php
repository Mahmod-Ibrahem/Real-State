<?php

namespace Modules\Lead\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Lead\Database\Factories\PropertyLeadFactory;
use Modules\Lead\Enums\LeadSource;
use Modules\Lead\Enums\LeadStatus;
use Modules\Property\Models\Property;

class PropertyLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'owner_id',
        'full_name',
        'email',
        'phone',
        'message',
        'source',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'source' => LeadSource::class,
            'status' => LeadStatus::class,
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    protected static function newFactory(): PropertyLeadFactory
    {
        return PropertyLeadFactory::new();
    }
}
