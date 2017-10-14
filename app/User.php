<?php

namespace App;

use App\Models\Chat;
use App\Models\ChatMember;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, Notifiable;

	protected $fillable = [
		'name',
		'email',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	public function chats()
	{
		return $this->hasManyThrough(Chat::class, ChatMember::class, 'user_id', 'id', 'id', 'chat_id');
	}

	public function format($include_chats = false)
	{
		$data = [
			'id'    => $this->id,
			'name'  => $this->name,
			'email' => $this->email,
		];

		if ($include_chats) {
			$data['chats'] = $this->chats->transform(function (Chat $chat) {
				return $chat->format();
			});
		}

		return $data;
	}
}
