<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::all();
        return response()->json([$company], 200);
    }

    public function show(Company $company)
    {
        return response()->json([$company], 200);
    }
}
