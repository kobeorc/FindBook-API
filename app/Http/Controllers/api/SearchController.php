<?php

namespace App\Http\Controllers\api;

use App\Models\Book;
use Illuminate\Http\Request;

class SearchController extends ApiController
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'required|min:3'
        ]);

        $string = $request->get('search');

        $result = Book::where('name', 'like', '%' . $string . '%')
                      ->orWhere('description', 'like', '%' . $string . '%')
                      ->orWhereHas('authors', function ($query) use ($string) {
                          $query->where('full_name', 'like', '%' . $string . '%');
                      })
                      ->orWhereHas('publishers', function ($query) use ($string) {
                          $query->where('full_name', 'like', '%' . $string . '%');
                      })
                      ->with(['authors', 'publishers', 'categories', 'users'])
                      ->get();

        return $this->jsonResponse($result);

    }
}
