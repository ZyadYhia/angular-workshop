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

    public function show(Cat $cat)
    {
        $cat->load('skills');

        return CatResource::make($cat);
    }

    public function store(StoreCatRequest $request)
    {
        $cat = Cat::create([
            'name' => json_encode([
                'en' => $request->name_en,
                'ar' => $request->name_ar,
            ]),
            'active' => $request->input('active', true),
        ]);

        return CatResource::make($cat);
    }

    public function update(UpdateCatRequest $request, Cat $cat)
    {
        $nameData = json_decode($cat->name, true);

        if ($request->has('name_en')) {
            $nameData['en'] = $request->name_en;
        }

        if ($request->has('name_ar')) {
            $nameData['ar'] = $request->name_ar;
        }

        $cat->update([
            'name' => json_encode($nameData),
            'active' => $request->input('active', $cat->active),
        ]);

        return CatResource::make($cat);
    }

    public function destroy(Cat $cat)
    {
        $cat->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ]);
    }
}
