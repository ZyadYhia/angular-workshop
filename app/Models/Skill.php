<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Skill extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public array $translatable = ['name'];

    public function cat()
    {
        return $this->belongsTo(Cat::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function getStudentsCount()
    {
        $studentNum = 0;
        foreach ($this->exams as $exam) {
            $studentNum += $exam->users()->count();
        }

        return $studentNum;
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
