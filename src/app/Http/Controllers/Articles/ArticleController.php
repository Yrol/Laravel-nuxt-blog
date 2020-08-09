<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Repositories\Contracts\IArticle;
use App\Rules\CategoryExists;
use App\Rules\UniqueCategoryName;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    protected $articles;

    /*
    *  Injecting IArticle interface to the constructor
    */
    public function __construct(IArticle $articles)
    {
        $this->articles = $articles;
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /*
    * Display all articles
    */
    public function index()
    {
        $articles =  $this->articles->all();
        return ArticleResource::collection($articles);
    }
    /**
     * Display a specific article by ID.
     * @param  \App\Model\Articles $articles
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //return new ArticleResource($article); // we could also do this since we have route model binding
        $article = $this->articles->find($article->id);
        return new ArticleResource($article);
    }

    public function store(Request $request)
    {
        $categoryId = $request->input('category_id');
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
    public function update(Request $request, Article $article)
    {
        //Using the policy defined in app/Policies/ArticlePolicy.php and referencing the 'update' method in it
        $this->authorize('update', $article);

        $categoryId = $request->input('category_id');
        $this->validate($request, [
            'title' => ['required', new UniqueCategoryName($categoryId)],
            'category_id' => ['required', new CategoryExists($categoryId)],
            'body' => ['required'],
            'is_live' => ['required', 'boolean'],
            'close_to_comments' => ['required', 'boolean'],
            'tags' => ['required']
        ]);

        //$article->update($request->all());
        $updatedArticle = $this->articles->update($article->id, $request->all());

        /*
        * retag is a method of Taggable library [/vendor/cviebrock/eloquent-taggable/src/Taggable.php]
        * Taggable has been defined in Article model as a trait (i.e. use Taggable)
        */
        $article->retag($request->input('tags'));

        return response()->json(new ArticleResource($updatedArticle), Response::HTTP_ACCEPTED);
    }

    /*
    * Deleting Articles
    */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $current_article =  Article::where('slug', $article->slug)->firstOrfail();

        $current_article->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
