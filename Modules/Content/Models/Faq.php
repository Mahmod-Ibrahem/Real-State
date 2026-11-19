<?php

namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasTranslations;

    protected $table = 'faqs';
    protected $fillable = [
        'question',
        'answer',
    ];

    public $translatable = ['question', 'answer'];

}
