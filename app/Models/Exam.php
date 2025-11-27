<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Exam extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public array $translatable = ['name', 'desc'];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('score', 'time_mins', 'status')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
