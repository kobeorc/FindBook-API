<?php

namespace App\Providers;

use App\Models\Book;
use App\Observers\BookObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        Book::observe(BookObserver::class);
    }
}
