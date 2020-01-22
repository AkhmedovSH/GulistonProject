<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Helpers\Converter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allCategory = Category::orderBy('id', 'DESC')->with('parent')->get();
        
        return response()->json(
            [
                'result' => $allCategory
            ], 200);
    }

    public function categoryPluck()
    {
        $categoriesPluck = Category::pluck('title', 'id')->all();
        
        $converter = new Converter();
        $ojbectsInArray = $converter->ArrayContainsObjects($categoriesPluck);
        

        return response()->json(
            [
                'result' => [
                    'ojbectsInArray' => $ojbectsInArray,
                    'array' => $categoriesPluck
                ]
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable'],
            'image' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $category = Category::add($request->all());
        $category->uploadImage($request->file('image'));

        return response()->json(
            [
                'result' => $category
            ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        return response()->json([
                'result' => $category
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        dd($request->id);
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable'],
            'image' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $category = Category::find($request->id);
       
        $category->edit($request->all());
        $category->uploadImage($request->file('image'));

        return response()->json([
            'result' => $category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            Category::find($id)->remove();
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => $th->getMessage()
                ], 400);
        }
        
    }
}
