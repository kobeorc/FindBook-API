<?php

namespace App\Http\Controllers\api;

abstract class ApiController
{
    protected function jsonResponse($data)
    {
        return json_encode([$data]);
    }
}