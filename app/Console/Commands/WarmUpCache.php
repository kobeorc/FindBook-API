<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class WarmUpCache extends Command
{
    const COUNT_ITEMS = 100;
    protected $signature   = 'cache:warmUp';
    protected $description = 'WarmUp cache for /books. first 100 items';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $limit = 10;
        $offset = 0;

        while ($offset < self::COUNT_ITEMS) {
            /** @var Builder $query */
            $query = Book::isActive()->with(['authors', 'publishers', 'categories', 'users', 'images'])->orderByDesc('id');
            $items = $query->limit($limit)->offset($offset)->get();
            $request = collect(['limit' => $limit, 'offset' => $offset]);
            \Cache::put(\CacheHelper::getKeyCache($request), $items, 10);

            $offset += 10;
        }

    }
}
