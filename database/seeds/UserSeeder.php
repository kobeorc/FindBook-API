<?php

use App\Models\Book;
use App\Models\Category;
use App\Models\Creator;
use App\Models\Image;
use App\Models\User;
use App\Models\UserAuthToken;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    private $books_per_user = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->create(['role' => 'admin', 'email' => 'admin@findbook.info', 'password' => \Illuminate\Support\Facades\Hash::make('password123456')])->each(function (User $user) {
            $user->auth_token()->saveMany(factory(UserAuthToken::class, 1)->make());
            $user->avatar()->save(factory(Image::class)->make());
        });

        //пользователь для тестов
        factory(User::class, 1)->create(['role' => 'user', 'email' => 'userForTest@mail.ru', 'password' => \Illuminate\Support\Facades\Hash::make('passwordForTest')])->each(function (User $user) {
            $user->auth_token()->saveMany(factory(UserAuthToken::class, 5)->make());
            $user->inventory()->saveMany(factory(Book::class, $this->books_per_user)->create()->each(function (Book $book) use ($user){
                $book->images()->saveMany(factory(Image::class, 3)->create());
                $book->categories()->saveMany(Category::query()->inRandomOrder()->limit(3)->get());
                $book->creators()->save(Creator::query()->inRandomOrder()->whereType(Creator::TYPE_PUBLISHER)->first());
                $book->creators()->saveMany(Creator::query()->inRandomOrder()->whereType(Creator::TYPE_AUTHOR)->limit(3)->get());
            }));
            $user->avatar()->save(factory(Image::class)->make());

            // архивируем рандомное количество книг, кроме одной (всегда должна быть активна по крайней мере одна книга)
            foreach ($user->inventory()->inRandomOrder()->limit(rand(0,$this->books_per_user - 1))->get() as $item){
                $user->inventory()->updateExistingPivot($item->id,['archived_at'=> Carbon::now()]);
            }

            // добавляем книги в избранное
            foreach (Book::query()->inRandomOrder()->limit(rand(1,$this->books_per_user))->get() as $item){
                $user->favorite()->attach($item->id);
            }
        });

        factory(User::class, 50)->create()->each(function (User $user) {
            $user->auth_token()->saveMany(factory(UserAuthToken::class, 5)->make());
            $user->inventory()->saveMany(factory(Book::class, $this->books_per_user)->create()->each(function (Book $book) use ($user){
                $book->images()->saveMany(factory(Image::class, 3)->create());
                $book->categories()->saveMany(Category::query()->inRandomOrder()->limit(3)->get());
                $book->creators()->save(Creator::query()->inRandomOrder()->whereType(Creator::TYPE_PUBLISHER)->first());
                $book->creators()->saveMany(Creator::query()->inRandomOrder()->whereType(Creator::TYPE_AUTHOR)->limit(3)->get());
            }));
            $user->avatar()->save(factory(Image::class)->make());

            // архивируем рандомное количество книг, кроме одной (всегда должна быть активна по крайней мере одна книга)
            foreach ($user->inventory()->inRandomOrder()->limit(rand(0,$this->books_per_user - 1))->get() as $item){
                $user->inventory()->updateExistingPivot($item->id,['archived_at'=> Carbon::now()]);
            }

            // добавляем книги в избранное
            foreach (Book::query()->inRandomOrder()->limit(rand(1,$this->books_per_user))->get() as $item){
                $user->favorite()->attach($item->id);
            }

        });
    }
}
