<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('chat_id');
            $table->unsignedInteger('author_id');
            $table->enum('type', ['text', 'audio', 'video', 'file', 'image', 'combined', 'forward', 'reply']);
            $table->enum('status', ['sending', 'sent', 'read']);
            $table->string('text');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('chat_id')->references('id')->on('chats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}
