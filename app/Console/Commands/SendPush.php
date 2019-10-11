<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Push;
use Illuminate\Console\Command;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class SendPush extends Command
{
    protected $signature   = 'push:send';
    protected $description = 'Send pushes about new books in app';

    public function handle()
    {
        $push = Push::query()->whereStatus(Push::STATUS_PENDING)->first();

        if (!$push) {
            return;
        }

        // берем только активные книги
        $books = Book::isActive()->orderByDesc('id')->whereIn('id', $push->ids)->get();

        // автор и картинка последней добавленной активной книги
        $authors = $books->first()->authors()->exists() ? implode($books->first()->authors->map(function ($item) {
            return $item->full_name;
        })->all(), ', ') : '';
        $images = $books->first()->images()->exists() ? $books->first()->images()->first()->path : '';

        $custom_data = [
            'book_name'    => $books->first()->name ?? '',
            'book_author'  => $authors,
            'book_image'   => $images,
            'count_of_new' => $books->count(),
            'book_ids'     => $books->map(function ($book) {
                return $book->id;
            })->toArray(),
        ];

        $notificationBuilder = new PayloadNotificationBuilder();
        $notification = $notificationBuilder->build();
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($custom_data);
        $data = $dataBuilder->build();
        $topic = new Topics();
        $topic->topic('addBook');
        $topicResponse = FCM::sendToTopic($topic, null, $notification, $data);
        $topicResponse->isSuccess();
        $topicResponse->shouldRetry();
        $topicResponse->error();

        $push->status = Push::STATUS_CLOSED;
        $push->save();
    }
}
