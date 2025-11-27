<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrolledExamResource extends JsonResource
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
            'name' => [
                'en' => $this->getTranslation('name', 'en'),
                'ar' => $this->getTranslation('name', 'ar'),
            ],
            'desc' => [
                'en' => $this->getTranslation('desc', 'en'),
                'ar' => $this->getTranslation('desc', 'ar'),
            ],
            'image' => asset("storage/uploads/{$this->img}"),
            'questions_no' => $this->questions_no,
            'difficulty' => $this->difficulty,
            'duration_mins' => $this->duration_mins,
            'active' => $this->active,
            'skill_id' => $this->skill_id,
            'enrollment' => [
                'score' => $this->pivot->score,
                'time_mins' => $this->pivot->time_mins,
                'status' => $this->pivot->status,
                'enrolled_at' => $this->pivot->created_at?->toIso8601String(),
                'updated_at' => $this->pivot->updated_at?->toIso8601String(),
            ],
        ];
    }
}
