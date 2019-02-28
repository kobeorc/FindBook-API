<?php

namespace App\Http\Controllers\api;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookController extends ApiController
{
    public function index()
    {
        $books = Book::isActive()->get();

        return $this->jsonResponse($books);
    }
}
