<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Category;
use App\Repositories\Contracts\IArticle;
use Illuminate\Http\Response;

class ArticlesByCategoryController extends Controller
{
    /**
     * Controller retrieves articles by category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    protected $article;

    public function __construct(IArticle $article) //using route model binding
    {
        $this->article = $article;
    }

    public function __invoke(Category $category)
    {
        $categoryId = $category->id;

        $resource = $this->article->findWhere('category_id', $categoryId);

        return ArticleResource::collection($resource);
    }
}
