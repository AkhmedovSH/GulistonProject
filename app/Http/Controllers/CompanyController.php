<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $allCompany = Company::orderBy('id', 'DESC')->with('categories')->get();

        return response()->json(
            [
                'result' => $allCompany
            ], 200);
    }

    public function companyCategoryProducts($company_category_id)
    {

        $allProducts = Product::where('company_category_id', $company_category_id)->get();

        return response()->json(
            [
                'result' => $allProducts
            ], 200);
    }
}
