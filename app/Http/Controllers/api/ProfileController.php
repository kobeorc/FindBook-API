<?php

namespace App\Http\Controllers\api;

use App\Models\Book;
use App\Models\Creator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ProfileController extends ApiController
{
    public function inventory()
    {
        /** @var User $user */
        $user = Auth::user();
        $books = $user->inventory()->whereNull('archived_at')->with(['authors', 'publishers', 'categories', 'users'])->get();
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
        $books = $user->inventory()->whereNotNull('archived_at')->with(['authors', 'publishers', 'categories', 'users'])->get();

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
        $this->validate($request, [
            'book_id' => 'required|exists:books.id'
        ]);
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

        $favorites = $user->favorite()->with(['authors', 'publishers', 'categories', 'users'])->get();

        return $this->jsonResponse($favorites);
    }

    public function deleteFromFavorite(Request $request, $bookId)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->whereHas('favorite', function ($query) use ($bookId) {
            $query->whereBookId($bookId);
        })->exists(), 404);
        $user->favorite()->detach($bookId);

        return response('', 200);
    }

    public function putToInventory(Request $request)
    {
        $this->validate($request, [
            'book_id'             => 'sometimes|exists:books.id',
            'book_name'           => 'sometimes|string',
            'book_description'    => 'sometimes|string',
            'year'                => 'sometimes|integer',
            'author_full_name'    => 'sometimes|array',
            'author_full_name.*'  => 'sometimes|string',
            'publisher_full_name' => 'sometimes|string',
            'category_id'         => 'sometimes|exists:categories.id',
            'images'              => 'sometimes|array',
            'images.*'            => 'sometimes|image',
        ]);
        /** @var User $user */
        $user = Auth::user();

        $bookId = $request->get('book_id');
        $bookName = $request->get('book_name');
        $bookDescription = $request->get('book_description');
        $bookYear = $request->get('year');
        $bookLatitude = $request->get('latitude');
        $bookLongitude = $request->get('longitude');

        $authorFullNames = $request->get('author_full_name');
        $publisherFullName = $request->get('publisher_full_name');

        $categoryId = $request->get('category_id');

        if ($bookId) {
            $book = Book::findOrFail($bookId);
            $book->name = $bookName ?? $book->name;
            $book->description = $bookDescription ?? $book->description;
            $book->latitude = $bookLatitude ?? $book->latitude;
            $book->longitude = $bookLongitude ?? $book->longitude;
            $book->year = $bookYear ?? $book->year;
            $book->save();
        } else {
            $book = New Book();
            $book->name = $bookName;
            $book->description = $bookDescription;
            $book->latitude = $bookLatitude;
            $book->longitude = $bookLongitude;
            $book->year = $bookYear;
            $book->save();
            $book->users()->attach($user);
        }

        /** Add Images */
        $images = $request->file('images');

        if ($images instanceof UploadedFile) {
            $origName = '/books/' . $book->id . '/' . str_random() . '.' . $images->getClientOriginalExtension();
            $this->uploadImage($images, $origName);
            $image = \App\Models\Image::create([
                'path' => '/images/' . $origName,
            ]);

            $book->images()->attach($image);

        } elseif (is_array($images)) {
            foreach ($images as $image) {
                $origName = '/books/' . $book->id . '/' . str_random() . '.' . $image->getClientOriginalExtension();
                $this->uploadImage($image, $origName);
                $image_instance = \App\Models\Image::create([
                    'path' => '/images/' . $origName,
                ]);
                $book->images()->attach($image_instance);
            }
        }

        if (!empty($authorFullNames)) {
            $authorId = [];
            foreach ($authorFullNames as $authorFullName) {
                $author = Creator::query()
                                 ->whereType(Creator::TYPE_AUTHOR)
                                 ->whereFullName($authorFullName)
                                 ->first();

                if (!$author->exists()) {
                    $author = new Creator();
                    $author->type = Creator::TYPE_AUTHOR;
                    $author->full_name = $authorFullName;
                    $author->save();
                }
                $authorId[] = $author->id;
            }
            $book->creators()->sync($authorId);
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

        if ($categoryId) {
            $book->categories()->attach($categoryId);
        }

        return response('', 201);
    }

    public function uploadImage($image, $origName)
    {
        $path = public_path('images' . $origName);
        @mkdir(dirname($path), 0777, true);

        /** @var \Intervention\Image\Image $img */
        $img = Image::make($image);
        $img->resize(null, 600, function ($const) {
            $const->aspectRatio();
        })->save($path);
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'avatar'   => 'image|mimes:jpeg,jpg,png,gif',
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users|max:255',
            'password' => 'sometimes|min:6|confirmed|string',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $userName = $request->get('name');
        $userEmail = $request->get('email');
        $fileAvatar = $request->file('avatar');
        $password = $request->get('password');

        $userAvatar = '/avatar/' . str_random() . '.' . $fileAvatar->getClientOriginalExtension();
        /** @var \Intervention\Image\Image $img */
        $this->uploadImage($fileAvatar, $userAvatar);

        $image = \App\Models\Image::create([
            'path' => '/images' . $userAvatar,
        ]);

        $user->avatar()->attach($image);

        $user->name = $userName ?? $user->name;
        $user->email = $userEmail ?? $user->email;
        $user->password = $password ?? $user->password;
        $user->save();

        return response('', 200);
    }

    public function deleteFromArchive(Request $request, $bookId)
    {
        /** @var User $user */
        $user = Auth::user();
        $book = $user->inventory()->whereBookId($bookId);
        abort_unless($book->exists(), 404, 'У пользователя нет этой книги');

        $user->inventory()->updateExistingPivot($bookId, ['archived_at' => null]);

        return response()->make('', 200);
    }

    public function deleteFromInventory(Request $request, $bookId)
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Book $book */
        $book = $user->inventory()->whereBookId($bookId);
        abort_unless($book->exists(), 404, 'У пользователя нет этой книги');

        Book::find($bookId)->delete();

        return response('', 204);
    }


}
