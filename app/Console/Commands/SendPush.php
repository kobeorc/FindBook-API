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
    protected $signature = 'push:send';
    protected $description = 'Send pushes about new books in app';

    public function handle()
    {
        $push = Push::query()->whereStatus(Push::STATUS_PENDING)->first();
        if (!$push)
            return;

        $book = Book::findOrFail($push->id);

        $authors = $book->authors()->exists() ? implode($book->authors->map(function ($item) {return $item->full_name;})->all(), ', ') : '';
        $images = $book->images()->exists() ? $book->images()->first()->path : '';

        $custom_data = [
            'book_name'     => $book->name ?? '',
            'book_author'   => $authors,
            'book_image'    => $images,
            'count_of_new'  => $push->count ?? 0,
            'book_ids'      => $push->ids,
        ];

        $notificationBuilder = new PayloadNotificationBuilder();
        $notification = $notificationBuilder->build();
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($custom_data);
        $data = $dataBuilder->build();
        $topic = new Topics();
        $topic->topic('testAddBook');
        $topicResponse = FCM::sendToTopic($topic, null, $notification, $data);
        $topicResponse->isSuccess();
        $topicResponse->shouldRetry();
        $topicResponse->error();

        $push->status = Push::STATUS_CLOSED;
        $push->save();
    }
}
