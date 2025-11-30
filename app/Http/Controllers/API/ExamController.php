<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Exams', description: 'Exam management and taking endpoints')]
class ExamController extends Controller
{
    #[OA\Get(
        path: '/exams',
        summary: 'Get all exams',
        tags: ['Exams'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of exams',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Exam')
                )
            ),
        ]
    )]
    public function index()
    {
        $exams = Exam::orderBy('id', 'DESC')->get();

        return ExamResource::collection($exams);
    }

    #[OA\Get(
        path: '/exams/{id}',
        summary: 'Get a specific exam',
        tags: ['Exams'],
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
                description: 'Exam details',
                content: new OA\JsonContent(ref: '#/components/schemas/Exam')
            ),
            new OA\Response(response: 404, description: 'Exam not found'),
        ]
    )]
    public function show(Exam $exam)
    {
        return ExamResource::make($exam);
    }

    #[OA\Post(
        path: '/exams',
        summary: 'Create a new exam',
        security: [['bearerAuth' => []]],
        tags: ['Exams'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name_en', 'name_ar', 'desc_en', 'desc_ar', 'course_id', 'questions_no', 'duration_mins', 'difficulty'],
                properties: [
                    new OA\Property(property: 'name_en', type: 'string', example: 'Laravel Basics'),
                    new OA\Property(property: 'name_ar', type: 'string', example: 'أساسيات لارافيل'),
                    new OA\Property(property: 'desc_en', type: 'string', example: 'Test your Laravel knowledge'),
                    new OA\Property(property: 'desc_ar', type: 'string', example: 'اختبر معرفتك بلارافيل'),
                    new OA\Property(property: 'course_id', type: 'integer', example: 1),
                    new OA\Property(property: 'questions_no', type: 'integer', example: 20),
                    new OA\Property(property: 'duration_mins', type: 'integer', example: 60),
                    new OA\Property(property: 'difficulty', type: 'string', example: 'medium'),
                    new OA\Property(property: 'img', type: 'string', example: 'exam.png'),
                    new OA\Property(property: 'active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Exam created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Exam')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreExamRequest $request)
    {
        $exam = Exam::create([
            'name' => [
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ],
            'desc' => [
                'en' => $request->desc_en,
                'ar' => $request->desc_ar,
            ],
            'img' => $request->img,
            'questions_no' => $request->questions_no,
            'difficulty' => $request->difficulty,
            'duration_mins' => $request->duration_mins,
            'course_id' => $request->course_id,
            'active' => $request->input('active', true),
        ]);

        return ExamResource::make($exam);
    }

    #[OA\Put(
        path: '/exams/{id}',
        summary: 'Update an exam',
        security: [['bearerAuth' => []]],
        tags: ['Exams'],
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
                    new OA\Property(property: 'name_en', type: 'string', example: 'Laravel Basics'),
                    new OA\Property(property: 'name_ar', type: 'string', example: 'أساسيات لارافيل'),
                    new OA\Property(property: 'desc_en', type: 'string', example: 'Test your Laravel knowledge'),
                    new OA\Property(property: 'desc_ar', type: 'string', example: 'اختبر معرفتك بلارافيل'),
                    new OA\Property(property: 'course_id', type: 'integer', example: 1),
                    new OA\Property(property: 'questions_no', type: 'integer', example: 20),
                    new OA\Property(property: 'duration_mins', type: 'integer', example: 60),
                    new OA\Property(property: 'difficulty', type: 'string', example: 'medium'),
                    new OA\Property(property: 'img', type: 'string', example: 'exam.png'),
                    new OA\Property(property: 'active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Exam updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Exam')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Exam not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $exam->update([
            'name' => [
                'en' => $request->input('name_en', $exam->getTranslation('name', 'en')),
                'ar' => $request->input('name_ar', $exam->getTranslation('name', 'ar')),
            ],
            'desc' => [
                'en' => $request->input('desc_en', $exam->getTranslation('desc', 'en')),
                'ar' => $request->input('desc_ar', $exam->getTranslation('desc', 'ar')),
            ],
            'img' => $request->input('img', $exam->img),
            'questions_no' => $request->input('questions_no', $exam->questions_no),
            'difficulty' => $request->input('difficulty', $exam->difficulty),
            'duration_mins' => $request->input('duration_mins', $exam->duration_mins),
            'course_id' => $request->input('course_id', $exam->course_id),
            'active' => $request->input('active', $exam->active),
        ]);

        return ExamResource::make($exam);
    }

    #[OA\Delete(
        path: '/exams/{id}',
        summary: 'Delete an exam',
        security: [['bearerAuth' => []]],
        tags: ['Exams'],
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
                description: 'Exam deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Exam deleted successfully'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Exam not found'),
        ]
    )]
    public function destroy(Exam $exam)
    {
        $this->authorize('delete', $exam);

        // Delete associated questions first
        $exam->questions()->delete();

        // Delete the exam
        $exam->delete();

        return response()->json([
            'message' => 'Exam deleted successfully',
        ]);
    }

    #[OA\Get(
        path: '/exams/show-questions/{id}',
        summary: 'Get exam questions for taking',
        security: [['bearerAuth' => []]],
        tags: ['Exams'],
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
                description: 'Exam with questions',
                content: new OA\JsonContent(ref: '#/components/schemas/Exam')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Exam not found'),
        ]
    )]
    public function showQuestions($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);

        $this->authorize('viewQuestions', $exam);

        return ExamResource::make($exam);
    }

    #[OA\Post(
        path: '/exams/start/{id}',
        summary: 'Start an exam',
        security: [['bearerAuth' => []]],
        tags: ['Exams'],
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
                description: 'Exam started successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'exam begins'),
                        new OA\Property(property: 'token', type: 'string'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Exam not found'),
        ]
    )]
    public function start($examId, Request $request)
    {
        $exam = Exam::findOrFail($examId);

        $this->authorize('take', $exam);

        $user = $request->user();
        if (! $user->exams->contains($examId)) {
            $user->exams()->attach($examId);
        } else {
            $user->exams()->updateExistingPivot($examId, [
                'status' => 'closed',
            ]);
        }

        return response()->json([
            'message' => 'exam begins',
            'token' => $request->bearerToken(),
        ]);
    }

    #[OA\Post(
        path: '/exams/submit/{id}',
        summary: 'Submit exam answers',
        security: [['bearerAuth' => []]],
        tags: ['Exams'],
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
                required: ['answers'],
                properties: [
                    new OA\Property(
                        property: 'answers',
                        type: 'object',
                        example: ['1' => 2, '2' => 1, '3' => 4]
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Exam submitted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'exam finished'),
                        new OA\Property(property: 'score', type: 'number', format: 'float', example: 85.5),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Exam not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function submit(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId);

        $this->authorize('take', $exam);

        $validator = Validator::make($request->all(), [
            'answers' => 'required| ',
            'answers.*' => 'required|in:1,2,3,4',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // calculating score
        $points = 0;
        $totalQuesNum = $exam->questions->count();
        foreach ($exam->questions as $question) {
            if (isset($request->answers[$question->id])) {
                $userAns = $request->answers[$question->id];
                $rightAns = $question->right_ans;
                if ($userAns == $rightAns) {
                    $points += 1;
                }
            }
        }
        $score = ($points / $totalQuesNum) * 100;
        // calculating time
        $user = $request->user();
        $pivotRow = $user->exams()->where('exam_id', $examId)->first();
        $startTime = $pivotRow->pivot->created_at;
        $submitTime = Carbon::now();

        $timeMins = $submitTime->diffInMinutes($startTime);
        $message = 'exam finished';
        if ($timeMins > $pivotRow->duration_mins) { // update pivot row
            $score = 0;
            $message = 'you broked the rules';
        }

        $user->exams()->updateExistingPivot($examId, [
            'score' => $score,
            'time_mins' => $timeMins,
        ]);

        return response()->json([
            'message' => $message,
            'score' => $score,
        ]);
    }
}
