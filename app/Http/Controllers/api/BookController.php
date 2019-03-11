<?php

namespace App\Http\Controllers\api;

use App\Models\Book;
use App\Models\Creator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookController extends ApiController
{
    public function index()
    {
        $this->validate(request(), [
            'categoriesIds'   => 'sometimes|array',
            'categoriesIds.*' => 'integer',
            'publishersIds'   => 'sometimes|array',
            'publishersIds.*' => 'integer',
            'authorsIds'      => 'sometimes|array',
            'authorsIds.*'    => 'integer',
        ]);
        $categoriesIds = (array)request()->get('categoriesIds');
        $publisherIds = (array)request()->get('publishersIds');
        $authorsIds = (array)request()->get('authorsIds');

        /** @var Builder $query */
        $query = Book::isActive()->with(['authors', 'publishers', 'categories', 'users','images'])->orderByDesc('id');
        if ($categoriesIds) {
            $query->whereHas('categories', function ($q) use ($categoriesIds) {
                $q->whereIn('categories.id', $categoriesIds);
            });
        }
        if ($publisherIds) {
            $query->whereHas('creators', function ($q) use ($publisherIds) {
                $q->whereIn('creators.id', $publisherIds)
                  ->where('type', Creator::TYPE_PUBLISHER);
            });
        }
        if ($authorsIds) {
            $query->whereHas('creators', function ($q) use ($authorsIds) {
                $q->whereIn('creators.id', $authorsIds)
                  ->where('type', Creator::TYPE_AUTHOR);
            });
        }

        return $this->jsonPaginateResponse($query);
    }

    public function show(Request $request, $bookId)
    {
        $book = Book::isActive()->with(['authors', 'publishers', 'categories', 'users','images'])->findOrFail($bookId);
        return $this->jsonResponse($book);
    }
}
