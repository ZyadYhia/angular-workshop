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
            'name' => json_encode([
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ]),
            'img' => $request->img,
            'cat_id' => $request->cat_id,
            'active' => $request->input('active', true),
        ]);

        return SkillResource::make($skill);
    }

    public function update(UpdateSkillRequest $request, Skill $skill)
    {
        $nameData = json_decode($skill->name, true);

        if ($request->has('name_en')) {
            $nameData['en'] = $request->name_en;
        }

        if ($request->has('name_ar')) {
            $nameData['ar'] = $request->name_ar;
        }

        $skill->update([
            'name' => json_encode($nameData),
            'img' => $request->input('img', $skill->img),
            'cat_id' => $request->input('cat_id', $skill->cat_id),
            'active' => $request->input('active', $skill->active),
        ]);

        return SkillResource::make($skill);
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return response()->json([
            'message' => 'Skill deleted successfully',
        ]);
    }
}
