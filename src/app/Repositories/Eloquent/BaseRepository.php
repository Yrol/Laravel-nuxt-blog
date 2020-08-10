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

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findWhere($column, $value)
    {
        return $this->model->where($column, $value)->first();
    }

    public function findWhereFirst($column, $value)
    {
        return $this->model->where($column, $value)->firstOrFail();
    }

    public function paginate($perpage)
    {
        return $this->model->paginate($perpage);
    }

    public function create(array $data)
    {
        $resource = $this->model->create($data);
        return $resource;
    }

    public function update($id, array $data)
    {
        //reusing the "find" method above
        $resource = $this->model->find($id);
        if ($resource->update($data)) {
            return $resource;
        }

        return null;
    }

    public function delete($id)
    {
        $resource = $this->model->find($id);
        return $resource->delete();
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
