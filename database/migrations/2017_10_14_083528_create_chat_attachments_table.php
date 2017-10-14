<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatAttachmentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chat_attachments', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('message_id')->index();
			$table->string('file_name');
			$table->timestamps();

			$table->foreign('message_id')
			      ->references('id')->on('chat_messages')
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
		Schema::dropIfExists('chat_attachments');
	}
}
