<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $allCompany = Company::orderBy('id', 'DESC')->get();

        return response()->json(
            [
                'result' => $allCompany
            ], 200);
    }


    public function companyProducts($id)
    {

        $allCompanyProducts = Company::where('id', $id)->with('product')->get();

        return response()->json(
            [
                'result' => $allCompanyProducts
            ], 200);
    }
}
