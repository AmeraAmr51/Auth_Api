<?php

namespace App\Http\Repositories\Base;

use Illuminate\Database\Eloquent\Model;

use App\Http\Repositories\Base\BaseRepositoryInterface;
use App\Http\Requests\UserRequest;
use Illuminate\Database\Eloquent\Collection;

class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // : => this mean type of return 
    //? => this mean if return is null or collection 

    public function all(): ?Collection
    {
        $data = $this->model->all();

        // count($users) ==false =0;

        if (!count($data)) {
            return null;
        }
        return $data;
    }

    public function create(array $data): Model
    {

        return $this->model->create($data);
    }
}
