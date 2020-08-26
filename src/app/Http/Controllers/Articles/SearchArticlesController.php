<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Repositories\Contracts\IArticle;
use Illuminate\Http\Request;

class SearchArticlesController extends Controller
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected $articles;

    public function __construct(IArticle $article)
    {
        $this->articles = $article;
    }

    public function __invoke(Request $request)
    {
        $articles = $this->articles->search($request->all());
        return ArticleResource::collection($articles);
    }
}
