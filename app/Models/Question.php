<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Question extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public array $translatable = ['title', 'option_1', 'option_2', 'option_3', 'option_4'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
