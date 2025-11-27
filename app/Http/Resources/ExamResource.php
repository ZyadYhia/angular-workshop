<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
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
            'name_en' => $this->name('en'),
            'name_ar' => $this->name('ar'),
            'desc_en' => $this->desc('en'),
            'desc_ar' => $this->desc('ar'),
            'image' => asset("storage/uploads/{$this->img}"),
            'questions_no' => $this->questions_no,
            'difficulty' => $this->difficulty,
            'duration_mins' => $this->duration_mins,
            'active' => $this->active,
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}
