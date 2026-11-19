<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FeatureType extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable = [
        'name',
    ];

    public function features()
    {
        return $this->hasMany(Feature::class);
    }
}
