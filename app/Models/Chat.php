<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
	protected $fillable = [
		'title',
		'description',
	];

	public function members()
	{
		return $this->hasManyThrough(User::class, ChatMember::class, 'user_id', 'id');
	}

	public function messages()
	{
		return $this->hasMany(ChatMessage::class);
	}

	public function format($include_members = false, $include_dates = false)
	{
		$data = [
			'id'           => $this->id,
			'title'        => $this->title,
			'description'  => $this->description,
			'membersCount' => $this->members()->count(),
		];

		if ($include_members) {
			$data['members'] = $this->members->transform(function (User $user) {
				return $user->format();
			});
		}

		if ($include_dates) {
			$data['createdAt'] = $this->created_at->format('Y-m-d H:i:s\Z');
			$data['updatedAt'] = $this->updated_at->format('Y-m-d H:i:s\Z');
		}

		return $data;
	}
}
