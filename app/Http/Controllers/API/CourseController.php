<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Courses', description: 'Course management endpoints')]
class CourseController extends Controller
{
    #[OA\Get(
        path: '/courses',
        summary: 'Get all courses',
        tags: ['Courses'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of courses',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Course')
                )
            ),
        ]
    )]
    public function index()
    {
        $courses = Course::orderBy('id', 'DESC')->get();

        return CourseResource::collection($courses);
    }

    #[OA\Get(
        path: '/courses/{id}',
        summary: 'Get a specific course with exams',
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Course details',
                content: new OA\JsonContent(ref: '#/components/schemas/Course')
            ),
            new OA\Response(response: 404, description: 'Course not found'),
        ]
    )]
    public function show(Course $course)
    {
        $course->load('exams');

        return CourseResource::make($course);
    }

    #[OA\Post(
        path: '/courses',
        summary: 'Create a new course',
        security: [['bearerAuth' => []]],
        tags: ['Courses'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name_en', 'name_ar', 'cat_id'],
                properties: [
                    new OA\Property(property: 'name_en', type: 'string', example: 'Laravel'),
                    new OA\Property(property: 'name_ar', type: 'string', example: 'لارافيل'),
                    new OA\Property(property: 'cat_id', type: 'integer', example: 1),
                    new OA\Property(property: 'img', type: 'string', example: 'laravel.png'),
                    new OA\Property(property: 'active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Course created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Course')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreCourseRequest $request)
    {
        $course = Course::create([
            'name' => [
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ],
            'img' => $request->img,
            'cat_id' => $request->cat_id,
            'active' => $request->input('active', true),
        ]);

        return CourseResource::make($course);
    }

    #[OA\Put(
        path: '/courses/{id}',
        summary: 'Update a course',
        security: [['bearerAuth' => []]],
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name_en', type: 'string', example: 'Laravel'),
                    new OA\Property(property: 'name_ar', type: 'string', example: 'لارافيل'),
                    new OA\Property(property: 'cat_id', type: 'integer', example: 1),
                    new OA\Property(property: 'img', type: 'string', example: 'laravel.png'),
                    new OA\Property(property: 'active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Course updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Course')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Course not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update([
            'name' => [
                'en' => $request->input('name_en', $course->getTranslation('name', 'en')),
                'ar' => $request->input('name_ar', $course->getTranslation('name', 'ar')),
            ],
            'img' => $request->input('img', $course->img),
            'cat_id' => $request->input('cat_id', $course->cat_id),
            'active' => $request->input('active', $course->active),
        ]);

        return CourseResource::make($course);
    }

    #[OA\Delete(
        path: '/courses/{id}',
        summary: 'Delete a course',
        security: [['bearerAuth' => []]],
        tags: ['Courses'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Course deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Course deleted successfully'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Course not found'),
        ]
    )]
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        // Delete all associated exams and their questions
        foreach ($course->exams as $exam) {
            $exam->questions()->delete();
            $exam->delete();
        }

        // Delete the course
        $course->delete();

        return response()->json([
            'message' => 'Course deleted successfully',
        ]);
    }
}
