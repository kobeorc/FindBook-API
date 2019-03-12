<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;

class  CategoryController extends ApiController
{
    public function index()
    {
        $categories = Category::get();
        return $this->jsonResponse($categories);
    }
}
