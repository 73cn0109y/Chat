<?php

namespace App\Http\Controllers\Api;

use App\Models\Chat;
use App\Http\Controllers\Controller;
use App\Models\ChatMember;
use App\Models\ChatMessage;
use App\User;

class ChatController extends Controller
{
	public function list()
	{
		$user  = \Auth::user();
		$chats = $user->chats->transform(function (Chat $chat) {
			return $chat->format();
		});

		return response()->json([
			'success' => true,
			'data'    => $chats,
		]);
	}

	public function create()
	{
		$user       = \Auth::user();
		$other_user = User::find(request('userID'));

		if (!isset($other_user)) {
			return response()->json([
				'success' => false,
				'message' => 'That user no longer has an account!',
			], 400);
		}

		$chat = Chat::create([
			'title'       => 'Chat with ' . $other_user->name,
			'description' => 'Chat between ' . $user->name . ' and ' . $other_user->name,
		]);

		ChatMember::create([
			'chat_id' => $chat->id,
			'user_id' => $user->id,
		]);

		ChatMember::create([
			'chat_id' => $chat->id,
			'user_id' => $other_user->id,
		]);

		return response()->json([
			'success' => true,
			'data'    => $chat->format(true),
		]);
	}

	public function get(Chat $chat)
	{
		$messages = $chat->messages()
		                 ->latest()
		                 ->take(50)
		                 ->get()
		                 ->transform(function (ChatMessage $message) {
			                 return $message->format(true);
		                 });

		$data             = $chat->format(true, true);
		$data['messages'] = array_reverse($messages->all());

		return response()->json([
			'success' => true,
			'data'    => $data,
		]);
	}

	public function getMessages(Chat $chat)
	{
		$messages = $chat->messages()
		                 ->paginate(request('perPage') ?? 50);

		$messages->getCollection()->transform(function (ChatMessage $message) {
			return $message->format(true);
		});

		return response()->json([
			'success' => true,
			'data'    => $messages,
		]);
	}

	public function postMessage(Chat $chat)
	{
		$chat_member = ChatMember::where([
			'chat_id' => $chat->id,
			'user_id' => \Auth::id(),
		])->select('id')->first();

		\Log::info($chat_member);

		$chat_message = ChatMessage::create([
			'chat_id'   => $chat->id,
			'sender_id' => $chat_member->id,
			'message'   => request('message'),
		]);

		return response()->json([
			'success' => true,
			'data'    => $chat_message->fresh()->format(),
		]);
	}
}
