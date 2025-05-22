<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use Illuminate\Http\Request;

class MainCategoryController extends Controller
{
    //
    public function index(){

        $categories = MainCategory::all();
      

        $catgeorys = [];

        foreach($categories as $category){  
            $catgeorys[] = [
                "id"=>$category->id,
                "name"=> $category->name,

            ];
            
        }
        return response()->json($catgeorys);
    }



}
