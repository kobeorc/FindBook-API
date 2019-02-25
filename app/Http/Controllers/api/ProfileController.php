<?php

namespace App\Http\Controllers\api;

use App\Models\Book;
use App\Models\Creator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ProfileController extends ApiController
{
    public function inventory()
    {
        /** @var User $user */
        $user = Auth::user();
        $books = $user->inventory()->whereNull('archived_at')->get();
        return $this->jsonResponse($books);
    }

    public function current()
    {
        /** @var User $user */
        $user = Auth::user();

        return $this->jsonResponse($user);
    }

    public function archive()
    {
        /** @var User $user */
        $user = Auth::user();
        $books = $user->inventory()->whereNotNull('archived_at')->get();

        return $this->jsonResponse($books);
    }

    public function putToArchive(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $bookId = $request->get('book_id');
        $book = $user->inventory()->whereBookId($bookId);
        abort_unless($book->exists(), 404, 'У пользователя нет этой книги');

        $user->inventory()->updateExistingPivot($bookId, ['archived_at' => Carbon::now()]);

        return response()->make('', 200);
    }

    public function putToFavorite(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $bookId = $request->get('book_id');
        $book = Book::findOrFail($bookId);

        $user->favorite()->attach($book);

        return response('', 201);
    }

    public function getFavorite()
    {
        /** @var User $user */
        $user = Auth::user();

        $favorites = $user->favorite()->get();

        return $this->jsonResponse($favorites);
    }

    public function deleteFromFavorite(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $bookId = $request->get('book_id');
        abort_unless($user->whereHas('favorite', function ($query) use ($bookId) {
            $query->whereBookId($bookId);
        })->exists(), 404);

        $user->favorite()->detach($bookId);

        return response('', 200);
    }

    public function putToInventory(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $bookName = $request->get('book_name', '');
        $bookDescription = $request->get('book_description');
        $bookYear = $request->get('year');
        $bookLatitude = $request->get('latitude', Book::DEFAULT_LATITUDE);
        $bookLongitude = $request->get('longitude', Book::DEFAULT_LONGITUDE);

        $authorFirstName = $request->get('author_first_name', '');
        $authorSecondName = $request->get('author_second_name', '');
        $authorMiddleName = $request->get('author_middle_name', '');

        $publisherFullName = $request->get('publisher_full_name', '');

        /* IMAGES */

        $book = New Book();
        $book->name = $bookName;
        $book->description = $bookDescription;
        $book->latitude = $bookLatitude;
        $book->longitude = $bookLongitude;
        $book->year = $bookYear;
        $book->save();

        /** Add Images */
//        $images = $request->file('images');
//dd($images);
//        foreach ($images as $image){
//            dd($image);
//        }


        $book->users()->attach($user);
        if ($authorFirstName
            || $authorSecondName
            || $authorMiddleName
        ) {
            $author = Creator::query()
                             ->whereType(Creator::TYPE_AUTHOR)
                             ->whereFirstName($authorFirstName)
                             ->whereSecondName($authorSecondName)
                             ->whereMiddleName($authorMiddleName)
                             ->first();

            if (!$author->exists()) {
                $author = new Creator();
                $author->type = Creator::TYPE_AUTHOR;
                $author->first_name = $authorFirstName;
                $author->second_name = $authorSecondName;
                $author->middle_name = $authorMiddleName;
                $author->full_name = $authorSecondName . ' ' . $authorFirstName . ' ' . $authorMiddleName;
                $author->save();
            }

            $book->creators()->attach($author);
        }

        if ($publisherFullName) {
            $publisher = Creator::query()
                                ->whereType(Creator::TYPE_PUBLISHER)
                                ->whereFullName($publisherFullName)
                                ->first();
            if (!$publisher->exists()) {
                $publisher = new Creator();
                $publisher->type = Creator::TYPE_PUBLISHER;
                $publisher->full_name = $publisherFullName;
            }
            $book->creators()->attach($publisher);
        }


        return response('', 201);
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $userName = $request->get('name');
        $userEmail = $request->get('email');
        $fileAvatar = $request->file('avatar');

        $userAvatar = '/avatar/' . str_random() . '.' . $fileAvatar->getClientOriginalExtension();
        $path = public_path('images' . $userAvatar);

        /** @var \Intervention\Image\Image $img */
        $img = Image::make($fileAvatar);
        $img->resize(null,600,function ($const){
            $const->aspectRatio();
        });


        $image = \App\Models\Image::create([
            'path' => $userAvatar,
        ]);

        $user->avatar()->attach($image);

        $user->name = $userName ?? $user->name;
        $user->email = $userEmail ?? $user->email;
        $user->save();

        return response('', 200);
    }

}
