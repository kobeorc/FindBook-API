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

    protected function jsonPaginateResponse(Builder $query)
    {
        $offset = request()->get('offset');
        $limit = request()->get('limit');

        if ($offset) {
            $query->offset($offset);
        }

        if ($limit) {
            $query->limit($limit);
        }
        $items = $query->get();

        \Cache::put(\CacheHelper::getKeyCache(request()), $items, 10);

        return response()->json($items);
    }

    protected function jsonPaginateCollectionResponse(Collection $data)
    {
        $offset = request()->get('offset');
        $limit = request()->get('limit');

        if ($offset) {
            $data = $data->slice($offset);
        }

        if ($limit) {
            $data = $data->take($limit);
        }

        return response()->json($data);
    }
}