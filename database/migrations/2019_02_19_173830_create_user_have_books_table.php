<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHaveBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_have_books', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('book_id');
            $table->dateTime('archived_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('book_id')->references('id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_have_books');
    }
}
