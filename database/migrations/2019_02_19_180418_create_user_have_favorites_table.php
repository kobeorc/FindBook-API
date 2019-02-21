<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHaveFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_have_favorites', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('book_id');
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_have_favorite');
    }
}
