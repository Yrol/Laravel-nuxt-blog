<?php

namespace App\Http\Resources;

use App\Models\Category;
use Conner\Tagging\Taggable;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    use Taggable;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'slug' => $this->slug,
            'is_live' => $this->is_live,
            'close_to_comment' => $this->close_to_comment,
            'created_at' => $this->created_at,
            'category' => Category::select('id', 'title', 'slug')->where('id', $this->category_id)->first(),
            'user_id' => $this->user_id,
            'tag_list' => [
                //'tags' => $this->tags //will output tags will all information
                $this->tagSlugs()
            ]
        ];
    }
}
