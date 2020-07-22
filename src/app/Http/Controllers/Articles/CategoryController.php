<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /*
    * Fetch all categories
    */
    public function index()
    {
        return CategoryResource::collection(Category::latest()->get());
    }

    /*
    * Fetching Articles by a category ID given
    */
    public function show($categoryId)
    {
        return response()->json(Article::where('category_id', $categoryId)->get(), 200);
    }
}
