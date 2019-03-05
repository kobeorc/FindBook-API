<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

abstract class ApiController extends Controller
{
    protected function jsonResponse($data)
    {
        return response()->json($data);
    }

    protected function jsonPaginateResponse(Builder $data)
    {
        $offset = request()->get('offset');
        $limit = request()->get('limit');

        if($offset)
            $data->offset($offset);

        if($limit)
            $data->limit($limit);


        return response()->json($data->get());
    }
}