<?php

namespace Modules\Location\Models;

use Modules\Location\Database\Factories\CountryFactory;
use Modules\Property\Models\Property;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasTranslations, Sluggable, HasFactory;

    protected $table = 'countries';

    public $translatable = ['name'];

    protected $fillable = [
        'name'
    ];

    public function cities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(City::class);
    }

    public function properties(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Property::class);
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
    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }
}
