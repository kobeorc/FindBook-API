<?php

namespace App\Observers;

use App\Models\Book;
use App\Models\Push;

class BookObserver
{
    public function created(Book $book)
    {
        $push = Push::query()->firstOrNew(['status' => Push::STATUS_PENDING]);
        $push->count++;
        $ids = collect($push->ids);
        $ids = $ids->push($book->id)->all();
        $push->ids = $ids;
        $push->save();
    }
}
