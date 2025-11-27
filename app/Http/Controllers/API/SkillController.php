<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Http\Resources\SkillResource;
use App\Models\Skill;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::orderBy('id', 'DESC')->get();

        return SkillResource::collection($skills);
    }

    public function show(Skill $skill)
    {
        $skill->load('exams');

        return SkillResource::make($skill);
    }

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
