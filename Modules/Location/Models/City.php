<?php

namespace Modules\Location\Models;

use Modules\Location\Database\Factories\CityFactory;
use Modules\Property\Models\Property;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasTranslations , Sluggable , HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'country_id',
    ];
    public $translatable = ['name', 'slug'];

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function areas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Area::class);
    }

    public function properties(){
        return $this->hasMany(Property::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }

    // Factories outside Database\Factories aren't found by convention (ARCHITECTURE.md, Adding a module)
    protected static function newFactory(): CityFactory
    {
        return CityFactory::new();
    }
}
