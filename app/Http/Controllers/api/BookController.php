<?php

namespace App\Http\Controllers\api;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookController extends ApiController
{
    public function index()
    {
        abort_unless(Auth::check(),403,'Требуется авторизация');
        $books = Book::isActive()->get();

        return $this->jsonResponse($books);
    }
}
