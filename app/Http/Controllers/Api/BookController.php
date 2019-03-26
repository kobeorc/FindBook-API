<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Creator;
use App\Models\Point;
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
            'square_top'      => 'required_with:square_left,square_bottom,square_right',
            'square_left'     => 'required_with:square_top,square_bottom,square_right',
            'square_right'    => 'required_with:square_top,square_bottom,square_left',
            'square_bottom'   => 'required_with:square_top,square_left,square_right',
            'latitude'        => 'required_with:longitude',
            'longitude'       => 'required_with:latitude',
        ]);

        $categoriesIds = (array)request()->get('categoriesIds');
        $publisherIds = (array)request()->get('publishersIds');
        $authorsIds = (array)request()->get('authorsIds');
        $latitude = request()->get('latitude', false);
        $longitude = request()->get('longitude', false);

        $square_top = request()->get('square_top');
        $square_left = request()->get('square_left');
        $square_right = request()->get('square_right');
        $square_bottom = request()->get('square_bottom');

        $cache_key = md5(json_encode(request()->toArray()));

        if(\Cache::has($cache_key)){
            return \Cache::get($cache_key);
        }
        /** @var Builder $query */
        $query = Book::isActive()->with(['authors', 'publishers', 'categories', 'users', 'images'])->orderByDesc('id');
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
        if ($square_top && $square_bottom && $square_left && $square_right) {
            $query->whereBetween('latitude', [$square_top, $square_bottom]);
            $query->whereBetween('longitude', [$square_left, $square_right]);
        }

        if ($latitude && $longitude) {
            return $this->jsonPaginateCollectionResponse($this->getNearestBooks($query, $latitude, $longitude));
        }

        return $this->jsonPaginateResponse($query);
    }

    /**
     * TMP for geolocation nearest point
     * move mechanic to psql?
     * @param Builder $query
     * @param         $latitude
     * @param         $longitude
     * @return \Illuminate\Support\Collection
     */
    private function getNearestBooks(Builder $query, $latitude, $longitude)
    {
        $books = $query->get();
        $booksWithLocation = [];
        $userLocation = new Point($latitude, $longitude);

        foreach ($books as $book) {
            $distance = $userLocation->distanceTo(new Point($book->latitude, $book->longitude));
            $book->distance = $distance;
            $booksWithLocation[] = $book;
        }

        $result = collect($booksWithLocation);

        $sorted = $result->sortBy('distance');

        return $sorted->values();
    }

    public function show(Request $request, $bookId)
    {
        $book = Book::isActive()->with(['authors', 'publishers', 'categories', 'users', 'images'])->findOrFail($bookId);
        return $this->jsonResponse($book);
    }
}
