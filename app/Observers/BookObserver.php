<?php

namespace App\Observers;

use App\Models\Book;
use App\Models\Push;

class BookObserver
{
    public function created(Book $book)
    {
        $push = Push::query()->firstOrCreate(['status' => Push::STATUS_PENDING]);
        $push->count++;
        $push->ids = $book->id;
        $push->save();
    }
}
