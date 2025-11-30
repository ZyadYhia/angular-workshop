<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCatRequest;
use App\Http\Requests\UpdateCatRequest;
use App\Http\Resources\CatResource;
use App\Models\Cat;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Categories', description: 'Category management endpoints')]
class CatController extends Controller
{
    #[OA\Get(
        path: '/categories',
        summary: 'Get all categories',
        tags: ['Categories'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of categories',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Category')
                )
            ),
        ]
    )]
    public function index()
    {
        $cats = Cat::orderBy('id', 'DESC')->get();

        return CatResource::collection($cats);
    }

    #[OA\Get(
        path: '/categories/{id}',
        summary: 'Get a specific category with courses',
        tags: ['Categories'],
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
                description: 'Category details',
                content: new OA\JsonContent(ref: '#/components/schemas/Category')
            ),
            new OA\Response(response: 404, description: 'Category not found'),
        ]
    )]
    public function show(Cat $category)
    {
        $category->load('courses');

        return CatResource::make($category);
    }

    #[OA\Post(
        path: '/categories',
        summary: 'Create a new category',
        security: [['bearerAuth' => []]],
        tags: ['Categories'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name_en', 'name_ar'],
                properties: [
                    new OA\Property(property: 'name_en', type: 'string', example: 'Programming'),
                    new OA\Property(property: 'name_ar', type: 'string', example: 'البرمجة'),
                    new OA\Property(property: 'active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Category created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Category')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreCatRequest $request)
    {
        $cat = Cat::create([
            'name' => [
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ],
            'active' => $request->input('active', true),
        ]);

        return CatResource::make($cat);
    }

    #[OA\Put(
        path: '/categories/{id}',
        summary: 'Update a category',
        security: [['bearerAuth' => []]],
        tags: ['Categories'],
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
                    new OA\Property(property: 'name_en', type: 'string', example: 'Programming'),
                    new OA\Property(property: 'name_ar', type: 'string', example: 'البرمجة'),
                    new OA\Property(property: 'active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Category')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Category not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateCatRequest $request, Cat $category)
    {
        $category->update([
            'name' => [
                'en' => $request->input('name_en', $category->getTranslation('name', 'en')),
                'ar' => $request->input('name_ar', $category->getTranslation('name', 'ar')),
            ],
            'active' => $request->input('active', $category->active),
        ]);

        return CatResource::make($category);
    }

    #[OA\Delete(
        path: '/categories/{id}',
        summary: 'Delete a category',
        security: [['bearerAuth' => []]],
        tags: ['Categories'],
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
                description: 'Category deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Category deleted successfully'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Category not found'),
        ]
    )]
    public function destroy(Cat $category)
    {
        $this->authorize('delete', $category);

        // Delete all associated courses and their exams
        foreach ($category->courses as $course) {
            // Delete exams and their questions
            foreach ($course->exams as $exam) {
                $exam->questions()->delete();
                $exam->delete();
            }
            $course->delete();
        }

        // Delete the category
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ]);
    }
}
