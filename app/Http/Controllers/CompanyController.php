<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::all();

        $allCompany = $company->map(function ($company) {
            return [
                "id" => $company->id,
                "title" => $company->title,
                "description" => $company->description,
                "image" => asset('uploads/companies/' . $company->image),
                "created_at" => $company->created_at,
            ];
        });
 
        return response()->json($allCompany, 200);
    }

    public function show(Company $company)
    {
        return response()->json($company, 200);
    }
}
