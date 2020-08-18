<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\Contracts\ICategory;
use App\Repositories\Eloquent\Criteria\EagerloadWith;
use App\Repositories\Eloquent\Criteria\EagerLoadWithCount;
use Illuminate\Http\Request;

class CategoriesWithArticlesController extends Controller
{
    /**
     * Returns all the categories that consist of articles (using count)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected $UsersWithArticlesController;

    public function __construct(ICategory $categories)
    {
        $this->categories = $categories;
    }

    public function __invoke()
    {
        $resource = $this->categories->withCriteria([
            new EagerLoadWithCount(['articles']),
        ])->all();

        return CategoryResource::collection($resource);
    }
}
