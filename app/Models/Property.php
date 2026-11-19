<?php

namespace App\Models;

use App\Enums\ListingPurpose;
use App\Enums\PropertyStatus;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];

    protected $attributes = [
        'status' => 'available',
    ];

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

    public function scopeActive($query)
    {
        return $query->where('is_published', 1);
    }


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
