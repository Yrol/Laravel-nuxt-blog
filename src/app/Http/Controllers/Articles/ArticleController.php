<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Rules\CategoryExists;
use App\Rules\UniqueCategoryName;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function update(Request $request)
    {

        $categoryId = $request->input('category_id');
        $this->validate($request, [
            'title' => ['required', new UniqueCategoryName($categoryId)],
            'category_id' => ['required', new CategoryExists($categoryId)],
            'body' => ['required'],
            'is_live' => ['required'],
            'close_to_comments' => ['required']
        ]);
    }
}
