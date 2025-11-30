<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'enrolled_courses' => EnrolledCourseResource::collection($this->whenLoaded('enrolledCourses')),
            'enrolled_exams' => EnrolledExamResource::collection($this->whenLoaded('exams')),
            'statistics' => $this->when(isset($this->statistics), function () {
                return $this->statistics;
            }),
        ];
    }
}
