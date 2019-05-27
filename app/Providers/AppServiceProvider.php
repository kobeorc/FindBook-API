<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Chat\ChatMessage;
use App\Observers\BookObserver;
use App\Observers\ChatMessageObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Book::observe(BookObserver::class);
        ChatMessage::observe(ChatMessageObserver::class);
    }

    public function register()
    {
    }
}
