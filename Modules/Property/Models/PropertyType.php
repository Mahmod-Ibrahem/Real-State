<?php

namespace Modules\Property\Models;

use Modules\Property\Database\Factories\PropertyTypeFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PropertyType extends Model
{
    use HasTranslations, sluggable , HasFactory;

    public $translatable = ['slug', 'name'];

    protected $fillable = ['name', 'image', 'slug'];

    public function properties()
    {
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

    // Factories outside Database\Factories aren't found by convention (CLAUDE.md, Modules)
    protected static function newFactory(): PropertyTypeFactory
    {
        return PropertyTypeFactory::new();
    }
}
