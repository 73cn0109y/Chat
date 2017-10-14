<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatAttachment extends Model
{
	protected $fillable = [
		'message_id',
		'file_name',
	];

	public function message()
	{
		return $this->belongsTo(ChatMessage::class);
	}

	public function sender()
	{
		return $this->message->sender();
	}

	public function format($include_sender = false, $include_message = false)
	{
		$data = [
			'id'       => $this->id,
			'fileName' => $this->file_name,
			'filePath' => null,
		];

		if ($include_sender)
			$data['sender'] = $this->sender->format();

		if ($include_message)
			$data['message'] = $this->message->format();

		return $data;
	}
}
