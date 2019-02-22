<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

abstract class ApiController extends Controller
{
    protected function jsonResponse($data)
    {
        return response()->json($data);
    }
}