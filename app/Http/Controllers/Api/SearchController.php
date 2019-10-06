<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;

class SearchController extends ApiController
{
    public function index(Request $request)
    {
        $string = $request->get('search', '');

        $query = Book::isActive()
            ->where(function ($query) use ($string) {
                $query->where('name', 'like', '%' . $string . '%')
                    ->orWhere('description', 'like', '%' . $string . '%')
                    ->orWhereHas('authors', function ($query) use ($string) {
                        $query->where('full_name', 'like', '%' . $string . '%');
                    })
                    ->orWhereHas('publishers', function ($query) use ($string) {
                        $query->where('full_name', 'like', '%' . $string . '%');
                    });
            })
            ->apiScope();

        return $this->jsonPaginateResponse($query);

    }
}
