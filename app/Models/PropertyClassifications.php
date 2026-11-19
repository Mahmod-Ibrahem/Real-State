<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PropertyClassifications extends Model
{
    use HasTranslations , HasFactory;

    protected $table = 'property_classifications';

    public $translatable = ['name'];

    protected $fillable = [
        'name',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class, 'property_classification_id');
    }
}
