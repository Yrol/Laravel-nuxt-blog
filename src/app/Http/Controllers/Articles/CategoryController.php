<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Repositories\Contracts\ICategory;
use App\Repositories\Eloquent\Criteria\EagerLoadWithCount;
use App\Repositories\Eloquent\Criteria\LatestFirst;

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
        //$categories = $this->category->all(); // without any criteria

        /*
        * Fetching all categories with the article count
        */
        $categories = $this->category->withCriteria([
            new LatestFirst(),
            new EagerLoadWithCount('articles')
        ])->all();

        return CategoryResource::collection($categories);
    }
}
