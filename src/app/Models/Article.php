<?php

namespace App\Models;

use App\User;
use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //use Taggable library
    use Taggable;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'slug',
        'category_id',
        'tags',
        'close_to_comment',
        'is_live'
    ];

    protected static function boot()
    {
        parent::boot();

        //Adding the additional functionality (create a slug using the title) whenever this model gets created (during HTTP requests when creating an article & etc)
        static::creating(function ($article) {
            $article->slug = str_slug($article->title);
        });

        //Adding the additional functionality (create a slug using the title) whenever this model gets updated (during HTTP requests when creating an article & etc)
        static::updating(function ($article) {
            $article->slug = str_slug($article->title);
        });
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function category()
    {
        $this->belongsTo(Category::class);
    }

    /**
     * Get the route key for the model.
     * Using the column "slug" value instead of the ID to retrieve a single Article. ex: http://localhost:8080/api/articles/reprehenderit-consequuntur-consequatur-nihil
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
