<?php

Route::group(['middleware' => 'auth:api'], function () {
	Route::group(['prefix' => 'search'], function () {
		Route::get('users', 'Api\SearchController@users');
	});

	Route::group(['prefix' => 'chat'], function () {
		Route::get('', 'Api\ChatController@list');
		Route::post('', 'Api\ChatController@create');

		Route::group(['prefix' => '{chat}'], function () {
			Route::get('', 'Api\ChatController@get');

			Route::group(['prefix' => 'messages'], function () {
				Route::post('', 'Api\ChatController@postMessage');
			});
		});
	});
});
