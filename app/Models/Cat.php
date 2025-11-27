<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Cat extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public array $translatable = ['name'];

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
