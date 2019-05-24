<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class SendPushTest extends Command
{
    protected $signature = 'push:test';

    protected $description = 'Pedal\'ka';

    public function handle()
    {
        $book = Book::query()->inRandomOrder()->limit(1)->first();

        $authors = $book->authors()->exists() ? implode($book->authors->map(function ($item) {return $item->full_name;})->all(), ', ') : '';
        $images = $book->images()->exists() ? $book->images()->first()->path : '';

        $custom_data = [
            'book_name' => $book->name,
            'book_author' => $authors,
            'book_image' => $images,
            'count_of_new' => 5,
            'book_ids' => [1,2,3,4,5]
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
