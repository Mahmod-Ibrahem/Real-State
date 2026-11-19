<?php

namespace Modules\Property\Models;

use Modules\Property\Database\Factories\FeatureTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FeatureType extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];
    protected $fillable = [
        'name',
    ];

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    // Factories outside Database\Factories aren't found by convention (CLAUDE.md, Modules)
    protected static function newFactory(): FeatureTypeFactory
    {
        return FeatureTypeFactory::new();
    }
}
