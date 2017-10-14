<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/698a8adccc.css">

	@auth
		<script>window.userID = {{ \Auth::id() }};</script>
	@endauth
</head>

<body>
	<div class="app-root" id="appRoot">
		<nav class="nav">
			<a class="nav-brand" href="{{ url('/') }}">
				{{ config('app.name', 'Laravel') }}
			</a>

			<div class="nav-group"></div>

			<div class="nav-group nav-right">
				@if (Auth::guest())
					<div class="nav-item">
						<a href="{{ route('login') }}" class="nav-link">Login</a>
					</div>

					<div class="nav-item">
						<a href="{{ route('register') }}" class="nav-link">Register</a>
					</div>
				@else
					<v-dropdown alignment="right" class="nav-item">
						<div slot="button">
							{{ $user->name }}
							<span class="fa fa-caret-down"></span>
						</div>

						<div slot="content">
							<div class="dropdown-item" onClick="document.getElementById('logoutForm').submit()">
								Logout

								<form id="logoutForm" action="{{ route('logout') }}" method="POST"
								      style="display: none;">
									{{ csrf_field() }}
								</form>
							</div>
						</div>
					</v-dropdown>
				@endif
			</div>
		</nav>

		@yield('content')
	</div>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
