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
    protected $description = 'Pedal\'ka';

    public function handle()
    {
        $push = Push::query()->whereStatus(Push::STATUS_PENDING)->first();
        $book = Book::findOrFail($push->id);

        $custom_data = [
            'book_name' => $book->name,
            'book_author' => $book->authors()->first()->full_name,
            'book_image' => $book->images()->first()->path,
            'count_of_new' => $push->count,
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
