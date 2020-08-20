<?php

namespace App\Repositories\Contracts;

/*
* Interface for Articles
*/
interface IArticle
{
    public function applyTags($id, array $tags);
    public function addComment($id, array $data);
}
