<?php

namespace App\Http\Controllers;

use App\Company;
use App\Product;
use App\CompanyCategory;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function getCompanies()
    {
        $allCompany = Company::orderBy('id', 'DESC')->paginate(20);

        return response()->json(
            [
                'result' => $allCompany
            ], 200);
    }

    public function companyCategories()
    {
        $allCompanyCategories = CompanyCategory::get();

        return response()->json(
            [
                'result' => $allCompanyCategories
            ], 200);
    }

    public function oneCompanyCategories($company_id)
    {
        $oneCompanyCategories = CompanyCategory::where('company_id', $company_id)->firstOrFail();
        
        return response()->json(
            [
                'result' => $oneCompanyCategories
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
