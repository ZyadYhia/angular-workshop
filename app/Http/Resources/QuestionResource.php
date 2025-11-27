<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam_id' => $this->exam_id,
            'title' => [
                'en' => $this->getTranslation('title', 'en'),
                'ar' => $this->getTranslation('title', 'ar'),
            ],
            'option_1' => [
                'en' => $this->getTranslation('option_1', 'en'),
                'ar' => $this->getTranslation('option_1', 'ar'),
            ],
            'option_2' => [
                'en' => $this->getTranslation('option_2', 'en'),
                'ar' => $this->getTranslation('option_2', 'ar'),
            ],
            'option_3' => [
                'en' => $this->getTranslation('option_3', 'en'),
                'ar' => $this->getTranslation('option_3', 'ar'),
            ],
            'option_4' => [
                'en' => $this->getTranslation('option_4', 'en'),
                'ar' => $this->getTranslation('option_4', 'ar'),
            ],
            'right_ans' => $this->right_ans,
        ];
    }
}
