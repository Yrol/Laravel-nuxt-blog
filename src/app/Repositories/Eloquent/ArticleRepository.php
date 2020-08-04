<?php
namespace App\Repositories\Eloquent;

use App\Models\Article;
use App\Repositories\Contracts\IArticle;

/*
* Implementation class for IArticle class
*/

class ArticleRepository implements IArticle
{
    public function all()
    {
        return Article::latest()->paginate(5);
    }
}
