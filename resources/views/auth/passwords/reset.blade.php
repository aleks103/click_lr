@extends('layouts.baseLogin')
@section('title', 'Reset Password')
@section('content')
	<div class="page-wrappers">
		<div class="login-data">
			<div class="login-content">
				<div class="login-header">
					<a href="{{ url('/') }}"><img src="{{ asset('/images/header/logo/login-logo.png') }}" alt="{{ config('app.name', 'Click Perfect') }}"/></a>
					<h2>Reset Password</h2>
				</div>
				@if (session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
				@endif
				<form class="form-horizontal login-form" role="form" method="POST" action="{{ route('password.request') }}">
					{{ csrf_field() }}
					<input type="hidden" name="token" value="{{ $token }}">
					<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<input id="email" type="email" class="form-control" name="email" placeholder="Email" value="{{ $email or old('email') }}" required autofocus/>
						<i class="fa fa-envelope-o"></i>
						@if ($errors->has('email'))
							<span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
						<input id="password" type="password" placeholder="Password" class="form-control" name="password" required/>
						<i class="fa fa-key"></i>
						@if ($errors->has('password'))
							<span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
						<input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required/>
						<i class="fa fa-key"></i>
						@if ($errors->has('password_confirmation'))
							<span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
						@endif
					</div>
					<div class="clearfix"></div>
					<button type="submit" class="btn btn-block">Reset Password</button>
				</form>
			</div>
			<div class="login-footer clearfix">
				<a class="btn btn-link pull-left" href="{{ route('login') }}">
					<i class="fa fa-key"></i> Login
				</a>
			</div>
		</div>
	</div>
@endsection
