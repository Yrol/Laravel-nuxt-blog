<?php
namespace App\Repositories\Eloquent;

use App\Models\Article;
use App\Repositories\Contracts\IArticle;

/*
* Implementation class for IArticle class
* this contains functions Specific to the Article
*/

class ArticleRepository extends BaseRepository implements IArticle
{
    public function model()
    {
        return Article::class;  //this returns the model namespace - App\Models\Article
    }

    public function applyTags($id, array $tags)
    {
        $article = $this->find($id);
        $article->retag($tags);
    }

    public function addComment($id, array $data)
    {
        $article = $this->find($id);
        $comment =  $article->comments()->create($data);
        return $comment;
    }
}
