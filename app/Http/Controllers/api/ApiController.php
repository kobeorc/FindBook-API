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

        $result = $data->limit($limit)->offset($offset)->get();

        return response()->json($result);
    }
}