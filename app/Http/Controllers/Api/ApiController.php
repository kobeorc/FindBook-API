<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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

    protected function jsonPaginateCollectionResponse(Collection $data)
    {
        $offset = request()->get('offset');
        $limit = request()->get('limit');

        if($offset)
            $data->slice($offset);

        if($limit)
            $data->take($limit);

        return response()->json($data);
    }
}