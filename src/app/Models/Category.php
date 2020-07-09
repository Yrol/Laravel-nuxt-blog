<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'slug'
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function articles()
    {
        $this->hasMany(Article::class);
    }
}
