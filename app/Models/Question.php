<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;
use Spatie\Translatable\HasTranslations;

#[OA\Schema(
    schema: 'Question',
    title: 'Question',
    description: 'Question model',
    required: ['id', 'title', 'exam_id', 'right_ans'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'object', example: ['en' => 'What is Laravel?', 'ar' => 'ما هو لارافيل؟']),
        new OA\Property(property: 'option_1', type: 'object', example: ['en' => 'A framework', 'ar' => 'إطار عمل']),
        new OA\Property(property: 'option_2', type: 'object', example: ['en' => 'A database', 'ar' => 'قاعدة بيانات']),
        new OA\Property(property: 'option_3', type: 'object', example: ['en' => 'A language', 'ar' => 'لغة برمجة']),
        new OA\Property(property: 'option_4', type: 'object', example: ['en' => 'A server', 'ar' => 'خادم']),
        new OA\Property(property: 'right_ans', type: 'integer', example: 1),
        new OA\Property(property: 'exam_id', type: 'integer', example: 1),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
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
