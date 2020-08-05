<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\ModelNotDefined;
use App\Repositories\Contracts\IBase;

/*
* Abstract class that contains the base / common methods to all repositories
* Since this is an abstract class, it cannot be instantiated on its own
*/
abstract class BaseRepository implements IBase
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass(); //will get the current model
    }

    public function all()
    {
        return $this->model::latest()->paginate(5);
    }

    protected function getModelClass()
    {
        //check if the method "model" exist in the repository classes that inherits the BaseRepository.
        if (!method_exists($this, 'model')) {
            throw new ModelNotDefined();
        }

        //if model exists then bind the "model" method that's available in the repository which extends the BaseRepository
        return app()->make($this->model());
    }
}
