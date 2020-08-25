<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use App\Repositories\Contracts\IArticle;
use App\Repositories\Eloquent\Criteria\IsLive;
use App\Repositories\Eloquent\Criteria\LatestFirst;
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
        $articles =  $this->articles->withCriteria([
            new LatestFirst(),
            new IsLive(),
        ])->paginate(5);
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
        $resource = $this->articles->find($article->id);
        return new ArticleResource($resource);
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

        $request->merge(['user_id' => auth()->user()->id]);

        // //$article = auth()->user()->articles()->create($request->all()); //user ID will be added automatically to the 'user_id' foreign field of articles
        $resource = $this->articles->create($request->all());
        return response(new ArticleResource($resource), Response::HTTP_CREATED);
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
        $resource = $this->articles->update($article->id, $request->all());

        /*
        * retag is a method of Taggable library [/vendor/cviebrock/eloquent-taggable/src/Taggable.php]
        * Taggable has been defined in Article model as a trait (i.e. use Taggable)
        */
        //$article->retag($request->input('tags'));
        $this->articles->applyTags($article->id, $request->input('tags'));

        return response()->json(new ArticleResource($resource), Response::HTTP_ACCEPTED);
    }

    /*
    * Deleting Articles
    */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        if ($this->articles->delete($article->id)) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        return response()->json(null, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
