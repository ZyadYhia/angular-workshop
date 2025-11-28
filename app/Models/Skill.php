<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;
use Spatie\Translatable\HasTranslations;

#[OA\Schema(
    schema: 'Skill',
    title: 'Skill',
    description: 'Skill model',
    required: ['id', 'name', 'cat_id'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'object', example: ['en' => 'Laravel', 'ar' => 'لارافيل']),
        new OA\Property(property: 'cat_id', type: 'integer', example: 1),
        new OA\Property(property: 'active', type: 'boolean', example: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
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
