<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\Creator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProfileController extends ApiController
{
    public function inventory()
    {
        /** @var User $user */
        $user = Auth::user();
        $books = $user->inventory()->whereNull('archived_at')->with(['authors', 'publishers', 'categories', 'users', 'images'])->get();
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
        $books = $user->inventory()->whereNotNull('archived_at')->with(['authors', 'publishers', 'categories', 'users', 'images'])->get();

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
            'book_id' => 'required|exists:books,id'
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

        $favorites = $user->favorite()->with(['authors', 'publishers', 'categories', 'users', 'images'])->get();

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
            'book_id'             => 'sometimes|exists:books,id',
            'book_name'           => 'required|string',
            'book_description'    => 'sometimes|string|nullable',
            'year'                => 'sometimes|integer|nullable|digits:4|max:' . Carbon::now('Y'),
            'author_full_name'    => 'sometimes|array',
            'author_full_name.*'  => 'sometimes|string|nullable',
            'publisher_full_name' => 'sometimes|string|nullable',
            'categories_ids'      => 'sometimes|array',
            'categories_ids.*'    => 'sometimes|exists:categories,id|nullable',
            'images'              => 'sometimes|array',
            'images.*'            => 'sometimes|image',
            'latitude'            => 'required_with:longitude|regex:/^\-?[0-9]+\.([0-9]){0,7}$/',
            'longitude'           => 'required_with:latitude|regex:/^\-?[0-9]+\.([0-9]){0,7}$/',
            'address'             => 'sometimes|string',
        ]);
        /** @var User $user */
        $user = Auth::user();

        $bookId = $request->get('book_id');
        $bookName = $request->get('book_name');
        $bookDescription = $request->get('book_description');
        $bookYear = $request->get('year');
        $bookLatitude = $request->get('latitude');
        $bookLongitude = $request->get('longitude');
        $bookAddress = $request->get('address', '');

        $authorFullNames = $request->get('author_full_name');
        $publisherFullName = $request->get('publisher_full_name');

        $categoriesIds = $request->get('categories_ids');

        if ($bookId) {
            $book = Book::findOrFail($bookId);
            $book->name = $bookName ?? $book->name;
            $book->description = $bookDescription ?? $book->description;
            $book->latitude = $bookLatitude ?? $book->latitude;
            $book->longitude = $bookLongitude ?? $book->longitude;
            $book->year = $bookYear ?? $book->year;
            $book->address = $bookAddress ?? $book->address;
            $book->save();
        } else {
            $book = New Book();
            $book->name = $bookName;
            $book->description = $bookDescription;
            $book->latitude = $bookLatitude;
            $book->longitude = $bookLongitude;
            $book->year = $bookYear;
            $book->address = $bookAddress;
            $book->save();
            $book->users()->attach($user);
        }

        /** Add Images */
        $images = $request->file('images', []);
        foreach ($images as $image) {
            $origName = '/books/' . $book->id . '/' . Str::random() . '.' . $image->getClientOriginalExtension();
            $this->uploadImage($image, $origName);
            $image_instance = \App\Models\Image::create([
                'path' => '/images/' . $origName,
            ]);
            $book->images()->attach($image_instance);
        }

        if (!empty($authorFullNames[0])) {
            $authorId = [];
            foreach ($authorFullNames as $authorFullName) {
                $author = Creator::query()
                                 ->whereType(Creator::TYPE_AUTHOR)
                                 ->whereFullName($authorFullName)
                                 ->first();

                if (!$author) {
                    $author = new Creator();
                    $author->type = Creator::TYPE_AUTHOR;
                    $author->full_name = $authorFullName;
                    $author->save();
                }
                $authorId[] = $author->id;
            }
            $book->creators()->sync($authorId);
        }

        if (!empty($publisherFullName)) {
            $publisher = Creator::query()
                                ->whereType(Creator::TYPE_PUBLISHER)
                                ->whereFullName($publisherFullName)
                                ->first();
            if (!$publisher) {
                $publisher = new Creator();
                $publisher->type = Creator::TYPE_PUBLISHER;
                $publisher->full_name = $publisherFullName;
                $publisher->save();
            }
            $book->creators()->attach($publisher);
        }

        if (!empty($categoriesIds[0])) {
            $book->categories()->sync($categoriesIds);
        }

        return $this->jsonResponse($book);
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

        if ($fileAvatar) {
            $userAvatar = '/avatar/' . Str::random() . '.' . $fileAvatar->getClientOriginalExtension();
            /** @var \Intervention\Image\Image $img */
            $this->uploadImage($fileAvatar, $userAvatar);

            $image = \App\Models\Image::create([
                'path' => '/images' . $userAvatar,
            ]);
            $user->avatar()->attach($image);
        }

        if ($userEmail && $password) {
            $user->role = User::ROLE_USER;
        }

        $user->name = $userName ?? $user->name;
        $user->email = $userEmail ?? $user->email;
        $user->password = $password ? \Hash::make($password) : $user->password;
        $user->save();

        return $this->jsonResponse($user);
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

    public function deleteImageFromBook(Request $request, $bookId, $imageId)
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Book $book */
        $book = $user->inventory()->whereBookId($bookId);
        abort_unless($book->exists(), 404, 'У пользователя нет этой книги');
        $book = $book->first();
        $image = $book->images()->whereImageId($imageId);
        abort_unless($image->exists(), 404, 'У книги нет такого изображения');

        $book->images()->detach($imageId);

        return response('', 204);

    }

}
