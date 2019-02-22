<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        abort_unless($book->exists(),404, 'У пользователя нет этой книги');

        $book->first()->update(['archived_at',Carbon::now()]);

        return response()->make('',200);
    }


}
