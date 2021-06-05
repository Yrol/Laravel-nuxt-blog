<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'description',
        'slug',
        'value',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($setting) {
            $setting->slug = str_slug($setting->key);
        });

        static::updating(function ($setting) {
            $setting->slug = str_slug($setting->key);
        });
    }
}
