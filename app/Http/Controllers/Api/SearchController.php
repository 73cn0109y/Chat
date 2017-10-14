<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
	public function users()
	{
		$query = request('q') ?? request('query');

		if (empty($query)) {
			return response()->json([
				'success' => false,
				'message' => 'You need to specify a query!',
			], 400);
		}

		$users = User::where('name', 'like', '%' . $query . '%')
		             ->where('name', '!=', \Auth::user()->name)
		             ->paginate(request('perPage') ?? 25);

		$users->getCollection()->transform(function (User $user) {
			return $user->format();
		});

		return response()->json([
			'success' => true,
			'data'    => $users,
		]);
	}
}
