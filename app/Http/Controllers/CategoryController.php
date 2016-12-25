<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Category;

class CategoryController extends Controller
{
    public function index() {
        $all_categories = Category::all();
        return response()->json($all_categories);
    }
    
    public function show($id) {
        $category = Category::find($id);
        return response()->json($category);
    }
}
