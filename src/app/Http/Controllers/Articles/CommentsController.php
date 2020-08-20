<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Repositories\Contracts\IArticle;
use App\Repositories\Contracts\IComment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentsController extends Controller
{
    protected $comments;
    protected $articles;

    public function __construct(IComment $comments, IArticle $articles)
    {
        $this->comments = $comments;
        $this->articles = $articles;
    }

    //adding route model binding - "Article $article"
    public function store(Request $request, Article $article)
    {
        $this->validate($request, [
            'body' => ['required']
        ]);

        $comment = $this->articles->addComment($article->id, [
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return response(new CommentResource($comment), Response::HTTP_CREATED);
    }
}
