<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//providing the namespace for the repositories that is going to be used
use App\Repositories\Contracts\IArticle;
use App\Repositories\Contracts\IUser;

use App\Repositories\Eloquent\ArticleRepository;
use App\Repositories\Eloquent\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //binding the interface and the implementation of  Users and Articles
        $this->app->bind(IArticle::class, ArticleRepository::class);
        $this->app->bind(IUser::class, UserRepository::class);
    }
}
