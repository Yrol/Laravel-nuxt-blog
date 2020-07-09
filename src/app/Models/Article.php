<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
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

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function category()
    {
        $this->belongsTo(Category::class);
    }
}
