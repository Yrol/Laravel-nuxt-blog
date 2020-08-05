<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Article;
use App\Models\Category;
use App\Repositories\Contracts\ICategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(ICategory $category)
    {
        $this->category = $category;
    }

    /*
    * Fetch all categories
    */
    public function index()
    {
        $category = $this->category->all();
        return CategoryResource::collection($category);
    }

    /*
    * Fetching Articles by a category ID given
    */
    public function show($categoryId)
    {
        return response()->json(Article::where('category_id', $categoryId)->latest()->get(), 200);
    }
}
