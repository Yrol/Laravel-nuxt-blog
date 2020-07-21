<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Rules\CategoryExists;
use App\Rules\UniqueCategoryName;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    /*
    * Display all articles
    */
    public function index()
    {
        return ArticleResource::collection(Article::latest()->paginate(5));
    }
    /**
     * Display a specific article by ID.
     * @param  \App\Model\Articles $articles
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    public function store(Request $request)
    {
        $categoryId = $request->input('category_id');
        //$request->request->add(['user_id', auth()->user()->id]);
        $this->validate($request, [
            'title' => ['required', new UniqueCategoryName($categoryId)],
            'category_id' => ['required', new CategoryExists($categoryId)],
            'body' => ['required'],
            'is_live' => ['required', 'boolean'],
            'close_to_comments' => ['required', 'boolean']
        ]);

        $article = auth()->user()->articles()->create($request->all()); //user ID will be added automatically to the 'user_id' foreign field of articles
        return response(new ArticleResource($article), Response::HTTP_CREATED);
    }

    /*
    * Updating articles
    */
    public function update(Request $request)
    {
    }
}
