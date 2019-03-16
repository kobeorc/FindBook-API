<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 1)->create(['role' => 'admin', 'email' => 'admin@findbook.info', 'password' => \Illuminate\Support\Facades\Hash::make('password123456')])->each(function (\App\Models\User $user) {
            $user->auth_token()->saveMany(factory(\App\Models\UserAuthToken::class, 1)->make());
            $user->avatar()->save(factory(\App\Models\Image::class)->make());
        });

        factory(\App\Models\User::class, 1000)->create()->each(function (\App\Models\User $user) {
            $user->auth_token()->saveMany(factory(\App\Models\UserAuthToken::class, 5)->make());
            $user->inventory()->saveMany(factory(\App\Models\Book::class, 5)->create()->each(function (\App\Models\Book $book) {
                $book->images()->saveMany(factory(\App\Models\Image::class, 3)->create());
                $book->categories()->saveMany(\App\Models\Category::query()->inRandomOrder()->limit(3)->get());
                $book->creators()->save(\App\Models\Creator::query()->inRandomOrder()->whereType(\App\Models\Creator::TYPE_PUBLISHER)->first());
                $book->creators()->saveMany(\App\Models\Creator::query()->inRandomOrder()->whereType(\App\Models\Creator::TYPE_AUTHOR)->limit(3)->get());
            }));
            $user->avatar()->save(factory(\App\Models\Image::class)->make());
        });
    }
}
