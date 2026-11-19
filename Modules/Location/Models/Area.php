<?php

namespace Modules\Location\Models;

use Modules\Location\Database\Factories\AreaFactory;
use Modules\Property\Models\Property;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Area extends Model
{
    use HasTranslations , Sluggable , HasFactory;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'city_id',
    ];

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
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

    // Factories outside Database\Factories aren't found by convention (CLAUDE.md, Modules)
    protected static function newFactory(): AreaFactory
    {
        return AreaFactory::new();
    }
}
