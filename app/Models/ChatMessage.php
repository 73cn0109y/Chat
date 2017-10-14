<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
	protected $fillable = [
		'chat_id',
		'sender_id',
		'message',
	];

	public function chat()
	{
		return $this->belongsTo(Chat::class, 'chat_id');
	}

	public function sender()
	{
		return $this->hasOne(ChatMember::class, 'id', 'sender_id');
	}

	public function attachments()
	{
		return $this->hasMany(ChatAttachment::class, 'message_id');
	}

	public function format($include_attachments = false)
	{
		$data = [
			'id'        => $this->id,
			'sender'    => $this->sender->user->format(),
			'message'   => $this->message,
			'createdAt' => $this->created_at->format('Y-m-d H:i:s\Z'),
		];

		if ($include_attachments) {
			$data['attachments'] = $this->attachments->transform(function (ChatAttachment $attachment) {
				return $attachment->format();
			});
		}

		return $data;
	}
}
