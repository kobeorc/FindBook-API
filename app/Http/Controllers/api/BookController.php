<?php

namespace App\Http\Controllers\api;

use App\Models\Book;
use Illuminate\Support\Facades\Request;

class BookController extends ApiController
{
    public function index()
    {
        $books = Book::isActive()->with(['authors','publishers','categories','users'])->get();

        return $this->jsonResponse($books);
    }

    public function show(Request $request, $bookId)
    {
        $book = Book::isActive()->with(['authors','publishers','categories','users'])->findOrFail($bookId);
        return $this->jsonResponse($book);
    }
}
