<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCatRequest;
use App\Http\Requests\UpdateCatRequest;
use App\Http\Resources\CatResource;
use App\Models\Cat;

class CatController extends Controller
{
    public function index()
    {
        $cats = Cat::orderBy('id', 'DESC')->get();

        return CatResource::collection($cats);
    }

    public function show(Cat $category)
    {
        $category->load('skills');

        return CatResource::make($category);
    }

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

    public function destroy(Cat $category)
    {
        $this->authorize('delete', $category);

        // Delete all associated skills and their exams
        foreach ($category->skills as $skill) {
            // Delete exams and their questions
            foreach ($skill->exams as $exam) {
                $exam->questions()->delete();
                $exam->delete();
            }
            $skill->delete();
        }

        // Delete the category
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ]);
    }
}
