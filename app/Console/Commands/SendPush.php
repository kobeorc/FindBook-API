<?php

namespace App\Console\Commands;

use App\Models\Book;
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
        $book = Book::query()->inRandomOrder()->limit(1)->first();

        $custom_data = [
            'book_name' => $book->name,
            'book_description' => $book->description,
            'book_image' => $book->images()->first()->path,
            'count' => 3
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
    }
}
