<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class SendPush extends Command
{
    protected $signature = 'push:send';
    protected $description = 'Pedal\'ka';

    public function handle()
    {
        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello world')
            ->setSound('default');

//        $notification = $notificationBuilder->build();
        $topic = new Topics();
        $topic->topic('news');

        /**
         * Build Custom Data
         */
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        /**
         *
         */

        $topicResponse = FCM::sendToTopic($topic, $option, $notification, $data);

        $topicResponse->isSuccess();
        $topicResponse->shouldRetry();
        $topicResponse->error();
    }
}
