<?php

namespace App\Http\Controllers\Admin;

use App\CompanyCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CompanyCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allCategory = CompanyCategory::orderBy('position', 'ASC')->get();
        //$allCategory = CompanyCategory::orderBy('id', 'DESC')->get();

        return response()->json(
            [
                'result' => $allCategory
            ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCompanyCategories($company_id)
    {
        $companyCategories = CompanyCategory::where('id', $company_id)->get();

        return response()->json([
                'result' => $companyCategories
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'company_id' => ['required']
        ]);
        
        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $category = CompanyCategory::add($request->all());
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
        $category = CompanyCategory::find($id);
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
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'company_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first()
                ], 400);
        }

        $category = CompanyCategory::find($request->id);
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
            CompanyCategory::find($id)->remove();
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
