<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Feature extends Model
{

    use HasTranslations, HasFactory;

    protected $table = 'features';

    protected $fillable = [
        'name',
        'feature_type_id',
    ];

    public $translatable = ['name'];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'feature_property');
    }

    public function featureType()
    {
        return $this->belongsTo(FeatureType::class);
    }
}
