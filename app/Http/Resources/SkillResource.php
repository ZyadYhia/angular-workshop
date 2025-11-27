<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SkillResource extends JsonResource
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
            'image' => $this->img ? asset("storage/uploads/{$this->img}") : null,
            'exams' => ExamResource::collection($this->whenLoaded('exams')),
        ];
    }
}
