<?php

namespace App\Http\Repositories\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface 
{
    public function all():? Collection;
    public function create(array $data):Model;
   
}