<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::all();
        return response()->json([$company], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'parent_id' => 'nullable',
            'image' => 'nullable'
        ]);

        $company = Company::add($request->all());
        $company->uploadImage($request->file('image'));
        
         return $company;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::find($id);
        return response()->json([$company], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'parent_id' => 'nullable',
            'image' => 'nullable'
        ]);
       
        $company = Company::find($id);
        $company->edit($request->all());
        $company->uploadImage($request->file('image'));
        
        return $company;
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
            Company::find($id)->remove();
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Cannot delete'], 200);
        }

        return response()->json(['success' => 'Deleted'], 200);
    }
}
