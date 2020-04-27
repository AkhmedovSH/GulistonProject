<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResourceCollection;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $allCategory = Category::get();

        return response()->json(
            [
                'result' => $allCategory
            ], 200);
    }

    public function getChildrensIds($categories, $category_id){
        $category_ids = [];
        foreach ($categories as $key => $value) {
            # code...
        }
        
    }

    public function fetch_recursive($src_arr, $currentid, $parentfound = false, $cats = array())
    {
        $category_ids = [];
        foreach($src_arr as $row)
        {
            if((!$parentfound && $row['id'] == $currentid) || $row['parent_id'] == $currentid)
            {
                array_push($category_ids, $row['id']);
                if($row['parent_id'] == $currentid)
                    $cats = array_merge($cats, $this->fetch_recursive($src_arr, $row['id'], true));
            }
        }
        return $category_ids;
    }

    public function getCategoryProducts($category_id)
    {   
        $categories = Category::all();
        $category_ids = $this->fetch_recursive($categories, $category_id);

        $allCategoryProducts = Product::whereIn('category_id', $category_ids)
        ->paginate(20);
        
        return response()->json(
            [
                'result' => $allCategoryProducts
            ], 200);
    }

    public function getCompanyCategoryProducts($company_category_id)
    {
        $allCompanyCategoryProducts = Product::where('company_category_id', $company_category_id)
        ->paginate(20);
        
        return response()->json(
            [
                'result' => $allCompanyCategoryProducts
            ], 200);
    }
}
