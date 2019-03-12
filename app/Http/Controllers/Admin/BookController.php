<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::isActive()->paginate(20);
        return view('admin.books.index',compact('books'));
    }
}