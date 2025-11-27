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

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::orderBy('id', 'DESC')->get();

        return ExamResource::collection($exams);
    }

    public function show(Exam $exam)
    {
        return ExamResource::make($exam);
    }

    public function store(StoreExamRequest $request)
    {
        $exam = Exam::create([
            'name' => json_encode([
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ]),
            'desc' => json_encode([
                'en' => $request->desc_en,
                'ar' => $request->desc_ar,
            ]),
            'img' => $request->img,
            'questions_no' => $request->questions_no,
            'difficulty' => $request->difficulty,
            'duration_mins' => $request->duration_mins,
            'skill_id' => $request->skill_id,
            'active' => $request->input('active', true),
        ]);

        return ExamResource::make($exam);
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $nameData = json_decode($exam->name, true);
        $descData = json_decode($exam->desc, true);

        if ($request->has('name_en')) {
            $nameData['en'] = $request->name_en;
        }

        if ($request->has('name_ar')) {
            $nameData['ar'] = $request->name_ar;
        }

        if ($request->has('desc_en')) {
            $descData['en'] = $request->desc_en;
        }

        if ($request->has('desc_ar')) {
            $descData['ar'] = $request->desc_ar;
        }

        $exam->update([
            'name' => json_encode($nameData),
            'desc' => json_encode($descData),
            'img' => $request->input('img', $exam->img),
            'questions_no' => $request->input('questions_no', $exam->questions_no),
            'difficulty' => $request->input('difficulty', $exam->difficulty),
            'duration_mins' => $request->input('duration_mins', $exam->duration_mins),
            'skill_id' => $request->input('skill_id', $exam->skill_id),
            'active' => $request->input('active', $exam->active),
        ]);

        return ExamResource::make($exam);
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return response()->json([
            'message' => 'Exam deleted successfully',
        ]);
    }

    public function showQuestions($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);

        return ExamResource::make($exam);
    }

    public function start($examId, Request $request)
    {
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

    public function submit(Request $request, $examId)
    {
        $validator = Validator::make($request->all(), [
            'answers' => 'required| ',
            'answers.*' => 'required|in:1,2,3,4',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // calculating score
        $exam = Exam::findOrFail($examId);
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
