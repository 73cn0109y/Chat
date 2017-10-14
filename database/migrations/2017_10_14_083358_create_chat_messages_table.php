<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
			$table->unsignedInteger('chat_id')->index();
			$table->unsignedInteger('sender_id')->index();
			$table->text('message');
			$table->timestamps();

			$table->foreign('chat_id')
			      ->references('id')->on('chats')
			      ->onDelete('cascade');

			$table->foreign('sender_id')
			      ->references('id')->on('chat_members')
			      ->onDelete('cascade');
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
