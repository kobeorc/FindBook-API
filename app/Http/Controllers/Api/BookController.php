<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BookIndexRequest;
use App\Models\Book;
use App\Models\Creator;
use App\Models\Point;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends ApiController
{
    public function index(BookIndexRequest $request)
    {
        $categoriesIds = (array)$request->get('categoriesIds');
        $publisherIds = (array)$request->get('publishersIds');
        $authorsIds = (array)$request->get('authorsIds');
        $latitude = $request->get('latitude', false);
        $longitude = $request->get('longitude', false);
        $except_me = $request->get('except_me',false);
        $book_ids = (array)$request->get('book_ids');

        $square_top = $request->get('square_top');
        $square_left = $request->get('square_left');
        $square_right = $request->get('square_right');
        $square_bottom = $request->get('square_bottom');

        /** @var Builder $query */
        $query = Book::isActive()->apiScope()->orderByDesc('id');
        if ($categoriesIds) {
            $query->whereHas('categories', function ($q) use ($categoriesIds) {
                $q->whereIn('categories.id', $categoriesIds);
            });
        }
        if($except_me){
            $query->whereDoesntHave('users',function ($query){
                $query->where('user_id','=',Auth::user()->id);
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
        if($book_ids){
            $query->whereIn('id',$book_ids);
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
        $book = Book::apiScope()->findOrFail($bookId);
        return $this->jsonResponse($book);
    }

    public function showInventory($userId)
    {
        $user = User::findOrFail($userId);
        return $this->jsonResponse($user->inventory()->whereNull('archived_at')->apiScope()->orderByDesc('id')->get());
    }
}
