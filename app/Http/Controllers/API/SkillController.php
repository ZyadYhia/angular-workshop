<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Skills', description: 'Skill management endpoints')]
class SkillController extends Controller
{
    #[OA\Get(
        path: '/skills',
        summary: 'Get all skills',
        tags: ['Skills'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of skills',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Skill')
                )
            ),
        ]
    )]
    public function index()
    {
        $skills = Skill::orderBy('id', 'DESC')->get();

        return SkillResource::collection($skills);
    }

    #[OA\Get(
        path: '/skills/{id}',
        summary: 'Get a specific skill with exams',
        tags: ['Skills'],
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
                description: 'Skill details',
                content: new OA\JsonContent(ref: '#/components/schemas/Skill')
            ),
            new OA\Response(response: 404, description: 'Skill not found'),
        ]
    )]
    public function show(Skill $skill)
    {
        $skill->load('exams');

        return SkillResource::make($skill);
    }

    #[OA\Post(
        path: '/skills',
        summary: 'Create a new skill',
        security: [['bearerAuth' => []]],
        tags: ['Skills'],
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
                description: 'Skill created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Skill')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreSkillRequest $request)
    {
        $skill = Skill::create([
            'name' => [
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ],
            'img' => $request->img,
            'cat_id' => $request->cat_id,
            'active' => $request->input('active', true),
        ]);

        return SkillResource::make($skill);
    }

    #[OA\Put(
        path: '/skills/{id}',
        summary: 'Update a skill',
        security: [['bearerAuth' => []]],
        tags: ['Skills'],
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
                description: 'Skill updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Skill')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Skill not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateSkillRequest $request, Skill $skill)
    {
        $skill->update([
            'name' => [
                'en' => $request->input('name_en', $skill->getTranslation('name', 'en')),
                'ar' => $request->input('name_ar', $skill->getTranslation('name', 'ar')),
            ],
            'img' => $request->input('img', $skill->img),
            'cat_id' => $request->input('cat_id', $skill->cat_id),
            'active' => $request->input('active', $skill->active),
        ]);

        return SkillResource::make($skill);
    }

    #[OA\Delete(
        path: '/skills/{id}',
        summary: 'Delete a skill',
        security: [['bearerAuth' => []]],
        tags: ['Skills'],
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
                description: 'Skill deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Skill deleted successfully'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Skill not found'),
        ]
    )]
    public function destroy(Skill $skill)
    {
        $this->authorize('delete', $skill);

        // Delete all associated exams and their questions
        foreach ($skill->exams as $exam) {
            $exam->questions()->delete();
            $exam->delete();
        }

        // Delete the skill
        $skill->delete();

        return response()->json([
            'message' => 'Skill deleted successfully',
        ]);
    }
}
