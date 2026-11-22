<?php

namespace Modules\Property\Models;

use Modules\Property\Database\Factories\PropertyFactory;
use Modules\Location\Models\Country;
use Modules\Location\Models\Area;
use Modules\Location\Models\City;
use Modules\Property\Enums\ListingPurpose;
use Modules\Property\Enums\PropertyStatus;
use Modules\Property\Enums\ReviewStatus;
use App\Models\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Property extends Model
{
    use HasTranslations, HasFactory, Sluggable;

    protected $fillable = ['slug', 'name', 'description', 'address', 'image', 'price', 'bedrooms', 'bathrooms', 'garages', 'year_built', 'sqft',
        'google_map', 'is_published', 'property_type_id', 'area_id',
        'city_id', 'property_classification_id', 'purpose', 'status', 'floor', 'country_id'];
    public $translatable = ['slug', 'name', 'description', 'address','floor'];

    protected $casts = [
        'purpose' => ListingPurpose::class,
        'status' => PropertyStatus::class,
        'review_status' => ReviewStatus::class,
        'reviewed_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    protected $attributes = [
        'status' => 'available',
        'review_status' => 'pending',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isApproved(): bool
    {
        return $this->review_status === ReviewStatus::Approved;
    }

    public function propertyType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function propertyClassification()
    {
        return $this->belongsTo(PropertyClassifications::class);
    }

    public function area(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function features(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'feature_property');
    }

    public function propertyImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PropertyImages::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_published', true)->where('review_status', ReviewStatus::Approved);
    }

    public function scopeOwnedBy(Builder $query, User $owner): Builder
    {
        return $query->where('owner_id', $owner->id);
    }


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // Factories outside Database\Factories aren't found by convention (ARCHITECTURE.md, Adding a module)
    protected static function newFactory(): PropertyFactory
    {
        return PropertyFactory::new();
    }
}
