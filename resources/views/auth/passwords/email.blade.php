@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="card">
					<div class="card-header">Reset Password</div>

					<div class="card-block">
						@if (session('status'))
							<div class="alert alert-success">
								{{ session('status') }}
							</div>
						@endif

						<form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
							{{ csrf_field() }}

							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
								<label for="email" class="col-md-4 form-control-label text-right">E-Mail Address</label>

								<div class="col-md-6">
									<input id="email" type="email" class="form-control" name="email"
									       value="{{ old('email') }}" required>

									@if ($errors->has('email'))
										<span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary">
										Send Password Reset Link
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
