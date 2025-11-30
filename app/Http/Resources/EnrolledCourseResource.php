<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrolledCourseResource extends JsonResource
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
            'image' => $this->img ? asset("storage/uploads/{$this->img}") : null,
            'active' => $this->active,
            'cat_id' => $this->cat_id,
            'exams' => EnrolledExamResource::collection($this->whenLoaded('exams')),
            'statistics' => $this->when(isset($this->statistics), function () {
                return $this->statistics;
            }),
        ];
    }
}
