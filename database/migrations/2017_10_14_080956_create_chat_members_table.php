<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMembersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chat_members', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('chat_id')->index();
			$table->unsignedInteger('user_id')->index();

			$table->foreign('chat_id')
			      ->references('id')->on('chats')
			      ->onDelete('cascade');

			$table->foreign('user_id')
			      ->references('id')->on('users')
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
		Schema::dropIfExists('chat_members');
	}
}
