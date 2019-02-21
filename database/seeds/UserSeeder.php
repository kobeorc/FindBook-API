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
        factory(\App\Models\User::class,1)->create(['role'=>'admin'])->each(function (\App\Models\User $user){
            $user->auth_token()->saveMany(factory(\App\Models\UserAuthToken::class,1)->make());
        });

        factory(\App\Models\User::class,100)->create()->each(function (\App\Models\User $user){
            $user->auth_token()->saveMany(factory(\App\Models\UserAuthToken::class,5)->make());
            $user->inventory()->saveMany(factory(\App\Models\Book::class,5)->create());
        });


    }
}
