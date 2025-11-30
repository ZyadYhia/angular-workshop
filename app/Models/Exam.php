<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;
use Spatie\Translatable\HasTranslations;

#[OA\Schema(
    schema: 'Exam',
    title: 'Exam',
    description: 'Exam model',
    required: ['id', 'name', 'skill_id', 'time_mins', 'num_questions'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'object', example: ['en' => 'Laravel Basics', 'ar' => 'أساسيات لارافيل']),
        new OA\Property(property: 'desc', type: 'object', example: ['en' => 'Test your Laravel knowledge', 'ar' => 'اختبر معرفتك بلارافيل']),
        new OA\Property(property: 'course_id', type: 'integer', example: 1),
        new OA\Property(property: 'time_mins', type: 'integer', example: 60),
        new OA\Property(property: 'num_questions', type: 'integer', example: 20),
        new OA\Property(property: 'pass_mark', type: 'integer', example: 70),
        new OA\Property(property: 'active', type: 'boolean', example: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class Exam extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public array $translatable = ['name', 'desc'];

    public function course()
    {
        return $this->belongsTo(Course::class);
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
